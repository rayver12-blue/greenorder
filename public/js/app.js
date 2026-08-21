// GreenOrder - Main JS

// ── Password Toggle ─────────────────────────────────
document.querySelectorAll('.eye-toggle').forEach(btn => {
  btn.addEventListener('click', function () {
    const input = this.closest('.input-wrapper').querySelector('input');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    this.innerHTML = isHidden
      ? `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
      : `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
  });
});

// ── Password Strength ────────────────────────────────
function checkStrength(password) {
  const rules = {
    length:    password.length >= 8,
    uppercase: /[A-Z]/.test(password),
    lowercase: /[a-z]/.test(password),
    number:    /[0-9]/.test(password),
    special:   /[^A-Za-z0-9]/.test(password),
  };
  const score = Object.values(rules).filter(Boolean).length;
  return { rules, score };
}

const pwdInput = document.getElementById('password');
if (pwdInput) {
  pwdInput.addEventListener('input', function () {
    const { rules, score } = checkStrength(this.value);
    const tracks = document.querySelectorAll('.strength-track');
    const label = document.querySelector('.strength-label');
    const ruleEls = {
      length:    document.querySelector('[data-rule="length"]'),
      uppercase: document.querySelector('[data-rule="uppercase"]'),
      lowercase: document.querySelector('[data-rule="lowercase"]'),
      number:    document.querySelector('[data-rule="number"]'),
      special:   document.querySelector('[data-rule="special"]'),
    };

    tracks.forEach((t, i) => {
      t.className = 'strength-track';
      if (i < score) {
        if (score <= 1) t.classList.add('weak');
        else if (score <= 2) t.classList.add('fair');
        else if (score <= 3) t.classList.add('good');
        else t.classList.add('strong');
      }
    });

    const labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const colors = ['', '#ef4444', '#f59e0b', '#22c55e', '#16a34a', '#15803d'];
    if (label) {
      label.textContent = this.value ? labels[score] || '' : '';
      label.style.color = colors[score] || '';
    }

    Object.entries(rules).forEach(([key, met]) => {
      const el = ruleEls[key];
      if (!el) return;
      el.classList.toggle('met', met);
      el.querySelector('svg').innerHTML = met
        ? '<polyline points="20 6 9 17 4 12"/>'
        : '<circle cx="12" cy="12" r="10"/>';
    });

    // Also trigger confirm check
    const confirmInput = document.getElementById('password_confirmation');
    if (confirmInput && confirmInput.value) confirmInput.dispatchEvent(new Event('input'));
  });
}

// ── Confirm Password ─────────────────────────────────
const confirmInput = document.getElementById('password_confirmation');
if (confirmInput) {
  confirmInput.addEventListener('input', function () {
    const pwd = document.getElementById('password').value;
    const confirmFeedback = document.getElementById('confirm-feedback');
    if (!confirmFeedback) return;
    if (!this.value) { confirmFeedback.textContent = ''; this.classList.remove('is-valid', 'is-invalid'); return; }
    if (this.value === pwd) {
      confirmFeedback.textContent = 'Password match';
      confirmFeedback.style.color = 'var(--green-700)';
      this.classList.add('is-valid'); this.classList.remove('is-invalid');
    } else {
      confirmFeedback.textContent = 'Password do not match';
      confirmFeedback.style.color = '#dc2626';
      this.classList.add('is-invalid'); this.classList.remove('is-valid');
    }
  });
}

// ── Role Toggle (Admin Key) ──────────────────────────
const roleSelect = document.getElementById('role');
if (roleSelect) {
  roleSelect.addEventListener('change', function () {
    const adminKeyGroup = document.getElementById('admin-key-group');
    if (!adminKeyGroup) return;
    if (this.value === 'admin') {
      adminKeyGroup.style.display = 'block';
      adminKeyGroup.querySelector('input').required = true;
    } else {
      adminKeyGroup.style.display = 'none';
      adminKeyGroup.querySelector('input').required = false;
      adminKeyGroup.querySelector('input').value = '';
    }
  });
}

// ── Day Tabs ─────────────────────────────────────────
document.querySelectorAll('.day-tab').forEach(tab => {
  tab.addEventListener('click', function () {
    document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active'));
    this.classList.add('active');
    const day = this.dataset.day;
    document.querySelectorAll('.products-section').forEach(s => {
      s.style.display = s.dataset.day === day ? 'block' : 'none';
    });
  });
});

// ── Search Filter ─────────────────────────────────────
const searchInput = document.getElementById('product-search');
if (searchInput) {
  searchInput.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
      const name = card.querySelector('.product-name').textContent.toLowerCase();
      card.style.display = name.includes(q) ? '' : 'none';
    });
  });
}

// ── Cart ─────────────────────────────────────────────
let cart = JSON.parse(localStorage.getItem('greenorder_cart') || '[]');
function updateCartCount() {
  const badge = document.getElementById('cart-count');
  if (badge) {
    const total = cart.reduce((s, i) => s + i.qty, 0);
    badge.textContent = total;
    badge.style.display = total > 0 ? 'flex' : 'none';
  }
}
updateCartCount();

// ── Alert dismiss ─────────────────────────────────────
document.querySelectorAll('.alert-dismiss').forEach(btn => {
  btn.addEventListener('click', function () { this.closest('.alert').remove(); });
});

