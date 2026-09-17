<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ $settings->restaurant_name }} — Menu</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Cinzel:wght@400;600;700&family=Noto+Naskh+Arabic:wght@400;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('css/site.css') }}"/>
</head>
<body>

<!-- ══════════ LANGUAGE BAR ══════════ -->
<div class="lang-bar">
  <span class="lang-bar-left">✦ {{ $settings->restaurant_name }} · {{ $settings->location }} ✦</span>
  <div class="lang-toggle-wrap">
    <span class="lang-label active-lang" id="lblEn">EN</span>
    <label class="lang-switch" title="Toggle Arabic / English">
      <input type="checkbox" id="langToggle" onchange="toggleLanguage()"/>
      <span class="lang-slider"></span>
    </label>
    <span class="lang-label" id="lblAr" style="font-family:'Noto Naskh Arabic',serif">عربي</span>
  </div>
</div>

<!-- ══════════ HEADER ══════════ -->
<header class="site-header">
  <div class="header-top">
    <a class="logo" href="{{ url('/') }}">{{ $settings->restaurant_name }} <small>Restaurant & Café · Kuwait</small></a>

    <div class="search-wrap">
      <input type="text" id="searchInput" placeholder="Search menu items…" autocomplete="off"/>
      <span class="search-icon">🔍</span>
      <div class="search-results" id="searchResults"></div>
    </div>

    <div class="header-actions">
      <a href="34lounge_reservation.html" class="hdr-btn">🗓 Reserve</a>
      <a href="{{ route('admin.dashboard') }}" class="hdr-btn" title="Back Office">⚙️</a>
      <button class="hdr-btn cart-btn primary" onclick="toggleCart()">
        🛒 <span class="cart-label">Cart</span>
        <span class="cart-count" id="cartCount">0</span>
      </button>
    </div>
  </div>

  <!-- Category Nav -->
  <nav class="cat-nav">
    <div class="cat-nav-inner" id="catNav">
      @foreach($categories as $cat)
      <div class="cat-item{{ $loop->first ? ' active' : '' }}" data-target="{{ $cat->key }}">{{ $cat->emoji }} {{ $cat->label }}</div>
      @endforeach
    </div>
  </nav>
</header>

<!-- ══════════ HERO SWIPER ══════════ -->
<section class="hero-swiper" id="heroSwiper">
  <div class="swiper-track" id="swiperTrack">

    <div class="slide active">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[0] ?? asset('images/hero1.jpg') }}');background-color:#2a1a08"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Darah Mall · Kuwait ✦</div>
        <h1 class="slide-title">A Taste of Luxury,<br>Every Visit</h1>
        <p class="slide-sub">Where Lebanese heritage meets contemporary dining</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#breakfast">Explore Menu</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Reserve a Table</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[1] ?? asset('images/hero2.jpg') }}');background-color:#1a0e0e"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Signature Grills ✦</div>
        <h1 class="slide-title">Charcoal-Grilled<br>to Perfection</h1>
        <p class="slide-sub">Fresh cuts, aromatic spices, flames that tell a story</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#grills">View Grills</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Book Now</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[2] ?? asset('images/hero3.jpg') }}');background-color:#0e1a14"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Celebration Tables ✦</div>
        <h1 class="slide-title">Make Every Moment<br>Unforgettable</h1>
        <p class="slide-sub">Birthday packages, private rooms & premium décor from KWD 20</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#celebration">See Packages</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Reserve Now</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[3] ?? asset('images/hero4.jpg') }}');background-color:#0e0c1a"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Dessert Heaven ✦</div>
        <h1 class="slide-title">Sweets That<br>Steal the Show</h1>
        <p class="slide-sub">From molten lava cakes to San Sebastian cheesecake</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#sweets">View Sweets</a>
          <a class="slide-btn outline" href="#cold-coffee">Cold Coffee</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[4] ?? asset('images/hero5.jpg') }}');background-color:#1a1408"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ The {{ $settings->restaurant_name }} Experience ✦</div>
        <h1 class="slide-title">An Ambience<br>Built for Moments</h1>
        <p class="slide-sub">Warm interiors, attentive service, unforgettable evenings</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#breakfast">Explore Menu</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Reserve a Table</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[5] ?? asset('images/hero6.jpg') }}');background-color:#141a0e"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Fresh Every Day ✦</div>
        <h1 class="slide-title">Crafted with Care,<br>Served with Pride</h1>
        <p class="slide-sub">Every dish plated fresh, from our kitchen to your table</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#mains">View Mains</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Book Now</a>
        </div>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('{{ $heroImages[6] ?? asset('images/hero7.jpg') }}');background-color:#0e1418"></div>
      <div class="slide-content">
        <div class="slide-badge">✦ Visit Us ✦</div>
        <h1 class="slide-title">{{ $settings->location }}</h1>
        <p class="slide-sub">Come see what everyone's talking about</p>
        <div class="slide-cta">
          <a class="slide-btn primary" href="#breakfast">Explore Menu</a>
          <a class="slide-btn outline" href="34lounge_reservation.html">Reserve a Table</a>
        </div>
      </div>
    </div>

  </div>

  <button class="swiper-prev" onclick="swiperMove(-1)">‹</button>
  <button class="swiper-next" onclick="swiperMove(1)">›</button>
  <div class="swiper-dots" id="swiperDots"></div>
</section>

<!-- ══════════ ITEM DETAIL MODAL ══════════ -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>
<div class="modal-box" id="modalBox">
  <button class="modal-close" onclick="closeModal()">✕</button>
  <div id="modalInner"></div>
</div>

<!-- ══════════ MENU SECTIONS ══════════ -->
@foreach($categories as $cat)
<section class="section" id="{{ $cat->key }}" data-category="{{ $cat->key }}">
  <div class="section-header">
    <div class="sec-line"></div>
    <div><div class="sec-title">{{ $cat->title }}</div><div class="sec-title-ar">{{ $cat->title_ar }}</div></div>
    <div class="sec-line r"></div>
  </div>
  <div class="item-grid" id="grid-{{ $cat->key }}"></div>
</section>
@unless($loop->last)
<div class="sec-divider"><div class="sec-div-line"></div><div class="sec-div-diamond"></div><div class="sec-div-line"></div></div>
@endunless
@endforeach

<!-- ══════════ FOOTER ══════════ -->
<footer class="site-footer">

  <!-- Meat Source Banner -->
  <div class="footer-meat">
    <div class="footer-meat-inner">
      <h3>Our Commitment to Quality</h3>
      <div class="meat-badges">
        <div class="meat-badge"><span class="icon">🥩</span> 100% Halal Certified Meat</div>
        <div class="meat-badge"><span class="icon">🌿</span> Fresh Ingredients Daily</div>
        <div class="meat-badge"><span class="icon">🐄</span> Premium Brazilian & Australian Beef</div>
        <div class="meat-badge"><span class="icon">🐔</span> Fresh Local Poultry</div>
        <div class="meat-badge"><span class="icon">🐟</span> Sustainably Sourced Seafood</div>
        <div class="meat-badge"><span class="icon">🫒</span> Extra Virgin Lebanese Olive Oil</div>
        <div class="meat-badge"><span class="icon">🧀</span> Imported European Dairy</div>
        <div class="meat-badge"><span class="icon">🌾</span> No Artificial Preservatives</div>
      </div>
    </div>
  </div>

  <!-- Footer Main -->
  <div class="footer-main">
    <div class="footer-brand">
      <a class="logo" href="{{ url('/') }}">{{ $settings->restaurant_name }}</a>
      <p>An elevated dining experience rooted in Lebanese warmth, at the heart of {{ $settings->location }}. From morning breakfast to late-night sweets, every dish is crafted with love and premium ingredients.</p>
      <div style="margin-top:1.2rem;display:flex;gap:.6rem">
        <a class="social-link" href="#" title="Instagram">📸</a>
        <a class="social-link" href="#" title="TikTok">🎵</a>
        <a class="social-link" href="https://wa.me/{{ $waNumberDigits }}" title="WhatsApp">💬</a>
        <a class="social-link" href="#" title="Snapchat">👻</a>
      </div>
    </div>

    <div class="footer-col">
      <h4>Contact</h4>
      <ul>
        <li><span class="icon">📞</span><a href="tel:+{{ $waNumberDigits }}">{{ $settings->whatsapp_number }}</a></li>
        <li><span class="icon">💬</span><a href="https://wa.me/{{ $waNumberDigits }}">WhatsApp Us</a></li>
        <li><span class="icon">✉️</span><a href="mailto:info@34lounge.kw">info@34lounge.kw</a></li>
        <li><span class="icon">📸</span><a href="#">@34Lounge.kw</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Opening Hours</h4>
      <ul>
        <li><span class="icon">🌅</span>Breakfast: 7:00 AM – 12:00 PM</li>
        <li><span class="icon">☀️</span>Lunch: 12:00 PM – 4:00 PM</li>
        <li><span class="icon">🌙</span>Dinner: 4:00 PM – 12:00 AM</li>
        <li><span class="icon">🎉</span>Fri–Sat: Until 1:00 AM</li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Location</h4>
      <ul>
        <li><span class="icon">📍</span>{{ $settings->location }}</li>
        <li><span class="icon">🏢</span>Ground Floor, Main Wing</li>
        <li><span class="icon">🚗</span>Ample parking available</li>
      </ul>
      <div class="map-wrap" style="margin-top:.8rem"></div>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="footer-copy">© {{ date('Y') }} {{ $settings->restaurant_name }} Restaurant & Café. All rights reserved. · {{ $settings->location }}</div>
    <div style="font-size:.72rem;color:var(--gold-dim)">
      <a href="34lounge_reservation.html" style="color:var(--gold-dim);text-decoration:none;margin-right:1rem">Reserve a Table</a>
      <a href="{{ route('admin.dashboard') }}" style="color:var(--gold-dim);text-decoration:none">Back Office</a>
    </div>
  </div>
</footer>

<!-- ══════════ CART DRAWER ══════════ -->
<div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
<div class="cart-drawer" id="cartDrawer">
  <div class="cart-header">
    <div class="cart-title">Your Order 🛒</div>
    <button class="cart-close" onclick="toggleCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <div class="cart-empty">Your cart is empty.<br><small style="opacity:.6">Add items from the menu.</small></div>
  </div>
  <div class="cart-footer">
    <div class="cart-subtotal"><span class="label">Subtotal</span><span class="val" id="cartSubtotal">{{ $settings->currency }} 0.000</span></div>
    <div class="cart-subtotal"><span class="label">Service</span><span class="val">{{ $settings->currency }} 0.000</span></div>
    <div class="cart-note">Prices exclude VAT. Subject to 5% service charge.</div>
    @if($settings->enable_whatsapp_order)
    <button class="checkout-btn" onclick="checkout()">Place Order via WhatsApp →</button>
    @endif
    <button class="reserve-btn" onclick="window.location='34lounge_reservation.html'">🗓 Reserve a Table Instead</button>
  </div>
</div>

@if($settings->enable_whatsapp_order)
<!-- ══════════ WHATSAPP BUBBLE ══════════ -->
<div class="wa-bubble">
  <div class="wa-chat-box" id="waChatBox">
    <div class="wa-chat-header">
      <div class="wa-avatar">🍽</div>
      <div>
        <div class="wa-name">{{ $settings->restaurant_name }}</div>
        <div class="wa-status">Typically replies in minutes</div>
      </div>
    </div>
    <div class="wa-messages" id="waMessages">
      <div class="wa-msg">
        👋 Welcome to {{ $settings->restaurant_name }}! How can we help you today?
        <div class="wa-msg-time">Now</div>
      </div>
      <div class="wa-msg">
        You can ask about reservations, menu items, or send your order directly.
        <div class="wa-msg-time">Now</div>
      </div>
    </div>
    <div class="wa-input-row">
      <input class="wa-input" id="waInput" placeholder="Type a message…" onkeydown="waKeydown(event)"/>
      <button class="wa-send" onclick="waSend()">➤</button>
    </div>
  </div>
  <button class="wa-fab" onclick="toggleWA()" title="Chat on WhatsApp">
    <div class="wa-fab-pulse"></div>
    💬
  </button>
</div>
@endif

<!-- Toast -->
<div class="toast" id="toast"></div>

<script>
const MENU = @json($menuForJs);
const CATEGORY_LABELS = @json($categoryLabels);
const WA_NUMBER = @json($waNumberDigits);
const CURRENCY = @json($settings->currency);
const RESTAURANT_NAME = @json($settings->restaurant_name);
const SHOW_ARABIC = @json((bool) $settings->show_arabic);
const SHOW_PLACEHOLDER = @json((bool) $settings->show_placeholder);
const ENABLE_POPUPS = @json((bool) $settings->enable_popups);
const ENABLE_WHATSAPP_ORDER = @json((bool) $settings->enable_whatsapp_order);
</script>
<script src="{{ asset('js/site.js') }}"></script>
</body>
</html>
