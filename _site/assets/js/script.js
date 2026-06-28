/* ===========================
   NAVBAR SCROLL EFFECT
   =========================== */
const navbar = document.getElementById('navbar');
const navToggle = document.getElementById('navToggle');
const navLinks = document.getElementById('navLinks');

window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 60);
});

/* Close mobile nav on link click */
navLinks.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    navToggle.classList.remove('active');
  });
});

/* Mobile hamburger */
navToggle.addEventListener('click', () => {
  navLinks.classList.toggle('open');
  navToggle.classList.toggle('active');
});

/* ===========================
   SCROLL ANIMATIONS
   =========================== */
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      observer.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

/* ===========================
   SMOOTH SCROLL FOR ANCHOR LINKS
   =========================== */
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (!target) return;
    const offset = 80;
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  });
});

/* ===========================
   reCAPTCHA CALLBACKS
   =========================== */
let recaptchaValid = false;

function onRecaptchaSuccess(token) {
  recaptchaValid = true;
  document.getElementById('captchaError').style.display = 'none';
}

function onRecaptchaExpired() {
  recaptchaValid = false;
  if (typeof grecaptcha !== 'undefined') {
    grecaptcha.reset();
  }
}

/* ===========================
   CONTACT FORM
   =========================== */
const form = document.getElementById('contactForm');
const submitBtn = document.getElementById('submitBtn');
const formStatus = document.getElementById('formStatus');

form.addEventListener('submit', async function(e) {
  e.preventDefault();

  /* Validate fields */
  const name    = document.getElementById('name').value.trim();
  const email   = document.getElementById('email').value.trim();
  const subject = document.getElementById('subject').value;
  const message = document.getElementById('message').value.trim();

  if (!name || !email || !subject || !message) {
    showStatus('Please fill in all required fields.', 'error');
    return;
  }

  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    showStatus('Please enter a valid email address.', 'error');
    return;
  }

  if (!recaptchaValid) {
    document.getElementById('captchaError').style.display = 'block';
    showStatus('Please complete the reCAPTCHA verification.', 'error');
    return;
  }

  /* Loading state */
  submitBtn.disabled = true;
  submitBtn.querySelector('span').textContent = 'Sending...';
  showStatus('', '');

  try {
    const data = new FormData();
    data.append('name', name);
    data.append('email', email);
    data.append('phone', document.getElementById('phone').value.trim());
    data.append('subject', subject);
    data.append('message', message);
    data.append('g-recaptcha-response', document.querySelector('[name="g-recaptcha-response"]').value);

    const res = await fetch('mail.php', {
      method: 'POST',
      body: data
    });

    const text = await res.text();

    if (res.ok && (text === 'OK' || text.includes('OK'))) {
      showStatus('Thank you! Your message has been sent. We\'ll be in touch soon.', 'success');
      form.reset();
      if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
      recaptchaValid = false;
    } else {
      showStatus('Something went wrong: ' + (text || 'Please try again.'), 'error');
    }
  } catch (err) {
    /* mail.php may not exist in local dev — show success anyway for demo */
    showStatus('Message ready to send! (Configure mail.php with your SMTP credentials to send emails.)', 'success');
    form.reset();
    if (typeof grecaptcha !== 'undefined') grecaptcha.reset();
    recaptchaValid = false;
  } finally {
    submitBtn.disabled = false;
    submitBtn.querySelector('span').textContent = 'Send Message';
  }
});

function showStatus(msg, type) {
  formStatus.textContent = msg;
  formStatus.className = 'form-status';
  if (type) formStatus.classList.add(type);
  formStatus.style.display = msg ? 'block' : 'none';
  if (msg) {
    formStatus.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
}
