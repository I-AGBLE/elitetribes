document.addEventListener('DOMContentLoaded', function () {
    const usernameInput = document.getElementById('username');
    if (!usernameInput) return;

    usernameInput.addEventListener('input', function () {
        let value = usernameInput.value;

        // Replace all spaces with underscores
        value = value.replace(/ /g, '_');

        // Replace multiple consecutive underscores with a single underscore
        value = value.replace(/_+/g, '_');

        // Remove leading or trailing underscores (optional, if you want)
        // value = value.replace(/^_+|_+$/g, '');

        usernameInput.value = value;
    });
});