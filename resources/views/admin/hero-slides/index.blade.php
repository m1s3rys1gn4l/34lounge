@extends('layouts.admin')

@section('title', 'Hero Slider')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Hero Slider Images</h1>
    <p>Replace the background photo for each homepage slide. Titles and text stay fixed — only the image changes.</p>
  </div>
</div>

@php
  $labels = [
    'Darah Mall · Kuwait — "A Taste of Luxury, Every Visit"',
    'Signature Grills — "Charcoal-Grilled to Perfection"',
    'Celebration Tables — "Make Every Moment Unforgettable"',
    'Dessert Heaven — "Sweets That Steal the Show"',
    'The 34 Lounge Experience — "An Ambience Built for Moments"',
    'Fresh Every Day — "Crafted with Care, Served with Pride"',
    'Visit Us — "Darah Mall, Kuwait City"',
  ];
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.2rem">
  @foreach($slides as $slide)
  <div style="background:var(--dark2);border:1px solid var(--border);padding:1rem">
    <div style="font-family:'Cinzel',serif;font-size:.58rem;letter-spacing:.08em;color:var(--gold-dim);text-transform:uppercase;margin-bottom:.6rem">
      Slide {{ $slide->sort_order + 1 }}
    </div>
    @if($slide->imageUrl())
      <img class="img-preview show" src="{{ $slide->imageUrl() }}" alt="Slide {{ $slide->sort_order + 1 }}" style="aspect-ratio:16/9"/>
    @endif
    <p style="font-size:.78rem;color:var(--cream-dim);margin:.7rem 0;font-style:italic">{{ $labels[$slide->sort_order] ?? '' }}</p>

    <form method="POST" action="{{ route('admin.hero-slides.update', $slide) }}" enctype="multipart/form-data">
      @csrf
      <div class="img-upload-zone" style="padding:1rem">
        <input type="file" name="image" accept="image/*" required/>
        <div class="upload-icon">📷</div>
        <p>Click to upload new image</p>
        <small>JPG, PNG, WebP — max 8MB</small>
      </div>
      <button class="form-btn save" type="submit" style="width:100%;margin-top:.7rem">✓ Replace Image</button>
    </form>
  </div>
  @endforeach
</div>
@endsection
