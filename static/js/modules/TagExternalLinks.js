
/**
 * Tags external links in the document with appropriate attributes and styling.
 *
 * This function identifies all anchor elements with href attributes and determines
 * if they point to external domains. External links are enhanced with:
 * - Accessibility label indicating they open in a new tab
 * - target="_blank" to open in new tab
 * - rel="noopener noreferrer" for security
 * - Custom CSS class "extLink" for styling
 *
 * Links are considered external if their host differs from the current page's host.
 * Malformed URLs are silently ignored.
 *
 * @function tagExternalLinks
 * @returns {void}
 */

function tagExternalLinks() {
    const currentHost = window.location.host;

    document.querySelectorAll('a[href]').forEach(link => {
        try {
            const url = new URL(link.href, window.location.href);

            // If the link's host is different from the current host, treat as external
            if (url.host !== currentHost) {
                link.setAttribute('aria-description', 'External link, opens in a new tab');
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer'); // Security best practice
                link.classList.add('extLink'); // Add a custom class for icons or other styling
            }
        } catch (e) {
            // Ignore malformed URLs
        }
    });
}

export default tagExternalLinks;
