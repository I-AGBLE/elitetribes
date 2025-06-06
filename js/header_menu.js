
  document.addEventListener('DOMContentLoaded', function () {
    const logo = document.querySelector('.logo');
    const headerMenus = document.querySelector('.header_menus');

    // Toggle visibility on logo click
    logo.addEventListener('click', function (event) {
      event.stopPropagation(); // Prevents the click from bubbling up
      headerMenus.classList.toggle('visible');
      animateMenuItems(headerMenus.classList.contains('visible'));
    });

    // Hide menu if clicking outside of logo or menu
    document.addEventListener('click', function (event) {
      if (!headerMenus.contains(event.target) && !logo.contains(event.target)) {
        if (headerMenus.classList.contains('visible')) {
          headerMenus.classList.remove('visible');
          animateMenuItems(false);
        }
      }
    });

    // Function to animate menu items
    function animateMenuItems(show) {
      const menuItems = headerMenus.querySelectorAll('li');
      menuItems.forEach((item, index) => {
        if (show) {
          setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
          }, (index + 1) * 100);
        } else {
          item.style.opacity = '0';
          item.style.transform = 'translateY(-10px)';
        }
      });
    }
  });