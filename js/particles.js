particlesJS("particles-js", {
  particles: {
    number: {
      value: 100,
      density: {
        enable: true,
        value_area: 800,
      },
    },
    color: {
      value: ["#ffcc00", "#ffffffff"], // Dual colors: yellow + white
    },
    shape: {
      type: ["circle", "edge"], // Two shapes: circles and squares
      stroke: {
        width: 0,
        color: "#000",
      },
    },
    opacity: {
      value: 0.9,
      random: true,
      anim: {
        enable: false,
      },
    },
    size: {
      value: 3,
      random: true,
      anim: {
        enable: false,
      },
    },
    line_linked: {
      enable: true,
      distance: 110,
      color: "#ffffff",
      opacity: 0.3,
      width: 1,
    },
    move: {
      enable: true,
      speed: 1,
      direction: "none",
      random: false,
      straight: false,
      out_mode: "out",
    },
  },
  interactivity: {
    detect_on: "canvas",
    events: {
      onhover: {
        enable: true,
        mode: "repulse",
      },
    },
    modes: {
      repulse: {
        distance: 80,
        duration: 0.4,
      },
    },
  },
  retina_detect: true,
});
