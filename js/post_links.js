// Add this script after your HTML or in a DOMContentLoaded event
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.post_text p');
    if (!containers.length) return;

    // List of domain extensions to match
    const domainExtensions = ['com', 'org', 'net', 'edu', 'gov', 'io', 'co', 'info', 'biz', 'me', 'us', 'uk', 'ca', 'au', 'in'];
    const domainPattern = domainExtensions.join('|');

    // Regex: match #word or word ending with .com, .org, etc.
    const regex = new RegExp(`(#[\\w-]+)|(\\b[\\w.-]+\\.(${domainPattern})\\b)`, 'gi');

    containers.forEach(function(container) {
        container.innerHTML = container.innerHTML.replace(regex, function(match) {
            return `<span class="hyperlink custom-hyperlink">${match}</span>`;
        });
    });
});