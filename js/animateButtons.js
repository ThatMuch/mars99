/**
 * Button Animation Handler
 * Ensures all .btn elements have the required structure for animations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all buttons that might not have the proper structure
    const buttons = document.querySelectorAll('.btn');

    buttons.forEach(button => {
        // Check if button already has btn__content wrapper
        const hasContent = button.querySelector('.btn__content');
        const hasOverlay = button.querySelector('.btn__overlay');

        // If missing the structure, wrap existing content
        if (!hasContent) {
            const content = document.createElement('div');
            content.className = 'btn__content';

            // Move all child nodes into the content wrapper
            while (button.firstChild) {
                content.appendChild(button.firstChild);
            }

            button.appendChild(content);
        }

        // Add overlay if missing
        if (!hasOverlay) {
            const overlay = document.createElement('div');
            overlay.className = 'btn__overlay';
            button.appendChild(overlay);
        }
    });
});
