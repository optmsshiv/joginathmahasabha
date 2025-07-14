const canvas = document.getElementById("textParticles");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const words = [
  "Unity",
  "धर्म",
  "Service",
  "सशक्तिकरण",
  "Jogi",
  "संस्कृति",
  "Truth",
  "Nath",
];
const particles = [];

class WordParticle {
  constructor(text, x, y, speed, size) {
    this.text = text;
    this.x = x;
    this.y = y;
    this.speed = speed;
    this.size = size;
  }

  draw() {
    ctx.font = `${this.size}px serif`;
    ctx.fillStyle = "rgba(255, 204, 0, 0.2)";
    ctx.fillText(this.text, this.x, this.y);
    ctx.globalCompositeOperation = "lighter";
  }

  update() {
    this.y -= this.speed;
    if (this.y < -20) {
      this.y = canvas.height + 20;
      this.x = Math.random() * canvas.width;
    }
    this.draw();
  }
}

function initParticles() {
  for (let i = 0; i < 30; i++) {
    const text = words[Math.floor(Math.random() * words.length)];
    const x = Math.random() * canvas.width;
    const y = Math.random() * canvas.height;
    const speed = 0.2 + Math.random() * 0.5;
    const size = 16 + Math.random() * 10;
    particles.push(new WordParticle(text, x, y, speed, size));
  }
}

function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  particles.forEach((p) => p.update());
  requestAnimationFrame(animate);
}

initParticles();
animate();

window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});
