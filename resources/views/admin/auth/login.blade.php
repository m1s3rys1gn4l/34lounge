<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Back Office Login — 34 Lounge</title>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"/>
<style>body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--dark)}</style>
</head>
<body>

<div class="login-box">
  <div class="logo-main">34 Lounge</div>
  <div class="logo-sub">Back Office Login</div>

  <form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <label class="form-label" for="email">Email</label>
    <input class="form-input" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="admin@example.com" autocomplete="username" required autofocus/>

    <label class="form-label" for="password" style="margin-top:1rem">Password</label>
    <input class="form-input" type="password" name="password" id="password" placeholder="Enter admin password" autocomplete="current-password" required/>

    @if ($errors->any())
      <div class="login-err" style="display:block">{{ $errors->first() }}</div>
    @endif

    <button class="login-submit" type="submit">Unlock Back Office</button>
  </form>
</div>

</body>
</html>
