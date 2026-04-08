document.addEventListener('DOMContentLoaded', function() {
    // --- 1. Global Airports Data (Initially Empty) ---
    let airports = [];

    // --- 2. Fetch Data from API ---
    // Fetches the full list once. Append a timestamp to break previous 24h browser cache
    const cacheBuster = new Date().getTime();
    fetch(`/api/airports?v=${cacheBuster}`)
        .then(response => response.json())
        .then(data => {
            airports = data;
            // console.log("Airports loaded:", airports.length); // Optional debugging
        })
        .catch(error => console.error('Error loading airports:', error));

    // --- 3. Autocomplete Logic (Updated) ---
    function autocomplete(inp) {
        let currentFocus;
        
        inp.addEventListener("input", function(e) {
            let a, b, i, val = this.value;
            closeAllLists();
            if (!val) { return false; }
            currentFocus = -1;
            
            // Create the dropdown container
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items dark:bg-slate-700");
            this.parentNode.appendChild(a);

            // Cascading search: code → state_name → airport name
            const q = val.toLowerCase();
            let matches = [];

            // 1. Try matching by IATA code first
            matches = airports.filter(item => item.code.includes(q));
            if (matches.length) {
                // Sort: exact code match first, then startsWith, then partial
                matches.sort((a, b) => {
                    if (a.code === q && b.code !== q) return -1;
                    if (b.code === q && a.code !== q) return 1;
                    if (a.code.startsWith(q) && !b.code.startsWith(q)) return -1;
                    if (b.code.startsWith(q) && !a.code.startsWith(q)) return 1;
                    return a.code.localeCompare(b.code);
                });
            }

            // 2. If no code match, try city/state_name
            if (!matches.length) {
                matches = airports.filter(item => item.city.includes(q));
            }

            // 3. If still nothing, try airport name
            if (!matches.length) {
                matches = airports.filter(item => item.name.includes(q));
            }

            matches = matches.slice(0, 20);

            for (i = 0; i < matches.length; i++) {
                let item = matches[i];
                
                b = document.createElement("DIV");
                // Display the Label
                b.innerHTML = item.label;

                // Store the IATA Code (item.value) in the hidden input
                b.innerHTML += "<input type='hidden' value='" + item.value + "'>";
                
                b.addEventListener("click", function(e) {
                    // Set the input value to the Airport Code (e.g., "LOS")
                    inp.value = this.getElementsByTagName("input")[0].value;
                    closeAllLists();

                    // Focus on the next logical input
                    const currentWrapper = inp.closest('.relative.autocomplete, .md\\:col-span-2, .col-span-12');
                    let nextWrapper = currentWrapper.nextElementSibling;
                    while(nextWrapper && !nextWrapper.querySelector('input')) {
                        nextWrapper = nextWrapper.nextElementSibling;
                    }
                    if (nextWrapper) {
                        const nextInput = nextWrapper.querySelector('input');
                        if (nextInput) {
                            if (nextInput._flatpickr) {
                                nextInput._flatpickr.open();
                            } else {
                                nextInput.focus();
                            }
                        }
                    }
                });
                a.appendChild(b);
            }
        });

        inp.addEventListener("keydown", function(e) {
            let x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) { currentFocus++; addActive(x); } 
            else if (e.keyCode == 38) { currentFocus--; addActive(x); } 
            else if (e.keyCode == 13) { e.preventDefault(); if (currentFocus > -1) { if (x) x[currentFocus].click(); } }
        });

        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            for (let i = 0; i < x.length; i++) { x[i].classList.remove("autocomplete-active"); }
        }

        function closeAllLists(elmnt) {
            const x = document.getElementsByClassName("autocomplete-items");
            for (let i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) { x[i].parentNode.removeChild(x[i]); }
            }
        }
        document.addEventListener("click", function (e) { closeAllLists(e.target); });
    }

    // --- Initial Autocompletes ---
    const originInput = document.getElementById("origin");
    const destinationInput = document.getElementById("destination");
    if (originInput) autocomplete(originInput);
    if (destinationInput) autocomplete(destinationInput);

    // --- Toggle Mobile Menu ---
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- Dark Mode Toggle ---
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;
    
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
        if (darkModeToggle) darkModeToggle.innerHTML = '<i class="fas fa-sun text-yellow-400"></i>';
    }
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                darkModeToggle.innerHTML = '<i class="fas fa-sun text-yellow-400"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                darkModeToggle.innerHTML = '<i class="fas fa-moon text-slate-600 dark:text-slate-300"></i>';
            }
        });
    }

    // --- Date Picker Logic ---
    const departurePicker = flatpickr("#departure-date", {
        altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", minDate: "today",
        onChange: (selectedDates, dateStr) => returnPicker.set('minDate', dateStr),
    });
    const returnPicker = flatpickr("#return-date", {
        altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", minDate: "today",
    });

    // --- Accordion & Form Logic ---
    const accordionContent = document.getElementById('booking-bar');
    
    function recalculateAccordionHeight() {
        if (accordionContent && window.innerWidth < 768 && accordionContent.classList.contains('open')) {
            setTimeout(() => {
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
            }, 50);
        }
    }

    // --- Multi-City Logic ---
    const multiCityForm = document.getElementById('multi-city-form');
    const multiCityRowsContainer = document.getElementById('multi-city-rows');
    const addFlightBtn = document.getElementById('add-flight-btn');
    
    function reindexRows() {
        const rows = multiCityRowsContainer.querySelectorAll('.multi-city-row');
        rows.forEach((row, index) => {
            const num = index + 1; // 1-based index
            
            // Find inputs and update their names
            const originInput = row.querySelector('.multi-city-origin');
            if (originInput) originInput.name = `originLocationCode${num}`;

            const destInput = row.querySelector('.multi-city-destination');
            if (destInput) destInput.name = `originDestinationCode${num}`;

            const dateInput = row.querySelector('.multi-city-date');
            if (dateInput) dateInput.name = `departureDate${num}`;
        });
    }

    function updateRemoveButtons() {
        const rows = multiCityRowsContainer.querySelectorAll('.multi-city-row');
        rows.forEach((row) => {
            let removeBtnContainer = row.querySelector('.remove-btn-container');
            if (removeBtnContainer) removeBtnContainer.remove();

            // Only show remove button if we have more than 2 rows
            if (rows.length > 2) {
                const btnContainer = document.createElement('div');
                btnContainer.className = 'remove-btn-container col-span-12 flex justify-end items-center md:absolute md:top-4 md:right-2';
                
                const btn = document.createElement('button');
                btn.className = 'text-red-500 hover:text-red-700 text-sm';
                btn.innerHTML = '<i class="fas fa-minus-circle mr-1 md:hidden"></i><span class="md:hidden">Remove flight</span><i class="fas fa-times-circle text-lg hidden md:block"></i>';
                btn.setAttribute('type', 'button'); 
                btn.onclick = () => { 
                    row.remove(); 
                    updateRemoveButtons();
                    reindexRows();
                    recalculateAccordionHeight();
                };
                
                btnContainer.appendChild(btn);
                row.appendChild(btnContainer);
            }
        });
    }

    function addMultiCityRow() {
        const row = document.createElement('div');
        row.className = 'relative multi-city-row grid grid-cols-12 gap-4 items-end border-t border-slate-200 dark:border-slate-700 pt-4 first:border-t-0 first:pt-0';
        
        row.innerHTML = `
            <div class="col-span-12 md:col-span-5 relative autocomplete">
                <label class="block text-sm font-medium text-white/80 mb-1">Where From?</label>
                <div class="relative">
                    <i class="fas fa-plane-departure absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                    <input type="text" placeholder="Where From?" class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-sky-500 multi-city-origin">
                </div>
            </div>
            <div class="col-span-12 md:col-span-5 relative autocomplete">
                <label class="block text-sm font-medium text-white/80 mb-1">Going To?</label>
                <div class="relative">
                    <i class="fas fa-plane-arrival absolute left-4 top-1/2 -translate-y-1/2 text-white/60"></i>
                    <input type="text" placeholder="Going To?" class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-sky-500 multi-city-destination">
                </div>
            </div>
            <div class="col-span-12 md:col-span-2">
                <label class="block text-sm font-medium text-white/80 mb-1">Departure</label>
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-white/60 pointer-events-none"></i>
                    <input type="text" placeholder="Select date" class="w-full bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg py-3 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-sky-500 multi-city-date">
                </div>
            </div>
        `;
        multiCityRowsContainer.appendChild(row);
        
        flatpickr(row.querySelector('.multi-city-date'), { altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d", minDate: "today" });
        
        // --- Updated Autocomplete calls for new row ---
        autocomplete(row.querySelector('.multi-city-origin'));
        autocomplete(row.querySelector('.multi-city-destination'));
        
        reindexRows();
        updateRemoveButtons();
        recalculateAccordionHeight();
    }
    
    if (addFlightBtn) {
        addFlightBtn.addEventListener('click', addMultiCityRow);
    }

    // --- Trip Type Logic ---
    const tripTypeSelect = document.getElementById('trip-type');
    const singleFlightForm = document.getElementById('single-flight-form');
    const returnDateWrapper = document.getElementById('return-date-wrapper');
    const flexibleDatesWrapper = document.getElementById('flexible-dates-wrapper');

    if (tripTypeSelect) {
        tripTypeSelect.addEventListener('change', function() {
            const isMultiCityValue = (this.value == 2);

        if (isMultiCityValue) {
            singleFlightForm.style.display = 'none';
            multiCityForm.style.display = 'block';
            addFlightBtn.style.display = 'block';
            returnDateWrapper.style.display = 'none';
            flexibleDatesWrapper.style.display = 'none';
            if (multiCityRowsContainer.children.length === 0) {
                addMultiCityRow();
                addMultiCityRow();
            }
        } else {
            singleFlightForm.style.display = 'grid';
            multiCityForm.style.display = 'none';
            addFlightBtn.style.display = 'none';
            
            if (this.value == 1) { // Round Trip
                returnDateWrapper.style.display = 'block';
                flexibleDatesWrapper.style.display = 'flex';
            } else { // One Way
                returnDateWrapper.style.display = 'none';
                flexibleDatesWrapper.style.display = 'none';
            }
        }
        
        recalculateAccordionHeight();
    });
    }

    // --- Passenger Dropdown Logic ---
    const passengerBtn = document.getElementById('passenger-btn');
    const passengerDropdown = document.getElementById('passenger-dropdown');
    const passengerDoneBtn = document.getElementById('passenger-done-btn');
    const passengerControls = document.querySelectorAll('.passenger-control');
    const passengerCountText = document.getElementById('passenger-count-text');
    const counts = { adults: 1, children: 0, infants: 0 };
    
    if (passengerBtn && passengerDropdown && passengerDoneBtn) {
        passengerBtn.addEventListener('click', (e) => { e.stopPropagation(); passengerDropdown.classList.toggle('hidden'); });
        passengerDoneBtn.addEventListener('click', () => passengerDropdown.classList.add('hidden'));
        document.addEventListener('click', (e) => { if (!passengerBtn.contains(e.target) && !passengerDropdown.contains(e.target)) { passengerDropdown.classList.add('hidden'); } });
    }
    
    function updatePassengerDisplay() {
        const total = counts.adults + counts.children + counts.infants;
        passengerCountText.textContent = `${total} Passenger${total > 1 ? 's' : ''}`;
        
        document.getElementById('adults-count').textContent = counts.adults;
        document.getElementById('children-count').textContent = counts.children;
        document.getElementById('infants-count').textContent = counts.infants;

        if(document.getElementById('input-adults')) document.getElementById('input-adults').value = counts.adults;
        if(document.getElementById('input-children')) document.getElementById('input-children').value = counts.children;
        if(document.getElementById('input-infants')) document.getElementById('input-infants').value = counts.infants;
    }

    passengerControls.forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            const type = button.dataset.type;
            const action = button.dataset.action;
            if (action === 'increase') { counts[type]++; } 
            else if (action === 'decrease') { const min = type === 'adults' ? 1 : 0; if (counts[type] > min) { counts[type]--; } }
            updatePassengerDisplay();
        });
    });

    // --- Mobile Accordion Logic ---
    if (accordionHeader && accordionContent && accordionIcon) {
        if (window.innerWidth < 768) { 
            accordionContent.style.maxHeight = '0px'; 
        } else { 
            accordionContent.classList.add('open');
            accordionContent.style.maxHeight = accordionContent.scrollHeight + "px"; 
        }
        accordionHeader.addEventListener('click', () => {
            const isOpen = accordionContent.classList.contains('open');
            if (!isOpen) {
                accordionContent.classList.add('open');
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
                accordionIcon.classList.add('rotate-180');
            } else {
                accordionContent.classList.remove('open');
                accordionContent.style.maxHeight = '0px';
                accordionIcon.classList.remove('rotate-180');
            }
        });
    }
    window.addEventListener('resize', () => {
        if (accordionContent) {
            if (window.innerWidth >= 768) { 
                accordionContent.classList.add('open');
                accordionContent.style.maxHeight = accordionContent.scrollHeight + "px"; 
            } else { 
                if (!accordionContent.classList.contains('open')) {
                    accordionContent.style.maxHeight = '0px'; 
                } else {
                    accordionContent.style.maxHeight = accordionContent.scrollHeight + "px";
                }
            }
        }
    });
});