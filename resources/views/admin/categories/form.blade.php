@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'Add Category')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>{{ $category->exists ? 'Edit Category' : 'Add Category' }}</h1>
    <p>{{ $category->exists ? 'Update this category\'s nav label and section heading' : 'Create a new menu category — it appears in the nav bar and as its own section' }}</p>
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
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
      @csrf
      @if($category->exists) @method('PUT') @endif

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Key (URL-safe, unique) *</label>
          <input class="form-input" name="key" value="{{ old('key', $category->key) }}" placeholder="e.g. mezza" style="font-family:'JetBrains Mono',monospace" required/>
          <small style="font-size:.68rem;color:var(--gold-dim);margin-top:.2rem;font-style:italic">Letters, numbers, dashes/underscores only. Used in the URL anchor.</small>
        </div>
        <div class="form-group">
          <label class="form-label">Emoji</label>
          <input class="form-input" name="emoji" value="{{ old('emoji', $category->emoji) }}" placeholder="🍽" maxlength="10"/>
        </div>
        <div class="form-group full">
          <label class="form-label">Nav Label *</label>
          <input class="form-input" name="label" value="{{ old('label', $category->label) }}" placeholder="e.g. Mezza" required/>
          <small style="font-size:.68rem;color:var(--gold-dim);margin-top:.2rem;font-style:italic">Shown in the top category nav bar (with the emoji).</small>
        </div>
        <div class="form-group full">
          <label class="form-label">Section Title (English) *</label>
          <input class="form-input" name="title" value="{{ old('title', $category->title) }}" placeholder="e.g. Mezza — Cold & Hot" required/>
        </div>
        <div class="form-group full">
          <label class="form-label">Section Title (Arabic)</label>
          <input class="form-input" name="title_ar" value="{{ old('title_ar', $category->title_ar) }}" placeholder="مقبلات باردة وساخنة" style="direction:rtl;font-family:'Noto Naskh Arabic',serif"/>
        </div>
      </div>

      <div class="form-actions">
        <button class="form-btn save" type="submit">✓ Save Category</button>
        <a class="form-btn cancel" href="{{ route('admin.categories.index') }}">↺ Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
