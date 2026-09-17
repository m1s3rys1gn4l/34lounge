/* ════════════════════════════════════════
   CART STATE
   MENU, CATEGORY_LABELS and WA_NUMBER are injected as globals
   by the Blade view before this script loads.
════════════════════════════════════════ */
let cart = JSON.parse(localStorage.getItem('34cart') || '[]');

function saveCart(){ localStorage.setItem('34cart', JSON.stringify(cart)); }

function addToCart(id, category) {
  const item = MENU[category].find(i => i.id === id);
  if (!item) return;
  const existing = cart.find(c => c.id === id);
  if (existing) existing.qty++;
  else cart.push({id, name:item.name, price:item.price, qty:1, emoji:item.emoji, img:item.img});
  saveCart(); renderCart(); updateCartCount();
  showToast('Added: ' + item.name);
}

function removeFromCart(id) {
  cart = cart.filter(c => c.id !== id);
  saveCart(); renderCart(); updateCartCount();
}

function changeQty(id, delta) {
  const item = cart.find(c => c.id === id);
  if (!item) return;
  item.qty = Math.max(1, item.qty + delta);
  saveCart(); renderCart(); updateCartCount();
}

function updateCartCount() {
  const total = cart.reduce((s,i) => s + i.qty, 0);
  const el = document.getElementById('cartCount');
  el.textContent = total;
  el.classList.toggle('show', total > 0);
}

function renderCart() {
  const container = document.getElementById('cartItems');
  const subtotalEl = document.getElementById('cartSubtotal');
  if (!cart.length) {
    container.innerHTML = '<div class="cart-empty">Your cart is empty.<br><small style="opacity:.6">Add items from the menu.</small></div>';
    subtotalEl.textContent = CURRENCY + ' 0.000';
    return;
  }
  let total = 0;
  container.innerHTML = cart.map(item => {
    total += item.price * item.qty;
    const imgHtml = item.img
      ? `<img class="ci-img" src="${item.img}" alt="${item.name}" onerror="this.style.display='none'">`
      : `<div class="ci-img-ph">${item.emoji}</div>`;
    return `<div class="cart-item">
      ${imgHtml}
      <div class="ci-info">
        <div class="ci-name">${item.name}</div>
        <div class="ci-price">${CURRENCY} ${item.price.toFixed(3)}</div>
        <div class="ci-qty">
          <button class="ci-qty-btn" onclick="changeQty('${item.id}',-1)">−</button>
          <span class="ci-qty-val">${item.qty}</span>
          <button class="ci-qty-btn" onclick="changeQty('${item.id}',1)">+</button>
          <button class="ci-remove" onclick="removeFromCart('${item.id}')">✕</button>
        </div>
      </div>
    </div>`;
  }).join('');
  subtotalEl.textContent = CURRENCY + ' ' + total.toFixed(3);
}

function toggleCart() {
  document.getElementById('cartDrawer').classList.toggle('open');
  document.getElementById('cartOverlay').classList.toggle('open');
}

function checkout() {
  if (!cart.length) { showToast('Cart is empty!'); return; }
  const lines = cart.map(i => `• ${i.name} x${i.qty} = ${CURRENCY} ${(i.price*i.qty).toFixed(3)}`).join('\n');
  const total = cart.reduce((s,i) => s + i.price*i.qty, 0);
  const msg = encodeURIComponent(`🍽 *Order from ${RESTAURANT_NAME}*\n\n${lines}\n\n*Total: ${CURRENCY} ${total.toFixed(3)}*\n\nPlease confirm my order. Thank you!`);
  window.open(`https://wa.me/${WA_NUMBER}?text=${msg}`, '_blank');
}

function orderItemViaWhatsApp(itemId, category) {
  const item = MENU[category].find(i => i.id === itemId);
  if (!item) return;
  const msg = encodeURIComponent(`🍽 *Order from ${RESTAURANT_NAME}*\n\n• ${item.name} x1 = ${CURRENCY} ${item.price.toFixed(3)}\n\n*Total: ${CURRENCY} ${item.price.toFixed(3)}*\n\nPlease confirm my order. Thank you!`);
  window.open(`https://wa.me/${WA_NUMBER}?text=${msg}`, '_blank');
}

/* ════════════════════════════════════════
   RENDER ITEMS
════════════════════════════════════════ */
function getImg(id, fallbackImg) {
  return fallbackImg || '';
}

function imgFallback(el, emoji) {
  el.parentElement.innerHTML = `<div class="item-img-ph">${emoji}<small>No Image</small></div>`;
}

function modalImgFallback(el, emoji) {
  el.parentElement.innerHTML = `<div class="modal-img-ph">${emoji}</div>`;
}

function renderCard(item, category) {
  const imgSrc = getImg(item.id, item.img);
  const placeholderBody = SHOW_PLACEHOLDER
    ? `${item.emoji}<small>No Image</small>${item.badge?`<span class="item-badge" style="position:static;display:block;margin-top:.3rem">${item.badge}</span>`:''}`
    : (item.badge ? `<span class="item-badge" style="position:static;display:block;margin-top:.3rem">${item.badge}</span>` : '');
  const imgHtml = imgSrc
    ? `<div class="item-img-wrap"><img src="${imgSrc}" alt="${item.name}" loading="lazy" onerror="imgFallback(this,'${item.emoji}')"/>${item.badge?`<span class="item-badge">${item.badge}</span>`:''}</div>`
    : `<div class="item-img-ph">${placeholderBody}</div>`;
  return `<div class="item-card" data-id="${item.id}" data-cat="${category}">
    ${imgHtml}
    <div class="item-body">
      <div class="item-name">${item.name}</div>
      ${(SHOW_ARABIC && item.ar)?`<div class="item-name-ar">${item.ar}</div>`:''}
      ${item.desc?`<div class="item-desc">${item.desc}</div>`:''}
      <div class="item-footer">
        <div class="item-price">${CURRENCY} ${item.price.toFixed(3)}</div>
        <button class="add-btn" onclick="addToCart('${item.id}','${category}')">+</button>
      </div>
    </div>
  </div>`;
}

function renderAllMenus() {
  Object.keys(MENU).forEach(cat => {
    const grid = document.getElementById('grid-' + cat);
    if (grid) grid.innerHTML = MENU[cat].map(item => renderCard(item, cat)).join('');
  });
}

/* ════════════════════════════════════════
   SEARCH
════════════════════════════════════════ */
const allItems = [];
Object.keys(MENU).forEach(cat => MENU[cat].forEach(item => allItems.push({...item, category:cat})));

document.getElementById('searchInput').addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  const box = document.getElementById('searchResults');
  if (!q) { box.classList.remove('open'); return; }
  const matches = allItems.filter(i => i.name.toLowerCase().includes(q) || (i.ar && i.ar.includes(q)) || (i.desc && i.desc.toLowerCase().includes(q))).slice(0, 8);
  if (!matches.length) { box.classList.remove('open'); return; }
  const imgSrc = id => getImg(id, '');
  box.innerHTML = matches.map(i => {
    const src = imgSrc(i.id);
    const img = src ? `<img class="sr-img" src="${src}" alt="${i.name}">` : `<div class="sr-img-ph">${i.emoji}</div>`;
    return `<div class="sr-item" onclick="scrollToItem('${i.id}','${i.category}')">
      ${img}
      <div><div class="sr-name">${i.name}${(SHOW_ARABIC && i.ar)?` · <span style="direction:rtl;font-size:.75rem;color:var(--gold-dim)">${i.ar}</span>`:''}</div><div class="sr-price">${CURRENCY} ${i.price.toFixed(3)}</div></div>
    </div>`;
  }).join('');
  box.classList.add('open');
});

document.addEventListener('click', e => {
  if (!e.target.closest('.search-wrap')) document.getElementById('searchResults').classList.remove('open');
});

function scrollToItem(id, category) {
  document.getElementById('searchResults').classList.remove('open');
  document.getElementById('searchInput').value = '';
  const section = document.getElementById(category);
  if (section) { section.scrollIntoView({behavior:'smooth'}); }
  setTimeout(() => {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (card) { card.style.outline = '2px solid var(--gold)'; setTimeout(() => card.style.outline = '', 2000); }
  }, 600);
}

/* ════════════════════════════════════════
   HERO SWIPER
════════════════════════════════════════ */
let swiperIdx = 0;
const slides = document.querySelectorAll('.slide');
const track = document.getElementById('swiperTrack');
const dotsWrap = document.getElementById('swiperDots');

// Build dots
slides.forEach((_,i) => {
  const d = document.createElement('div');
  d.className = 'swiper-dot' + (i===0?' active':'');
  d.onclick = () => swiperGo(i);
  dotsWrap.appendChild(d);
});

function swiperGo(n) {
  slides[swiperIdx].classList.remove('active');
  document.querySelectorAll('.swiper-dot')[swiperIdx].classList.remove('active');
  swiperIdx = (n + slides.length) % slides.length;
  track.style.transform = `translateX(-${swiperIdx*100}%)`;
  slides[swiperIdx].classList.add('active');
  document.querySelectorAll('.swiper-dot')[swiperIdx].classList.add('active');
}

function swiperMove(dir) { swiperGo(swiperIdx + dir); }

// Auto-advance
let swiperTimer = setInterval(() => swiperMove(1), 5500);
document.getElementById('heroSwiper').addEventListener('mouseenter', () => clearInterval(swiperTimer));
document.getElementById('heroSwiper').addEventListener('mouseleave', () => { swiperTimer = setInterval(() => swiperMove(1), 5500); });

// Touch swipe
let touchX = 0;
document.getElementById('heroSwiper').addEventListener('touchstart', e => touchX = e.touches[0].clientX, {passive:true});
document.getElementById('heroSwiper').addEventListener('touchend', e => {
  const dx = e.changedTouches[0].clientX - touchX;
  if (Math.abs(dx) > 50) swiperMove(dx < 0 ? 1 : -1);
}, {passive:true});

/* ════════════════════════════════════════
   CATEGORY NAV — sticky bar + active section tracking
   A single passive, rAF-throttled scroll listener replaces the old
   IntersectionObserver-per-section approach. It only touches the DOM
   when the active category actually changes, so it stays cheap even
   on long scroll-throughs of the full menu.
════════════════════════════════════════ */
const catNav = document.querySelector('.cat-nav');
const catItems = document.querySelectorAll('.cat-item');
const sections = Array.from(document.querySelectorAll('section[id]'));

function syncStickyOffset() {
  const offset = catNav.offsetHeight;
  document.documentElement.style.scrollPaddingTop = offset + 'px';
  return offset;
}
let stickyOffset = syncStickyOffset();
window.addEventListener('resize', () => { stickyOffset = syncStickyOffset(); }, {passive:true});

catItems.forEach(item => {
  item.addEventListener('click', () => {
    const sec = document.getElementById(item.dataset.target);
    if (sec) sec.scrollIntoView({behavior:'smooth'});
  });
});

// Smooth-scroll for in-page hash links (hero CTA buttons etc.).
document.addEventListener('click', e => {
  const link = e.target.closest('a[href^="#"]');
  if (!link) return;
  const id = link.getAttribute('href').slice(1);
  const target = id && document.getElementById(id);
  if (target) {
    e.preventDefault();
    target.scrollIntoView({behavior:'smooth'});
  }
});

let activeCategoryId = null;
function updateActiveCategory() {
  const probeY = stickyOffset + 4;
  let current = sections[0];
  for (const sec of sections) {
    if (sec.getBoundingClientRect().top <= probeY) current = sec;
    else break;
  }
  if (!current || current.id === activeCategoryId) return;
  activeCategoryId = current.id;
  catItems.forEach(c => c.classList.toggle('active', c.dataset.target === activeCategoryId));
  const activeItem = document.querySelector(`.cat-item[data-target="${activeCategoryId}"]`);
  if (activeItem) centerCatItemHorizontally(activeItem);
}

// Center the active tab within the horizontally-scrolling nav strip only.
// (scrollIntoView() was used before, but since the nav sits inside a
// position:sticky container, the browser also tried to fix up the page's
// *vertical* scroll to match the element's real document position — which
// visually snapped the whole page back to the top. Moving scrollLeft
// directly can never touch the vertical axis, so that bug can't happen.)
function centerCatItemHorizontally(item) {
  const container = item.parentElement;
  const target = item.offsetLeft - (container.clientWidth - item.clientWidth) / 2;
  container.scrollTo({left: Math.max(0, target), behavior:'auto'});
}

let scrollTicking = false;
window.addEventListener('scroll', () => {
  if (scrollTicking) return;
  scrollTicking = true;
  requestAnimationFrame(() => { updateActiveCategory(); scrollTicking = false; });
}, {passive:true});

updateActiveCategory();

/* ════════════════════════════════════════
   WHATSAPP BUBBLE
════════════════════════════════════════ */
function toggleWA() {
  document.getElementById('waChatBox').classList.toggle('open');
}

function waSend() {
  const input = document.getElementById('waInput');
  const msg = input.value.trim();
  if (!msg) return;
  // Open WhatsApp with pre-filled message
  window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(msg)}`, '_blank');
  input.value = '';
}

function waKeydown(e) { if (e.key === 'Enter') waSend(); }

/* ════════════════════════════════════════
   TOAST
════════════════════════════════════════ */
let toastTimer;
function showToast(msg) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => t.classList.remove('show'), 2500);
}

/* ════════════════════════════════════════
   LANGUAGE TOGGLE (EN ↔ AR)
════════════════════════════════════════ */
function toggleLanguage() {
  const isAr = document.getElementById('langToggle').checked;
  document.body.classList.toggle('ar-mode', isAr);
  document.getElementById('lblEn').classList.toggle('active-lang', !isAr);
  document.getElementById('lblAr').classList.toggle('active-lang', isAr);
  document.documentElement.lang = isAr ? 'ar' : 'en';
  localStorage.setItem('34lang', isAr ? 'ar' : 'en');
}
// Restore saved language
(function(){
  if (localStorage.getItem('34lang') === 'ar') {
    document.getElementById('langToggle').checked = true;
    document.body.classList.add('ar-mode');
    document.getElementById('lblEn').classList.remove('active-lang');
    document.getElementById('lblAr').classList.add('active-lang');
    document.documentElement.lang = 'ar';
  }
})();

/* ════════════════════════════════════════
   ITEM DETAIL MODAL
════════════════════════════════════════ */
function openModal(itemId, category) {
  const item = MENU[category].find(i => i.id === itemId);
  if (!item) return;
  const imgSrc = getImg(item.id, item.img);
  const isAr = document.body.classList.contains('ar-mode');

  const imgBlock = imgSrc
    ? `<div class="modal-img-wrap">
        <img src="${imgSrc}" alt="${item.name}" onerror="modalImgFallback(this,'${item.emoji}')"/>
        ${item.badge ? `<div class="modal-badge-row"><span class="item-badge">${item.badge}</span></div>` : ''}
      </div>`
    : `<div style="position:relative">
        <div class="modal-img-ph">${item.emoji}
          <small style="font-family:'Cinzel',serif;font-size:.5rem;letter-spacing:.2em;color:var(--gold-dim);margin-top:.3rem">NO IMAGE YET</small>
        </div>
        ${item.badge ? `<div class="modal-badge-row"><span class="item-badge">${item.badge}</span></div>` : ''}
      </div>`;

  document.getElementById('modalInner').innerHTML = `
    ${imgBlock}
    <div class="modal-body">
      <div class="modal-category-tag">${CATEGORY_LABELS[category] || category}</div>
      <div class="modal-name">${item.name}</div>
      ${SHOW_ARABIC ? `<div class="modal-name-ar" style="${isAr?'':'font-size:1rem'}">${item.ar || ''}</div>` : ''}
      <div class="modal-price-row">
        <div class="modal-price">${CURRENCY} ${item.price.toFixed(3)}</div>
        <div class="modal-price-label">Kuwaiti Dinar · دينار كويتي</div>
      </div>
      ${item.desc ? `<div class="modal-divider"></div>
      <div class="modal-desc-title">About this dish</div>
      <div class="modal-desc">${item.desc}</div>
      <div class="modal-desc-ar">${translateDescAr(item.id, item.desc)}</div>` : ''}
      <div class="modal-actions">
        ${ENABLE_WHATSAPP_ORDER
          ? `<button class="modal-add-btn" onclick="orderItemViaWhatsApp('${item.id}','${category}')">
               💬 Order via WhatsApp — ${CURRENCY} ${item.price.toFixed(3)}
             </button>`
          : `<button class="modal-add-btn" onclick="addToCart('${item.id}','${category}');showToast('${item.name} added to cart')">
               + Add to Order — ${CURRENCY} ${item.price.toFixed(3)}
             </button>`}
      </div>
    </div>`;

  document.getElementById('modalOverlay').classList.add('open');
  document.getElementById('modalBox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  document.getElementById('modalOverlay').classList.remove('open');
  document.getElementById('modalBox').classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

// Desc placeholder translations (for items without Arabic desc, show note)
function translateDescAr(id, desc) {
  // Items that have Arabic descriptions hardcoded can be added here
  // For now show a note pointing to back office
  return desc ? `<span style="color:var(--gold-dim);font-size:.75rem">الوصف بالعربية: أضفه من لوحة التحكم ← Back Office</span>` : '';
}

/* ════════════════════════════════════════
   MAKE CARDS CLICKABLE (open modal)
   — Override renderCard to attach click
════════════════════════════════════════ */
// Patch: delegate click on item cards
document.addEventListener('click', function(e) {
  if (!ENABLE_POPUPS) return;
  const card = e.target.closest('.item-card');
  if (!card) return;
  // Don't open modal if clicking the add button
  if (e.target.closest('.add-btn')) return;
  const id = card.dataset.id;
  const cat = card.dataset.cat;
  if (id && cat) openModal(id, cat);
});

/* ════════════════════════════════════════
   INIT
════════════════════════════════════════ */
renderAllMenus();
renderCart();
updateCartCount();
