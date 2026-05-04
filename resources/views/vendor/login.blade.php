<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Vendor - Kantin Online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #2563eb 100%);
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            padding: 40px 32px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-card h2 { font-size: 22px; font-weight: 700; color: #0f172a; }
        .login-card p { font-size: 14px; color: #64748b; }
        .form-control:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .btn-login {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            border: none; color: #fff; font-weight: 600;
        }
        .btn-login:hover { box-shadow: 0 4px 14px rgba(37,99,235,0.35); color: #fff; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-shop" style="font-size:40px; color:#2563eb;"></i>
            <h2 class="mt-2">Login Vendor</h2>
            <p>Masuk ke panel vendor kantin online</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first() }}
            </div>
        @endif

        <form id="formLogin" method="POST" action="{{ route('vendor.login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13.5px;">Email</label>
                <input type="email" name="email" class="form-control" placeholder="vendor@email.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:13.5px;">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13.5px;">Ingat saya</label>
            </div>
        </form>

        <button type="button" id="btnLogin" class="btn btn-login w-100 py-2">Masuk</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
    $(function(){
        $('#btnLogin').on('click', function(){
            var form = document.getElementById('formLogin');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');
            setTimeout(function(){ form.submit(); }, 400);
        });
    });
    </script>
</body>
</html>
