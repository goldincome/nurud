<div id="search-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="search-modal">
            <div class="text-center">
                <div class="mb-4">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-gray-200 rounded-full mx-auto animate-spin">
                            <div class="w-full h-full border-4 border-brand-blue rounded-full border-t-transparent"></div>
                        </div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue rounded-full animate-pulse"></div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2" id="overlay-title">Searching Best Price</h3>
                <p class="text-gray-600 text-sm mb-6" id="overlay-subtitle">We're comparing prices across multiple airlines to find you the best deal</p>
                <div class="flex justify-center space-x-2 mb-4">
                    <div class="w-3 h-3 bg-brand-blue rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-3 h-3 bg-brand-blue rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-3 h-3 bg-brand-blue rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
                <div class="text-xs text-gray-500">This may take a few moments...</div>
            </div>
        </div>
    </div>
</div>

<style>
    #search-overlay.show {
        display: flex;
    }
    
    #search-overlay.show #search-modal {
        transform: scale(1);
        opacity: 1;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    #search-modal {
        animation: float 2s ease-in-out infinite;
    }
    
    /* Center the modal vertically and horizontally */
    #search-overlay {
        align-items: center;
        justify-content: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route("search") }}"]');
    const overlay = document.getElementById('search-overlay');
    const modal = document.getElementById('search-modal');
    const title = document.getElementById('overlay-title');
    const subtitle = document.getElementById('overlay-subtitle');
    
    // Function to show overlay with custom text
    function showOverlay(isBooking = false) {
        if (isBooking) {
            title.textContent = "We are verifying your selected flight";
            subtitle.textContent = "Please wait while we confirm availability and pricing";
        } else {
            title.textContent = "Searching Best Price";
            subtitle.textContent = "We're comparing prices across multiple airlines to find you the best deal";
        }
        
        overlay.classList.add('show');
    }
    
    // Handle search form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            showOverlay(false);
            
            // Add slight delay before redirect to show animation
            setTimeout(() => {
                // Form will submit normally, overlay will be hidden when page loads
            }, 500);
        });
    }
    
    // Handle "Book Now" button clicks on search results
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('form[action="{{ route("api.offer.verify") }}"] button[type="submit"]')) {
            showOverlay(true);
        }
    });
    
    // Hide overlay when page loads (in case of back navigation)
    window.addEventListener('load', function() {
        overlay.classList.remove('show');
    });
});
</script>
