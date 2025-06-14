function highlightLinksAndHashtags() {
    const containers = document.querySelectorAll('.post_text p');
    if (!containers.length) return;

    const domainExtensions = ['com', 'org', 'net', 'edu', 'gov', 'io', 'co', 'info', 'biz', 'me', 'us', 'uk', 'ca', 'au', 'in'];
    const domainPattern = domainExtensions.join('|');
    const regex = new RegExp(`(#[\\w-]+)|(\\b[\\w.-]+\\.(${domainPattern})\\b)`, 'gi');

    containers.forEach(function(container) {
        container.innerHTML = container.innerHTML.replace(regex, function(match) {
            return `<span class="hyperlink custom-hyperlink">${match}</span>`;
        });
    });
}

// Run on initial load
document.addEventListener('DOMContentLoaded', highlightLinksAndHashtags);

// Run every time the hash changes (when you switch tabs)
window.addEventListener('hashchange', highlightLinksAndHashtags);