const navbar = document.getElementById("mainNavbar");

window.addEventListener("scroll", () => {
  if (window.scrollY > 5) {
    navbar.classList.add("visible", "animate__animated", "animate__fadeInDown");
  } else {
    navbar.classList.remove("visible");
  }
});
