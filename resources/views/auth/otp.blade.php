@extends('layouts.auth')

@section('content')
<div class="auth-card">

    <!-- Header -->
    <div class="auth-header">
        <div class="mobile-logo">&#128274;</div>
        <h1>Verifikasi OTP</h1>
        <p>Masukkan 6 digit kode yang dikirim ke email kamu</p>
    </div>

    <!-- Error -->
    @if(session('error'))
        <div class="otp-alert otp-alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="otp-alert otp-alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- OTP Form -->
    <form method="POST" action="/otp" id="otpForm">
        @csrf

        <div class="otp-input-group">
            @for($i = 0; $i < 6; $i++)
                <input
                    type="text"
                    class="otp-box"
                    maxlength="1"
                    inputmode="numeric"
                    pattern="[0-9]"
                    autocomplete="one-time-code"
                    required
                    data-index="{{ $i }}"
                >
            @endfor
        </div>

        <!-- Hidden input untuk kirim value OTP lengkap -->
        <input type="hidden" name="otp" id="otpHidden">

        <button type="submit" class="btn-login">Verifikasi</button>
    </form>

    <!-- Footer -->
    <div class="auth-footer">
        <a href="{{ url('/auth/google') }}" class="otp-resend-link">Kirim ulang kode OTP</a>
    </div>

</div>

<style>
    /* OTP Input Boxes */
    .otp-input-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-bottom: 28px;
    }

    .otp-box {
        width: 48px;
        height: 56px;
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        outline: none;
        transition: all 0.2s ease;
        caret-color: #2563eb;
    }

    .otp-box:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .otp-box.filled {
        border-color: #2563eb;
        background: #eff6ff;
    }

    /* Alert */
    .otp-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 24px;
    }

    .otp-alert svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .otp-alert-error {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .otp-alert-success {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    /* Resend link */
    .otp-resend-link {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    .otp-resend-link:hover {
        color: #1d4ed8;
    }

    @media (max-width: 480px) {
        .otp-box {
            width: 42px;
            height: 50px;
            font-size: 20px;
        }

        .otp-input-group {
            gap: 8px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.otp-box');
    const hidden = document.getElementById('otpHidden');
    const form = document.getElementById('otpForm');

    boxes.forEach(function(box, index) {
        box.addEventListener('input', function(e) {
            // Hanya angka
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value && index < boxes.length - 1) {
                boxes[index + 1].focus();
            }

            this.classList.toggle('filled', this.value !== '');
            updateHidden();
        });

        box.addEventListener('keydown', function(e) {
            // Backspace: pindah ke kotak sebelumnya
            if (e.key === 'Backspace' && !this.value && index > 0) {
                boxes[index - 1].focus();
                boxes[index - 1].value = '';
                boxes[index - 1].classList.remove('filled');
                updateHidden();
            }
        });

        // Handle paste
        box.addEventListener('paste', function(e) {
            e.preventDefault();
            var pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
            for (var i = 0; i < Math.min(pasted.length, boxes.length); i++) {
                boxes[i].value = pasted[i];
                boxes[i].classList.add('filled');
            }
            var focusIdx = Math.min(pasted.length, boxes.length - 1);
            boxes[focusIdx].focus();
            updateHidden();
        });
    });

    function updateHidden() {
        var otp = '';
        boxes.forEach(function(b) { otp += b.value; });
        hidden.value = otp;
    }

    // Set focus ke kotak pertama
    boxes[0].focus();

    // Pastikan hidden value terisi sebelum submit
    form.addEventListener('submit', function() {
        updateHidden();
    });
});
</script>
@endsection
