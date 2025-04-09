// Add this JavaScript to replace the previous scroll scripts

$(document).ready(function() {
    // Mobile menu toggle
    $('.menu-toggle').click(function() {
        $('.nav-links').toggleClass('active');
    });
    
    // Smart header scroll functionality
    let lastScrollTop = 0;
    const header = $('header');
    const delta = 10; // Minimum amount of pixels scrolled before triggering
    const headerHeight = header.outerHeight();
    
    // Add a class to set initial state
    header.addClass('visible');
    
    // Calculate proper padding for content
    function updateContentPadding() {
        const currentHeaderHeight = header.hasClass('hidden') ? 0 : header.outerHeight();
        $('.page-container').css('padding-top', (currentHeaderHeight + 40) + 'px');
    }
    
    // Initial padding adjustment
    updateContentPadding();
    
    // Handle window scroll
    $(window).scroll(function() {
        const scrollTop = $(this).scrollTop();
        
        // Make sure scroll is more than delta
        if(Math.abs(lastScrollTop - scrollTop) <= delta) {
            return;
        }
        
        // Add transition class to indicate we're in transition
        header.addClass('transitioning');
        
        // If scrolled down past header height - hide header
        if(scrollTop > lastScrollTop && scrollTop > headerHeight) {
            header.removeClass('visible').addClass('hidden');
            updateContentPadding();
        } 
        // If scrolled up - show header
        else if(scrollTop < lastScrollTop) {
            header.removeClass('hidden').addClass('visible');
            updateContentPadding();
        }
        
        // Only adjust padding when sticky to keep header minimal
        if(scrollTop > 100) {
            header.addClass('sticky');
        } else {
            header.removeClass('sticky');
        }
        
        // Remove transition class after animation completes
        setTimeout(function() {
            header.removeClass('transitioning');
        }, 300); // Same as transition duration (0.3s)
        
        // Update last scroll position
        lastScrollTop = scrollTop;
    });
    
    // Handle window resize to readjust padding
    $(window).resize(function() {
        updateContentPadding();
    });
});