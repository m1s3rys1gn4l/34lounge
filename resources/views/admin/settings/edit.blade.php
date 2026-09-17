@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>Settings</h1>
    <p>Configure restaurant details and menu options</p>
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

<form method="POST" action="{{ route('admin.settings.update') }}">
  @csrf
  @method('PUT')

  <div class="settings-section">
    <div class="settings-section-header"><h3>🏠 Restaurant Info</h3></div>
    <div class="settings-section-body" style="padding:1.4rem">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Restaurant Name</label>
          <input class="form-input" name="restaurant_name" value="{{ old('restaurant_name', $settings->restaurant_name) }}"/>
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Number</label>
          <input class="form-input" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}" style="font-family:'JetBrains Mono',monospace"/>
        </div>
        <div class="form-group">
          <label class="form-label">Location</label>
          <input class="form-input" name="location" value="{{ old('location', $settings->location) }}"/>
        </div>
        <div class="form-group">
          <label class="form-label">Currency</label>
          <input class="form-input" name="currency" value="{{ old('currency', $settings->currency) }}"/>
        </div>
      </div>
    </div>
  </div>

  <div class="settings-section">
    <div class="settings-section-header"><h3>📋 Menu Display</h3></div>
    <div class="settings-section-body" style="padding:1.4rem;display:flex;flex-direction:column;gap:1rem">
      <div class="settings-row" style="display:flex;align-items:center;justify-content:space-between">
        <div class="settings-row-label">Show Arabic names on cards</div>
        <label class="toggle-switch">
          <input type="checkbox" name="show_arabic" value="1" @checked(old('show_arabic', $settings->show_arabic))/>
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="settings-row" style="display:flex;align-items:center;justify-content:space-between">
        <div class="settings-row-label">Show "No Image" placeholder</div>
        <label class="toggle-switch">
          <input type="checkbox" name="show_placeholder" value="1" @checked(old('show_placeholder', $settings->show_placeholder))/>
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="settings-row" style="display:flex;align-items:center;justify-content:space-between">
        <div class="settings-row-label">Enable item detail popups</div>
        <label class="toggle-switch">
          <input type="checkbox" name="enable_popups" value="1" @checked(old('enable_popups', $settings->enable_popups))/>
          <span class="toggle-slider"></span>
        </label>
      </div>
      <div class="settings-row" style="display:flex;align-items:center;justify-content:space-between">
        <div class="settings-row-label">Enable WhatsApp ordering</div>
        <label class="toggle-switch">
          <input type="checkbox" name="enable_whatsapp_order" value="1" @checked(old('enable_whatsapp_order', $settings->enable_whatsapp_order))/>
          <span class="toggle-slider"></span>
        </label>
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button class="form-btn save" type="submit">✓ Save Settings</button>
  </div>
</form>
@endsection
