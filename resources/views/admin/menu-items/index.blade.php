@extends('layouts.admin')

@section('title', 'Menu Items')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Menu Items</h1>
    <p>Edit, update pricing, toggle badges, and upload images for every item</p>
  </div>
  <a class="topbar-btn primary" href="{{ route('admin.menu-items.create') }}">+ Add Item</a>
</div>

<form method="GET" action="{{ route('admin.menu-items.index') }}" class="search-row">
  <input class="bo-search" type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, Arabic, description…"/>
  <button class="topbar-btn" type="submit">Search</button>
</form>

<div class="cat-filter-bar">
  <a class="cat-filter-btn{{ request('category') ? '' : ' active' }}" href="{{ route('admin.menu-items.index', ['q' => request('q')]) }}">All</a>
  @foreach($categories as $cat)
  <a class="cat-filter-btn{{ request('category') == $cat->id ? ' active' : '' }}" href="{{ route('admin.menu-items.index', ['q' => request('q'), 'category' => $cat->id]) }}">{{ $cat->emoji }} {{ $cat->label }}</a>
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
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $item)
      <tr>
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
        <td style="display:flex;gap:.4rem">
          <a class="act-btn" href="{{ route('admin.menu-items.edit', $item) }}">✎ Edit</a>
          <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}" onsubmit="return confirm('Delete {{ addslashes($item->name) }}?')">
            @csrf @method('DELETE')
            <button class="act-btn danger" type="submit">🗑 Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="7"><div class="empty-state"><div class="empty-icon">🍽</div><p>No items found</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div style="margin-top:1.5rem">{{ $items->links() }}</div>
@endsection
