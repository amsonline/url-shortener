<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="/style.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
        <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

        <title>@yield('title')</title>
    </head>

    <body>
        <!-- Top Ad -->
        <x-adsense-ad location="top" />

        <div class="header">
            <div class="logo">
                {{ setting('site_name') }}
            </div>
            <div class="nav">
                <div class="nav-item">
                    <a href="{{ url("/") }}">
                        <i class="fas fa-link"></i>
                        Shorten URL
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url("/details") }}">
                        <i class="fas fa-info-circle"></i>
                        URL Details
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url("/report") }}">
                        <i class="fas fa-flag"></i>
                        Report
                    </a>
                </div>
                <div class="nav-item nav-spacer">&nbsp;</div>
            </div>
        </div>
        <div id="app" class="content-area">
            <!-- Left Ad -->
            <x-adsense-ad location="left" />


            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <!-- Right Ad -->
            <x-adsense-ad location="right" />

        </div>

        <!-- Bottom Ad -->
        <x-adsense-ad location="bottom" />

    <div class="footer">
        &copy; {{ date("Y") }} {{ setting('site_name') }}
    </div>
    </body>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @yield('js')
</html>
