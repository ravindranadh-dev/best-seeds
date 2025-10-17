<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendors/iconfonts/font-awesome/css/all.min.css') }}">
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('admin_assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/vendors/css/vendor.bundle.addons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}">

    <style>

body {
    background-color: #e0f7fa; /* Light aqua blue */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.content-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Smaller, cleaner form */
.auth-form-light {
    background: #ffffff;
    padding: 2rem;
    border-radius: 0.75rem;
    box-shadow: none; /* no shadow */
    max-width: 380px; /* compact width */
    width: 90%;
    margin: auto;
}

.auth-form-light h4 {
    font-weight: 600;
    margin: 0.5rem 0 0.5rem;
    text-align: center;
}

.auth-form-light h6 {
    color: #6c757d;
    margin-bottom: 1.5rem;
    text-align: center;
}

/* Logo styling */
.brand-logo {
    text-align: center;
    margin-bottom: 1.5rem;
}

.brand-logo img {
    max-height: 65px; /* keeps logo size small */
    width: auto; /* prevents stretching */
    object-fit: contain; /* keeps proportions */
    display: inline-block;
}

/* Input styling */
.auth .form-control {
    height: 42px;
    border: 1px solid #e5e5e5;
    border-radius: 0.375rem;
}

/* Aqua blue button */
.auth .btn-primary {
    background: linear-gradient(90deg, #00c8d7, #00acc1);
    color: #fff;
    height: 45px;
    border: none;
    border-radius: 0.375rem;
    transition: 0.3s ease;
}

.auth .btn-primary:hover {
    background: linear-gradient(90deg, #00acc1, #0097a7);
    transform: scale(1.02);
}

/* Checkbox & links */
.form-check-label {
    color: #6c757d;
}

.auth-link {
    font-size: 0.85rem;
    color: #00acc1;
}

.auth-link:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .auth-form-light {
        max-width: 90%;
        padding: 1.5rem;
    }

    .brand-logo img {
        max-height: 55px;
    }
}


    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper auth">
                <div class="row w-100 justify-content-center">
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="auth-form-light text-left">
                            <div class="brand-logo">
                                <img src="{{ asset('uploads/logo_68e5f26e3952d.png') }}" alt="{{ config('app.name', 'Best Seeds') }}">
                            </div>
                            <h4>Welcome back!</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>

                            <form method="POST" action="{{ route('login') }}" class="pt-3">
                                @csrf

                                <div class="form-group">
                                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="current-password" placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Keep me signed in
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
                                    @endif
                                </div>

                              <div class="mt-3">
    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
        SIGN IN
    </button>
</div>

                                @if(Route::has('register'))
                                    <div class="text-center mt-4 font-weight-light">
                                        Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Plugins JS -->
    <script src="{{ asset('admin_assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('admin_assets/vendors/js/vendor.bundle.addons.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('admin_assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin_assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin_assets/js/misc.js') }}"></script>
    <script src="{{ asset('admin_assets/js/settings.js') }}"></script>
    <script src="{{ asset('admin_assets/js/todolist.js') }}"></script>
</body>
</html>
