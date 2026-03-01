// NAVBAR TOGGLE
const menuToggle = document.getElementById("menuToggle");
const navMenu = document.getElementById("navMenu");

menuToggle.addEventListener("click", () => {
    navMenu.style.display =
        navMenu.style.display === "flex" ? "none" : "flex";
});

// SCROLL ANIMATION
const revealElements = document.querySelectorAll('.fade-up, .fade-left, .fade-right');

function revealOnScroll() {
    const triggerBottom = window.innerHeight * 0.85;

    revealElements.forEach(el => {
        const rect = el.getBoundingClientRect().top;

        if (rect < triggerBottom) {
            el.classList.add('show');
        }
    });
}

window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);

// Mencegah Halaman reload

document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault(); // cegah reload halaman

    let formData = new FormData(this);

    fetch("process_landingpage.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        alert("Pesan berhasil dikirim!"); 
        document.getElementById("contactForm").reset(); // reset form
    })
    .catch(error => {
        alert("Terjadi kesalahan, coba lagi.");
        console.error(error);
    });
});