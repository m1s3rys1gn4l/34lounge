@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Dashboard Overview</h1>
    <p>Real-time snapshot of your menu content</p>
  </div>
</div>

<div class="stats-grid">
  <div class="stat-card gold">
    <div class="stat-label">Total Items</div>
    <div class="stat-value">{{ $totalItems }}</div>
    <div class="stat-icon">🍽</div>
  </div>
  <div class="stat-card blue">
    <div class="stat-label">Categories</div>
    <div class="stat-value">{{ $totalCategories }}</div>
    <div class="stat-icon">📂</div>
  </div>
  <div class="stat-card red">
    <div class="stat-label">Missing Images</div>
    <div class="stat-value">{{ $missingImages->count() }}</div>
    <div class="stat-icon">📸</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);margin-bottom:2rem">
  @foreach($categoryBreakdown as $cat)
  <div style="background:var(--dark2);padding:.9rem 1.2rem;display:flex;justify-content:space-between;align-items:center">
    <span style="font-size:.88rem;color:var(--cream-dim)">{{ $cat->emoji }} {{ $cat->label }}</span>
    <span class="badge-pill premium">{{ $cat->menu_items_count }}</span>
  </div>
  @endforeach
</div>

<div style="margin-bottom:.8rem;display:flex;align-items:center;justify-content:space-between">
  <span style="font-family:'Cinzel',serif;font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gold-dim)">Items Missing Images</span>
  <a class="act-btn" href="{{ route('admin.menu-items.index') }}">Manage All →</a>
</div>

@if($missingImages->isEmpty())
<div class="empty-state"><div class="empty-icon">✅</div><p>Every item has an image</p></div>
@else
<div class="menu-table-wrap">
  <table class="menu-table">
    <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr></thead>
    <tbody>
      @foreach($missingImages as $item)
      <tr>
        <td>{{ $item->emoji }} {{ $item->name }}</td>
        <td>{{ $item->category->label ?? '—' }}</td>
        <td>{{ $item->price }}</td>
        <td><a class="act-btn" href="{{ route('admin.menu-items.edit', $item) }}">✎ Edit</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
@endsection
