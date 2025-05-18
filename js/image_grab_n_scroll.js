document.querySelectorAll('.post_images_container').forEach(container => {
  const images = container.querySelectorAll('.post_images img');
  let isDown = false;
  let startX;
  let scrollLeft;

  function updateActiveImage() {
    const containerRect = container.getBoundingClientRect();
    const containerCenter = containerRect.left + containerRect.width / 2;

    let closestImg = null;
    let closestDistance = Infinity;

    images.forEach((img) => {
      const imgRect = img.getBoundingClientRect();
      const imgCenter = imgRect.left + imgRect.width / 2;
      const distance = Math.abs(containerCenter - imgCenter);

      if (distance < closestDistance) {
        closestDistance = distance;
        closestImg = img;
      }
    });

    images.forEach((img) => {
      img.classList.remove('active');
    });

    if (closestImg) {
      closestImg.classList.add('active');
    }
  }

  // Mouse dragging functionality
  container.addEventListener('mousedown', (e) => {
    if (e.target.tagName === 'IMG') {
      e.preventDefault(); // Prevent default image drag behavior
    }

    isDown = true;
    container.classList.add('dragging');
    startX = e.pageX - container.offsetLeft;
    scrollLeft = container.scrollLeft;
  });

  container.addEventListener('mouseleave', () => {
    isDown = false;
    container.classList.remove('dragging');
  });

  container.addEventListener('mouseup', () => {
    isDown = false;
    container.classList.remove('dragging');
  });

  container.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - container.offsetLeft;
    const walk = (x - startX) * 1.5; // adjust scroll speed
    container.scrollLeft = scrollLeft - walk;
  });

  container.addEventListener('scroll', () => {
    requestAnimationFrame(updateActiveImage);
  });

  window.addEventListener('resize', updateActiveImage);
  window.addEventListener('load', updateActiveImage);
});
