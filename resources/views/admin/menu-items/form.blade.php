@extends('layouts.admin')

@section('title', $item->exists ? 'Edit Item' : 'Add New Item')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>{{ $item->exists ? 'Edit Item' : 'Add New Item' }}</h1>
    <p>{{ $item->exists ? 'Update details, pricing, or image for this item' : 'Create a new menu item — it will appear on the public menu immediately' }}</p>
  </div>
</div>

@if ($errors->any())
  <div class="login-err" style="display:block;margin-bottom:1rem">
    <ul style="list-style:none">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div style="max-width:680px">
  <div style="background:var(--dark2);border:1px solid var(--border);padding:1.5rem">
    <form method="POST" action="{{ $item->exists ? route('admin.menu-items.update', $item) : route('admin.menu-items.store') }}" enctype="multipart/form-data">
      @csrf
      @if($item->exists) @method('PUT') @endif

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Item ID *</label>
          <input class="form-input" name="item_key" value="{{ old('item_key', $item->item_key) }}" placeholder="e.g. custom1" style="font-family:'JetBrains Mono',monospace" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Category *</label>
          <select class="form-select" name="category_id" required>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->emoji }} {{ $cat->label }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group full">
          <label class="form-label">Name (English) *</label>
          <input class="form-input" name="name" value="{{ old('name', $item->name) }}" placeholder="e.g. Grilled Sea Bass" required/>
        </div>
        <div class="form-group full">
          <label class="form-label">Name (Arabic)</label>
          <input class="form-input" name="name_ar" value="{{ old('name_ar', $item->name_ar) }}" placeholder="الاسم بالعربي" style="direction:rtl;font-family:'Noto Naskh Arabic',serif"/>
        </div>
        <div class="form-group">
          <label class="form-label">Price (KWD) *</label>
          <input class="form-input" name="price" type="number" step="0.001" min="0" value="{{ old('price', $item->price) }}" placeholder="0.000" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Badge</label>
          <select class="form-select" name="badge">
            <option value="">None</option>
            @foreach(['New', 'Premium', "Chef's Pick", 'Seasonal'] as $badge)
            <option value="{{ $badge }}" @selected(old('badge', $item->badge) === $badge)>{{ $badge }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Emoji Icon</label>
          <input class="form-input" name="emoji" value="{{ old('emoji', $item->emoji) }}" placeholder="🍽" maxlength="4"/>
        </div>
        <div class="form-group full">
          <label class="form-label">Description</label>
          <textarea class="form-input" name="description" rows="3" placeholder="Ingredients, preparation, flavour notes…">{{ old('description', $item->description) }}</textarea>
        </div>
        <div class="form-group full">
          <label class="form-label">Image Upload</label>
          <div class="img-upload-zone">
            <input type="file" name="image" accept="image/*"/>
            <div class="upload-icon">📷</div>
            <p>Click to upload image</p>
            <small>JPG, PNG, WebP — max 4MB</small>
          </div>
          @if($item->imageUrl())
            <img class="img-preview show" src="{{ $item->imageUrl() }}" alt="{{ $item->name }}"/>
            <label style="display:flex;align-items:center;gap:.5rem;margin-top:.6rem;font-size:.8rem;color:var(--cream-dim)">
              <input type="checkbox" name="remove_image" value="1"/> Remove current image
            </label>
          @endif
        </div>
      </div>

      <div class="form-actions">
        <button class="form-btn save" type="submit">✓ Save Item</button>
        <a class="form-btn cancel" href="{{ route('admin.menu-items.index') }}">↺ Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
