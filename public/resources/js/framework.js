/**
 * attributes:
 * - [data-use-framework]: indicates that the framework should be applied
 */

/**
 * Load content via AJAX and update the URL
 *
 * @param target
 * @param url
 */
function load(target, url) {
    const container = document.body;
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.text())
        .then(data => {
            // Replace the body content
            container.innerHTML = data;

            // Update the browser's URL
            history.pushState(null, '', url);
        }).catch(error => console.error('Error loading page:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        if (e.target.tagName === 'A' && e.target.href && e.target.hasAttribute('data-use-framework')) {
            e.preventDefault();

            const href = e.target.href;

            load(document.body, href);

            // Handle browser back/forward buttons
            window.onpopstate = function () {
                load(document.body, location.href);
            }
        }
    });
});