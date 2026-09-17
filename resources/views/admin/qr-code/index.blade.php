@extends('layouts.admin')

@section('title', 'QR Code')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Menu QR Code</h1>
    <p>Scan with a phone camera to open the public menu. Print it on table tents, flyers, or posters.</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.5rem">

  <div style="background:var(--dark2);border:1px solid var(--border);padding:2rem;text-align:center">
    <div class="form-label" style="margin-bottom:1rem">Printable Card — Big Logo</div>
    <img src="{{ $cardDataUri }}" alt="Menu card with logo" style="width:100%;max-width:320px;background:#fff;margin:0 auto;display:block;border:1px solid var(--border)"/>
    <p style="font-size:.78rem;color:var(--cream-dim);margin-top:1rem;font-style:italic">Big logo banner above a clean, fully scannable QR code — best for posters and table tents.</p>
    <div class="form-actions" style="justify-content:center">
      <a class="form-btn save" href="{{ route('admin.qr-code.card-download') }}">⬇ Download Card PNG</a>
    </div>
  </div>

  <div style="background:var(--dark2);border:1px solid var(--border);padding:2rem;text-align:center">
    <div class="form-label" style="margin-bottom:1rem">Compact QR — Small Embedded Logo</div>
    <img src="{{ $dataUri }}" alt="Menu QR code" style="width:100%;max-width:320px;background:#fff;padding:1rem;margin:0 auto;display:block"/>
    <p style="font-family:'JetBrains Mono',monospace;font-size:.75rem;color:var(--gold-dim);margin-top:1.2rem;word-break:break-all">{{ $menuUrl }}</p>
    <div class="form-actions" style="justify-content:center">
      <a class="form-btn save" href="{{ route('admin.qr-code.download') }}">⬇ Download PNG</a>
    </div>
  </div>

</div>
@endsection
