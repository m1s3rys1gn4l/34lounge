@extends('layouts.admin')

@section('title', 'My Account')

@section('content')
<div class="page-header">
  <div class="page-header-left">
    <h1>My Account</h1>
    <p>Change the email and password used to log into the back office</p>
  </div>
</div>

<div style="max-width:520px;display:flex;flex-direction:column;gap:1.5rem">

  <div class="settings-section">
    <div class="settings-section-header"><h3>✉️ Login Email</h3></div>
    <div class="settings-section-body" style="padding:1.4rem">
      @if ($errors->updateEmail->any())
        <div class="login-err" style="display:block;margin-bottom:1rem">
          <ul style="list-style:none">
            @foreach ($errors->updateEmail->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('admin.profile.update-email') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Name</label>
            <input class="form-input" name="name" value="{{ old('name', $user->name) }}" required/>
          </div>
          <div class="form-group full">
            <label class="form-label">Email</label>
            <input class="form-input" type="email" name="email" value="{{ old('email', $user->email) }}" required/>
          </div>
        </div>
        <div class="form-actions">
          <button class="form-btn save" type="submit">✓ Save Email</button>
        </div>
      </form>
    </div>
  </div>

  <div class="settings-section">
    <div class="settings-section-header"><h3>🔒 Password</h3></div>
    <div class="settings-section-body" style="padding:1.4rem">
      @if ($errors->updatePassword->any())
        <div class="login-err" style="display:block;margin-bottom:1rem">
          <ul style="list-style:none">
            @foreach ($errors->updatePassword->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="{{ route('admin.profile.update-password') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Current Password</label>
            <input class="form-input" type="password" name="current_password" autocomplete="current-password" required/>
          </div>
          <div class="form-group full">
            <label class="form-label">New Password</label>
            <input class="form-input" type="password" name="password" autocomplete="new-password" required/>
          </div>
          <div class="form-group full">
            <label class="form-label">Confirm New Password</label>
            <input class="form-input" type="password" name="password_confirmation" autocomplete="new-password" required/>
          </div>
        </div>
        <div class="form-actions">
          <button class="form-btn save" type="submit">✓ Change Password</button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
