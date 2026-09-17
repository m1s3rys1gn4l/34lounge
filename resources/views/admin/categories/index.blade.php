@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Menu Categories</h1>
    <p>Add, edit, reorder, or remove categories shown in the nav bar and menu page</p>
  </div>
  <a class="topbar-btn primary" href="{{ route('admin.categories.create') }}">+ Add Category</a>
</div>

<div class="menu-table-wrap">
  <table class="menu-table">
    <thead>
      <tr>
        <th>Order</th>
        <th>Nav Label</th>
        <th>Section Title</th>
        <th>Key</th>
        <th>Items</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $cat)
      <tr>
        <td style="display:flex;gap:.3rem">
          <form method="POST" action="{{ route('admin.categories.move-up', $cat) }}">
            @csrf
            <button class="act-btn" type="submit" @disabled($loop->first)>↑</button>
          </form>
          <form method="POST" action="{{ route('admin.categories.move-down', $cat) }}">
            @csrf
            <button class="act-btn" type="submit" @disabled($loop->last)>↓</button>
          </form>
        </td>
        <td>{{ $cat->emoji }} {{ $cat->label }}</td>
        <td>{{ $cat->title }}<br><span style="direction:rtl;font-size:.8rem;opacity:.7">{{ $cat->title_ar }}</span></td>
        <td style="font-family:'JetBrains Mono',monospace;font-size:.78rem">{{ $cat->key }}</td>
        <td><span class="badge-pill premium">{{ $cat->menu_items_count }}</span></td>
        <td style="display:flex;gap:.4rem">
          <a class="act-btn" href="{{ route('admin.categories.edit', $cat) }}">✎ Edit</a>
          <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete {{ addslashes($cat->label) }}?')">
            @csrf @method('DELETE')
            <button class="act-btn danger" type="submit">🗑 Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6"><div class="empty-state"><div class="empty-icon">📂</div><p>No categories yet</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
