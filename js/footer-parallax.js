/**
 * Footer Parallax Effect
 * dynamically adjusts the margin-bottom of the main content
 * to reveal the fixed footer.
 */

document.addEventListener('DOMContentLoaded', function() {
    const footerWrapper = document.getElementById('footer-parallax-wrapper');
    const contentWrapper = document.getElementById('site-content-wrapper');
    const body = document.body;

    if (!footerWrapper || !contentWrapper) return;

    function updateFooterParallax() {
        // Only apply on screens where parallax makes sense (e.g. not super small mobile if desired, but request implies generally)
        // Checking if footer is actually fixed (based on CSS)
        const style = window.getComputedStyle(footerWrapper);
        if (style.position === 'fixed') {
             const footerHeight = footerWrapper.offsetHeight;
             contentWrapper.style.marginBottom = footerHeight + 'px';
        } else {
             contentWrapper.style.marginBottom = '0px';
        }
    }

    // Initial calculation
    updateFooterParallax();

    // Update on resize
    window.addEventListener('resize', updateFooterParallax);

    // Observer for DOM changes that might affect footer height (optional but good)
    const observer = new ResizeObserver(updateFooterParallax);
    observer.observe(footerWrapper);
});
