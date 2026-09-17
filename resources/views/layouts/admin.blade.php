<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>@yield('title', 'Dashboard') — 34 Lounge Back Office</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:wght@400;600&family=JetBrains+Mono&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
</head>
<body>

<div class="bo-layout" id="boLayout">

<!-- ══════════ SIDEBAR ══════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo">
    <div class="logo-main">34 Lounge</div>
    <div class="logo-sub">Back Office</div>
    <div class="logo-badge">⚙️ Admin Panel</div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a class="nav-item{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}" style="text-decoration:none">
      <span class="nav-icon">📊</span> Dashboard
    </a>
    <a class="nav-item{{ request()->routeIs('admin.menu-items.*') ? ' active' : '' }}" href="{{ route('admin.menu-items.index') }}" style="text-decoration:none">
      <span class="nav-icon">🍽</span> Menu Items
    </a>
    <a class="nav-item{{ request()->routeIs('admin.hero-slides.*') ? ' active' : '' }}" href="{{ route('admin.hero-slides.index') }}" style="text-decoration:none">
      <span class="nav-icon">🖼</span> Hero Slider
    </a>
    <a class="nav-item{{ request()->routeIs('admin.categories.*') ? ' active' : '' }}" href="{{ route('admin.categories.index') }}" style="text-decoration:none">
      <span class="nav-icon">📂</span> Categories
    </a>

    <div class="nav-section-label" style="margin-top:.8rem">Tools</div>
    <a class="nav-item{{ request()->routeIs('admin.menu-items.create') ? ' active' : '' }}" href="{{ route('admin.menu-items.create') }}" style="text-decoration:none">
      <span class="nav-icon">➕</span> Add New Item
    </a>
    <a class="nav-item{{ request()->routeIs('admin.settings.*') ? ' active' : '' }}" href="{{ route('admin.settings.edit') }}" style="text-decoration:none">
      <span class="nav-icon">⚙️</span> Settings
    </a>
    <a class="nav-item{{ request()->routeIs('admin.qr-code.*') ? ' active' : '' }}" href="{{ route('admin.qr-code.index') }}" style="text-decoration:none">
      <span class="nav-icon">🔲</span> QR Code
    </a>
    <a class="nav-item{{ request()->routeIs('admin.profile.*') ? ' active' : '' }}" href="{{ route('admin.profile.edit') }}" style="text-decoration:none">
      <span class="nav-icon">👤</span> My Account
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="{{ route('menu.index') }}">← Back to Menu</a>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:.5rem">
      @csrf
      <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;display:flex;align-items:center;gap:.6rem;color:var(--gold-dim);font-family:'Cinzel',serif;font-size:.58rem;letter-spacing:.08em">🔒 Log Out</button>
    </form>
  </div>
</aside>

<div class="overlay-mob" id="mobOverlay"></div>

<!-- ══════════ MAIN ══════════ -->
<main class="bo-main">

  <div class="topbar">
    <div style="display:flex;align-items:center;gap:.8rem">
      <button class="ham-toggle" id="hamToggle"><span></span><span></span><span></span></button>
      <div class="topbar-title">@yield('title', 'Dashboard')</div>
    </div>
    <div class="topbar-right">
      <a href="{{ route('menu.index') }}" class="topbar-btn">👁 View Menu</a>
    </div>
  </div>

  <div class="content">
    @if (session('status'))
      <div class="badge-pill new" style="margin-bottom:1.2rem;display:inline-flex">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="login-err" style="display:block;margin-bottom:1.2rem">{{ session('error') }}</div>
    @endif

    @yield('content')
  </div>

</main>

</div>

<script>
document.getElementById('hamToggle')?.addEventListener('click', () => {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('mobOverlay').classList.toggle('open');
});
document.getElementById('mobOverlay')?.addEventListener('click', () => {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('mobOverlay').classList.remove('open');
});
</script>
</body>
</html>
