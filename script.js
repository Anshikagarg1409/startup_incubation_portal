/* ============================================================
   Hamburger Menu Toggle (Mobile Navbar)
   ============================================================ */
const hamburger = document.querySelector(".hamburger");
const navLinks = document.querySelector(".nav-links");
const navButtons = document.querySelector(".nav-buttons");

if (hamburger) {
    hamburger.addEventListener("click", function () {
        navLinks.classList.toggle("active");
        navButtons.classList.toggle("active");
    });
}

/* ============================================================
   Smooth Scrolling for Navbar Links
   ============================================================ */
const allNavLinks = document.querySelectorAll('a[href^="#"]');

allNavLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
        const targetId = link.getAttribute("href");
        const targetSection = document.querySelector(targetId);

        if (targetSection) {
            event.preventDefault();
            targetSection.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});

/* ============================================================
   Contact Form Validation
   ============================================================ */
const contactForm = document.getElementById("contactForm");

if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
        const nameField = document.getElementById("name");
        const emailField = document.getElementById("email");
        const messageField = document.getElementById("message");

        const name = nameField.value.trim();
        const email = emailField.value.trim();
        const message = messageField.value.trim();

        // Simple email pattern check
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (name === "") {
            event.preventDefault();
            alert("Please enter your name.");
            return;
        }

        if (!emailPattern.test(email)) {
            event.preventDefault();
            alert("Please enter a valid email address.");
            return;
        }

        if (message === "") {
            event.preventDefault();
            alert("Please enter your message.");
            return;
        }

        alert("Message sent successfully!");
    });
}

/* ============================================================
   Register Form Validation
   ============================================================ */
const registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
        const fullNameField = document.getElementById("fullName");
        const emailField = document.getElementById("email");
        const passwordField = document.getElementById("password");
        const confirmPasswordField = document.getElementById("confirmPassword");

        const fullName = fullNameField.value.trim();
        const email = emailField.value.trim();
        const password = passwordField.value.trim();
        const confirmPassword = confirmPasswordField.value.trim();

        if (fullName === "") {
            event.preventDefault();
            alert("Full name is required.");
            return;
        }

        if (email === "") {
            event.preventDefault();
            alert("Email is required.");
            return;
        }

        if (password.length < 6) {
            event.preventDefault();
            alert("Password must be at least 6 characters long.");
            return;
        }

        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match.");
            return;
        }

        // Save user's name in Local Storage after successful registration
      
    });
}

/* ============================================================
   Login Form Validation
   ============================================================ */
const loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
        const emailField = document.getElementById("email");
const passwordField = document.getElementById("password");
        const email = emailField.value.trim();
        const password = passwordField.value.trim();

        if (email === "") {
            event.preventDefault();
            alert("Email is required.");
            return;
        }

        if (password === "") {
            event.preventDefault();
            alert("Password is required.");
            return;
        }

        
    });
}

/* ============================================================
   Search Bar (Filter Service Cards)
   ============================================================ */
const searchInput = document.getElementById("searchInput");
const serviceCards = document.querySelectorAll(".service-card");

if (searchInput) {
    searchInput.addEventListener("keyup", function () {
        const searchValue = searchInput.value.toLowerCase();

        serviceCards.forEach(function (card) {
            const cardTitle = card.querySelector("h3").textContent.toLowerCase();

            if (cardTitle.includes(searchValue)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
}

/* ============================================================
   Image Slider (Hero / Banner)
   ============================================================ */
const sliderImages = document.querySelectorAll(".slider-image");
const nextButton = document.getElementById("nextSlide");
const prevButton = document.getElementById("prevSlide");
let currentSlide = 0;
let slideTimer;

function showSlide(index) {
    if (sliderImages.length === 0) {
        return;
    }

    // Loop back to the first or last slide if out of range
    if (index >= sliderImages.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = sliderImages.length - 1;
    } else {
        currentSlide = index;
    }

    sliderImages.forEach(function (image, i) {
        image.style.display = (i === currentSlide) ? "block" : "none";
    });
}

function nextSlide() {
    showSlide(currentSlide + 1);
}

function prevSlide() {
    showSlide(currentSlide - 1);
}

function startAutoSlide() {
    slideTimer = setInterval(nextSlide, 3000);
}

if (sliderImages.length > 0) {
    showSlide(currentSlide);
    startAutoSlide();

    if (nextButton) {
        nextButton.addEventListener("click", function () {
            clearInterval(slideTimer);
            nextSlide();
            startAutoSlide();
        });
    }

    if (prevButton) {
        prevButton.addEventListener("click", function () {
            clearInterval(slideTimer);
            prevSlide();
            startAutoSlide();
        });
    }
}

/* ============================================================
   Local Storage - User Dashboard Display
   ============================================================ */
const welcomeMessage = document.getElementById("welcomeMessage");
const statusDisplay = document.getElementById("applicationStatus");

if (welcomeMessage) {
    const savedName = localStorage.getItem("userName");

    if (savedName) {
        welcomeMessage.textContent = "Welcome, " + savedName;
    } else {
        welcomeMessage.textContent = "Welcome, Guest";
    }
}

if (statusDisplay) {
    const savedStatus = localStorage.getItem("applicationStatus");

    if (savedStatus) {
        statusDisplay.textContent = savedStatus;
    } else {
        statusDisplay.textContent = "Pending";
    }
}

/* ============================================================
   Logout Button - Clear Local Storage
   ============================================================ */
const logoutButton = document.getElementById("logoutButton");

if (logoutButton) {
    logoutButton.addEventListener("click", function () {
        localStorage.removeItem("userName");
        localStorage.removeItem("applicationStatus");
        alert("You have been logged out.");
        window.location.href = "login.html";
    });
}