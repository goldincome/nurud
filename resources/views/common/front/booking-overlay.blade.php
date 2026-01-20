<div id="booking-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden transition-all duration-300">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="booking-modal">
            <div class="text-center">
                <div class="mb-4">
                    <div class="relative">
                        <div class="w-16 h-16 border-4 border-gray-200 rounded-full mx-auto animate-spin">
                            <div class="w-full h-full border-4 border-brand-blue rounded-full border-t-transparent"></div>
                        </div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue rounded-full animate-pulse"></div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">We are verifying your selected flight</h3>
                <p class="text-gray-600 text-sm mb-6">Please wait while we confirm availability and pricing</p>
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
    #booking-overlay.show {
        display: flex;
    }
    
    #booking-overlay.show #booking-modal {
        transform: scale(1);
        opacity: 1;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    #booking-modal {
        animation: float 2s ease-in-out infinite;
    }
    
    /* Center the modal vertically and horizontally */
    #booking-overlay {
        align-items: center;
        justify-content: center;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Book Now" button clicks on search results
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('form[action="{{ route("api.offer.verify") }}"] button[type="submit"]')) {
            const overlay = document.getElementById('booking-overlay');
            if (overlay) {
                overlay.classList.add('show');
            }
        }
    });
    
    // Hide overlay when page loads (in case of back navigation)
    window.addEventListener('load', function() {
        const overlay = document.getElementById('booking-overlay');
        if (overlay) {
            overlay.classList.remove('show');
        }
    });
});
</script>
