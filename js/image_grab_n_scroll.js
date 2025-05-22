document.querySelectorAll(".post_images_container").forEach((container) => {
  const images = container.querySelectorAll(".post_images img");
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
      img.classList.remove("active");
    });

    if (closestImg) {
      closestImg.classList.add("active");
    }
  }

  // Mouse dragging functionality
  container.addEventListener("mousedown", (e) => {
    if (e.target.tagName === "IMG") {
      e.preventDefault(); // Prevent default image drag behavior
    }

    isDown = true;
    container.classList.add("dragging");
    startX = e.pageX - container.offsetLeft;
    scrollLeft = container.scrollLeft;
  });

  container.addEventListener("mouseleave", () => {
    isDown = false;
    container.classList.remove("dragging");
  });

  container.addEventListener("mouseup", () => {
    isDown = false;
    container.classList.remove("dragging");
  });

  container.addEventListener("mousemove", (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - container.offsetLeft;
    const walk = (x - startX) * 1.5; // adjust scroll speed
    container.scrollLeft = scrollLeft - walk;
  });

  container.addEventListener("scroll", () => {
    requestAnimationFrame(updateActiveImage);
  });

  window.addEventListener("resize", updateActiveImage);
  window.addEventListener("load", updateActiveImage);
});



// File upload functionality
// This code will display the names of the selected files in a div with id "file-names"
const fileInput = document.getElementById("image-upload");
const fileNamesDisplay = document.getElementById("file-names");

fileInput.addEventListener("change", () => {
  const files = Array.from(fileInput.files);
  if (files.length === 0) {
    fileNamesDisplay.innerHTML = "No file selected";
  } else {
    fileNamesDisplay.innerHTML = files.map((f) => f.name).join("<br>");
  }
});

// File upload functionality
// This code will display the names of the selected files in a div with id "file-names"
const fileInputFloating = document.getElementById("image-upload");
const fileNamesDisplayFloating = document.getElementById("file-names-floating-input");

fileInputFloating.addEventListener("change", () => {
  const files = Array.from(fileInputFloating.files);
  if (files.length === 0) {
    fileNamesDisplayFloating.innerHTML = "No file selected";
  } else {
    fileNamesDisplayFloating.innerHTML = files.map((f) => f.name).join("<br>");
  }
});

