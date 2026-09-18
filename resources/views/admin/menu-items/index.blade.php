@extends('layouts.admin')

@section('title', 'Menu Items')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Menu Items</h1>
    <p>Edit, update pricing, toggle badges, enable/disable, and upload images for every item</p>
  </div>
  <a class="topbar-btn primary" href="{{ route('admin.menu-items.create') }}">+ Add Item</a>
</div>

<form method="GET" action="{{ route('admin.menu-items.index') }}" class="search-row">
  <input class="bo-search" type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, Arabic, description…"/>
  @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}"/>@endif
  @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}"/>@endif
  <button class="topbar-btn" type="submit">Search</button>
</form>

<div class="cat-filter-bar">
  <a class="cat-filter-btn{{ request('status') ? '' : ' active' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'category' => request('category')]) }}">All Status</a>
  <a class="cat-filter-btn{{ request('status') === 'enabled' ? ' active' : '' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'category' => request('category'), 'status' => 'enabled']) }}">✓ Enabled</a>
  <a class="cat-filter-btn{{ request('status') === 'disabled' ? ' active' : '' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'category' => request('category'), 'status' => 'disabled']) }}">✕ Disabled</a>
</div>

<div class="cat-filter-bar">
  <a class="cat-filter-btn{{ request('category') ? '' : ' active' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'status' => request('status')]) }}">All Categories</a>
  @foreach($categories as $cat)
  <a class="cat-filter-btn{{ request('category') == $cat->id ? ' active' : '' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'status' => request('status'), 'category' => $cat->id]) }}">{{ $cat->emoji }} {{ $cat->label }}</a>
  @endforeach
</div>

<div class="menu-table-wrap">
  <table class="menu-table">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Arabic</th>
        <th>Category</th>
        <th>Price</th>
        <th>Badge</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $item)
      <tr style="{{ $item->is_active ? '' : 'opacity:.55' }}">
        <td>
          @if($item->imageUrl())
            <img class="item-thumb" src="{{ $item->imageUrl() }}" alt="{{ $item->name }}"/>
          @else
            <div class="item-thumb-ph">{{ $item->emoji }}</div>
          @endif
        </td>
        <td>{{ $item->name }}</td>
        <td style="direction:rtl">{{ $item->name_ar }}</td>
        <td>{{ $item->category->label ?? '—' }}</td>
        <td>{{ number_format($item->price, 3) }}</td>
        <td>@if($item->badge)<span class="badge-pill premium">{{ $item->badge }}</span>@endif</td>
        <td>
          @if($item->is_active)
            <span class="badge-pill new">Enabled</span>
          @else
            <span class="badge-pill disabled">Disabled</span>
          @endif
        </td>
        <td style="display:flex;gap:.4rem">
          <a class="act-btn" href="{{ route('admin.menu-items.edit', $item) }}">✎ Edit</a>
          <form method="POST" action="{{ route('admin.menu-items.toggle-active', $item) }}">
            @csrf
            @if($item->is_active)
              <button class="act-btn" type="submit">⏸ Disable</button>
            @else
              <button class="act-btn success" type="submit">▶ Enable</button>
            @endif
          </form>
          <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
            @csrf @method('DELETE')
            <button class="act-btn danger" type="submit">🗑 Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="8"><div class="empty-state"><div class="empty-icon">🍽</div><p>No items found</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:1.5rem">{{ $items->links() }}</div>
@endsection
