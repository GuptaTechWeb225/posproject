<!doctype html>
<html lang="<?php  app()->getLocale(); ?>">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Welcome to Gain Core Apps</title>
    @stack('before-styles')
    {{ style(mix('css/dropzone.css')) }}
    {{ style(mix('css/core.css')) }}
    {{ style(mix('css/fontawesome.css')) }}
    @stack('after-styles')
</head>
<body>
<div id="app">
    <div class="container-scroller">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo">
                    <img src="{{ asset('images/core.png') }}" alt="logo"/>
                </a>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper pt-0">
            <div class="main-panel">
                <h6 class="text-center m-3">Core POS api Documentation</h6>
                <div class="container p-3"><code>{!! $content !!}</code></div>
            </div>
        </div>
    </div>
</div>

@stack('before-scripts')
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
@stack('after-scripts')

</body>
</html>
