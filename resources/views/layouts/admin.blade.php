<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/admin.css" />
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">
    <title>@yield('title', 'Admin Panel')</title>
</head>
<body>
<header>
    {{ setting('site_name') }} Admin area
    <div class="admin-panel">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
   document.getElementById('logout').submit();"
        title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>

        <form id="logout" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</header>

<nav class="sidebar">
    <ul>
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.urls') }}" class="{{ request()->routeIs('admin.urls') ? 'active' : '' }}">
                <i class="fas fa-link"></i>
                URLs
            </a>
        </li>
        <li>
            <a href="{{ route('admin.reports') }}" class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                Reports
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                Site Settings
            </a>
        </li>
        <li>
            <a href="{{ route('admin.qrsettings') }}" class="{{ request()->routeIs('admin.qrsettings') ? 'active' : '' }}">
                <i class="fas fa-qrcode"></i>
                QR Settings
            </a>
        </li>
        <li>
            <a href="{{ route('admin.ads') }}" class="{{ request()->routeIs('admin.ads') ? 'active' : '' }}">
                <i class="fas fa-ad"></i>
                Ads Settings
            </a>
        </li>
        <li>
            <a href="{{ route('admin.password') }}" class="{{ request()->routeIs('admin.password') ? 'active' : '' }}">
                <i class="fas fa-key"></i>
                Change password
            </a>
        </li>
        <li>
            <a href="{{ route('home') }}" target="_blank">
                <i class="fas fa-eye"></i>
                View website
            </a>
        </li>
    </ul>
</nav>


<main>
    <div class="container">
        {{-- Display flash messages or errors if needed --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer>
    &copy; 2023 amsonline
</footer>
</body>
</html>
