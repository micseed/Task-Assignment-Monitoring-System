@extends('layouts.app')

@section('title', 'Sign In — WMSU TAM System')

@push('styles')
<style>
    :root {
        --color-maroon-dark: hsl(348, 85%, 16%);
        --color-maroon: hsl(348, 83%, 22%);
        --color-maroon-light: hsl(348, 80%, 30%);
        --color-gold: hsl(45, 95%, 50%);
        --color-gold-light: hsl(45, 95%, 60%);
        --color-bg-light: hsl(0, 0%, 98%);
        --color-text-dark: hsl(0, 0%, 15%);
        --color-text-muted: hsl(0, 0%, 45%);
    }

    .login-container {
        display: flex;
        min-height: 100vh;
        background-color: var(--color-bg-light);
        font-family: 'Inter', sans-serif;
    }

    /* --- Left Panel (Branding) --- */
    .brand-panel {
        display: flex;
        flex: 1.2;
        background-color: var(--color-maroon-dark);
        color: #ffffff;
        position: relative;
        padding: 60px;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Background campus image with opacity */
    .brand-bg-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("{{ asset('images/wmsu_campus.jpg') }}");
        background-size: cover;
        background-position: center;
        opacity: 0.5; /* 50% opacity */
        z-index: 1;
    }

    /* Gradient overlay to blend the image with university maroon */
    .brand-bg-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, hsla(348, 85%, 16%, 0.82) 0%, hsla(348, 80%, 30%, 0.82) 100%);
        z-index: 2;
    }

    /* Decorative background gold glow */
    .brand-glow {
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        background: radial-gradient(circle, hsla(45, 95%, 50%, 0.08) 0%, transparent 70%);
        top: -100px;
        left: -100px;
        z-index: 3;
    }

    .brand-content {
        position: relative;
        z-index: 10;
        max-width: 500px;
        text-align: center;
    }

    .brand-logo {
        width: 140px;
        height: 140px;
        object-fit: contain;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
        margin-bottom: 28px;
        transition: transform 0.5s ease;
    }

    .brand-logo:hover {
        transform: rotate(5deg) scale(1.05);
    }

    .brand-title {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        line-height: 1.1;
        color: #ffffff;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .brand-subtitle {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--color-gold);
        letter-spacing: 0.5px;
        margin-bottom: 24px;
        text-transform: uppercase;
    }

    .brand-divider {
        width: 80px;
        height: 4px;
        background-color: var(--color-gold);
        margin: 20px auto;
        border-radius: 2px;
    }

    .brand-tagline {
        font-size: 0.95rem;
        line-height: 1.6;
        color: hsl(0, 0%, 90%);
        font-weight: 400;
    }

    /* --- Right Panel (Form) --- */
    .form-panel {
        display: flex;
        flex: 1;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background-color: #ffffff;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.03);
    }

    .form-card {
        width: 100%;
        max-width: 400px;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(24px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .mobile-header {
        display: none;
        text-align: center;
        margin-bottom: 30px;
    }

    .mobile-logo {
        width: 80px;
        height: 80px;
        margin-bottom: 12px;
    }

    .mobile-header h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--color-maroon);
        text-transform: uppercase;
        margin: 0;
    }

    .form-header {
        margin-bottom: 32px;
    }

    .form-header h2 {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--color-maroon);
        letter-spacing: -0.5px;
    }

    .form-header p {
        font-size: 0.9rem;
        color: var(--color-text-muted);
        margin-top: 6px;
    }

    /* --- Form Elements --- */
    .field {
        margin-bottom: 20px;
        position: relative;
    }

    .field label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--color-text-dark);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .field input[type="email"],
    .field input[type="password"] {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid hsl(0, 0%, 85%);
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
        color: var(--color-text-dark);
        background-color: var(--color-bg-light);
        outline: none;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
    }

    .field input:focus {
        border-color: var(--color-maroon);
        background-color: #ffffff;
        box-shadow: 0 0 0 3px hsla(348, 83%, 22%, 0.15);
    }

    .error-msg {
        font-size: 0.8rem;
        color: hsl(0, 85%, 45%);
        margin-top: 6px;
        font-weight: 500;
    }

    .remember-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 24px 0;
    }

    .checkbox-container {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.88rem;
        color: var(--color-text-muted);
        user-select: none;
    }

    .checkbox-container input {
        width: 16px;
        height: 16px;
        accent-color: var(--color-maroon);
        cursor: pointer;
    }

    .btn-submit {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--color-maroon) 0%, var(--color-maroon-light) 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px hsla(348, 83%, 22%, 0.25);
        transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
    }

    .btn-submit:hover {
        filter: brightness(1.1);
        box-shadow: 0 6px 16px hsla(348, 83%, 22%, 0.35);
    }

    .btn-submit:active {
        transform: translateY(1px);
        box-shadow: 0 2px 8px hsla(348, 83%, 22%, 0.25);
    }

    /* --- Demo Accounts Box Removed --- */

    /* --- Responsive Queries --- */
    @media (max-width: 900px) {
        .brand-panel {
            display: none;
        }
        
        .form-panel {
            box-shadow: none;
        }

        .mobile-header {
            display: block;
        }

        .form-header {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <!-- Left panel (Branding Showcase) -->
    <div class="brand-panel">
        <div class="brand-bg-image"></div>
        <div class="brand-bg-overlay"></div>
        <div class="brand-glow"></div>
        <div class="brand-content">
            <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU Logo" class="brand-logo">
            <h1 class="brand-title">WMSU</h1>
            <p class="brand-subtitle">Task Assignment &amp; Monitoring</p>
            <div class="brand-divider"></div>
            <p class="brand-tagline">
                Welcome to the Western Mindanao State University academic portal. 
                Seamlessly coordinate assignments, upload code files, track evaluations, 
                and sync deadlines in one centralized workspace.
            </p>
        </div>
    </div>
    
    <!-- Right panel (Login Form) -->
    <div class="form-panel">
        <div class="form-card">
            
            <!-- Mobile Header Logo (Visible on mobile/tablets only) -->
            <div class="mobile-header">
                <img src="{{ asset('images/wmsu_logo.png') }}" alt="WMSU Logo" class="mobile-logo">
                <h2>WMSU TAM System</h2>
            </div>
            
            <div class="form-header">
                <h2>Sign In</h2>
                <p>Welcome back! Enter your portal credentials below.</p>
            </div>
            
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="field">
                    <label for="email">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@wmsu.edu.ph"
                        autocomplete="email"
                        required
                        autofocus
                    />
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="field">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    />
                    @error('password')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="remember-row">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember" name="remember" />
                        <span>Remember my login</span>
                    </label>
                </div>
                
                <button id="btn-login" type="submit" class="btn-submit">Sign In to Portal</button>
            </form>
            
            <!-- Demo Accounts Removed -->
            
        </div>
    </div>
</div>
@endsection
