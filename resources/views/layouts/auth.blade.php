<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - Koleksi Buku</title>

    {{-- Google Font: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        /* ========================
           LEFT PANEL - Branding
           ======================== */
        .auth-left {
            display: none;
            width: 50%;
            min-height: 100vh;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 40%, #2563eb 100%);
            position: relative;
            overflow: hidden;
            padding: 48px;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -15%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(147, 197, 253, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .brand-top {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .brand-logo .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255, 0.1);
        }

        .brand-center {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .brand-center h2 {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .brand-center p {
            font-size: 16px;
            color: rgba(191, 219, 254, 0.8);
            line-height: 1.7;
            max-width: 380px;
        }

        .brand-features {
            margin-top: 36px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(219, 234, 254, 0.9);
            font-size: 14px;
            font-weight: 500;
        }

        .brand-feature .feat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255,255,255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255, 0.08);
        }

        .brand-bottom {
            position: relative;
            z-index: 1;
            color: rgba(148, 163, 184, 0.7);
            font-size: 13px;
        }

        /* ========================
           RIGHT PANEL - Form
           ======================== */
        .auth-right {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            background: #f8fafc;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        /* Header */
        .auth-header {
            text-align: center;
            margin-bottom: 36px;
        }

        .auth-header .mobile-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }

        .auth-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .auth-header p {
            font-size: 14.5px;
            color: #64748b;
            font-weight: 400;
        }

        /* Google Button */
        .btn-google {
            width: 100%;
            padding: 12px 20px;
            background: #fff;
            color: #1e293b;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-family: inherit;
            font-weight: 500;
            font-size: 14.5px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-google:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .btn-google:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-google svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 16px;
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            background: #fff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Password toggle */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-input {
            padding-right: 44px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            outline: none;
        }

        .password-toggle:hover {
            color: #475569;
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
        }

        .invalid-feedback {
            display: block;
            color: #ef4444;
            font-size: 12.5px;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .remember-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 5px;
            cursor: pointer;
            accent-color: #2563eb;
            transition: all 0.15s;
        }

        .remember-check span {
            font-size: 13.5px;
            color: #475569;
            user-select: none;
        }

        .forgot-link {
            font-size: 13.5px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1d4ed8;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: inherit;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        .btn-login:hover {
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
        }

        /* Footer */
        .auth-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 14px;
            color: #64748b;
        }

        .auth-footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .auth-footer a:hover {
            color: #1d4ed8;
        }

        /* ========================
           RESPONSIVE
           ======================== */
        @media (min-width: 1024px) {
            .auth-left {
                display: flex;
            }

            .auth-right {
                width: 50%;
                padding: 48px;
            }

            .auth-header .mobile-logo {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .auth-right {
                padding: 24px 20px;
            }

            .auth-header h1 {
                font-size: 22px;
            }
        }

        /* ========================
           ANIMATION
           ======================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL: Branding -->
    <aside class="auth-left">
        <div class="brand-top">
            <div class="brand-logo">
                <div class="logo-icon">&#128218;</div>
                Koleksi Buku
            </div>
        </div>

        <div class="brand-center">
            <h2>Kelola koleksi<br>buku dengan mudah.</h2>
            <p>Platform manajemen buku dan inventaris yang simpel, cepat, dan terorganisir.</p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="feat-icon">&#128202;</div>
                    Dashboard & laporan real-time
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">&#128196;</div>
                    Cetak label & tag harga otomatis
                </div>
                <div class="brand-feature">
                    <div class="feat-icon">&#128274;</div>
                    Login aman dengan OTP & Google
                </div>
            </div>
        </div>

        <div class="brand-bottom">
            &copy; {{ date('Y') }} Koleksi Buku. All rights reserved.
        </div>
    </aside>

    <!-- RIGHT PANEL: Form -->
    <main class="auth-right">
        @yield('content')
    </main>

    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.password-toggle').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var input = this.parentElement.querySelector('input');
                    var eyeOpen = this.querySelector('.eye-open');
                    var eyeClosed = this.querySelector('.eye-closed');

                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeOpen.style.display = 'none';
                        eyeClosed.style.display = 'block';
                    } else {
                        input.type = 'password';
                        eyeOpen.style.display = 'block';
                        eyeClosed.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
