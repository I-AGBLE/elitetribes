document.addEventListener('DOMContentLoaded', function () {
    const myPostsLink = document.getElementById('my_posts');
    const myFeedLink = document.getElementById('my_feed');
    const myFollowingLink = document.getElementById('my_following');
  
    const myPostsContainer = document.getElementById('my_posts_contents');
    const myFeedContainer = document.getElementById('feed');
    const followingContainer = document.getElementById('following');
  
    // Function to show the correct content based on the URL hash
    function showContent() {
        // Get the current hash from the URL
        const hash = window.location.hash;
  
        // Hide all content
        myPostsContainer.style.display = 'none';
        myFeedContainer.style.display = 'none';
        followingContainer.style.display = 'none';
  
        // Show the content based on the hash
        if (hash === '#my_posts') {
            myPostsContainer.style.display = 'block';
        } else if (hash === '#feed') {
            myFeedContainer.style.display = 'block';
        } else if (hash === '#following') {
            followingContainer.style.display = 'block';
        }
    }
  
    // Event listeners for each link
    myPostsLink.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default anchor action (page reload)
        window.location.hash = '#my_posts'; // Update the URL hash
        showContent(); // Show the content corresponding to the new hash
    });
  
    myFeedLink.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default anchor action (page reload)
        window.location.hash = '#feed'; // Update the URL hash
        showContent(); // Show the content corresponding to the new hash
    });
  
    myFollowingLink.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default anchor action (page reload)
        window.location.hash = '#following'; // Update the URL hash
        showContent(); // Show the content corresponding to the new hash
    });


      // Initially check for the hash and display content
  if (!window.location.hash) {
    // If there's no hash in the URL, set the default hash to #open_scrolls_contents
    window.location.hash = '#my_posts';
  }

  
    // Initially show content based on the current hash
    showContent();
  
    // Listen for changes in the URL hash
    window.addEventListener('hashchange', function () {
        showContent(); // Update content when hash changes
    });
  });
  
  
  
  
  
  
  
  
  
  