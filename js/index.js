document.addEventListener('DOMContentLoaded', function () {
  const openScrollsLink = document.getElementById('open_scrolls');
  const myTimelineLink = document.getElementById('timeline');

  const openScrollsContainer = document.getElementById('open_scrolls_contents');
  const myTimelineContainer = document.getElementById('my_timeline');

  // Function to show the correct content based on the URL hash
  function homePageContent() {
    const hash = window.location.hash;

    // Hide both containers initially
    openScrollsContainer.style.display = 'none';
    myTimelineContainer.style.display = 'none';

    // Show the correct container based on the hash
    if (hash === '#open_scrolls_contents') {
      openScrollsContainer.style.display = 'block'; // Show open scrolls
    } else if (hash === '#my_timeline') {
      myTimelineContainer.style.display = 'block'; // Show my timeline
    }
  }

  // Event listener for the "Open Scrolls" link
  openScrollsLink.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior (page reload)
    window.location.hash = '#open_scrolls_contents'; // Update the URL hash
    homePageContent(); // Update content based on the new hash
  });

  // Event listener for the "My Timeline" link
  myTimelineLink.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior (page reload)
    window.location.hash = '#my_timeline'; // Update the URL hash
    homePageContent(); // Update content based on the new hash
  });

  // Initially check for the hash and display content
  if (!window.location.hash) {
    // If there's no hash in the URL, set the default hash to #open_scrolls_contents
    window.location.hash = '#open_scrolls_contents';
  }

  // Call the function to display the correct content based on the hash
  homePageContent();

  // Listen for hash changes (if the user changes the hash directly in the URL)
  window.addEventListener('hashchange', function () {
    homePageContent(); // Update content when the hash changes
  });

  // Additional check: if user clicks "My Timeline" and it's not already displayed
  if (window.location.hash === '#my_timeline') {
    homePageContent(); // Ensure that it is shown if the user clicked "My Timeline"
  }
});

