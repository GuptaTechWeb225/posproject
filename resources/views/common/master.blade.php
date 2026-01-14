<!doctype html>
<html lang="<?php  app()->getLocale(); ?>" dir="{{ config('settings.application.layout') }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ url(settings('company_icon')) }}" />

    <link rel="apple-touch-icon" href="{{ url(settings('company_icon')) }}" />
    <link rel="apple-touch-icon-precomposed" href="{{ url(settings('company_icon')) }}" />

    <title>@yield('title') - {{ settings('company_name') }}</title>

    @stack('before-styles')
    {{ style(mix('css/core.css')) }}
    {{ style(mix('css/fontawesome.css')) }}
    {{ style(mix('css/dropzone.css')) }}
    {{ style('vendor/summernote/summernote-bs4.css') }}
    @stack('after-styles')
</head>
<body>
<div id="app" class="@yield('class')">
    @yield('master')
</div>
@guest()
    <script>
        window.settings = {!! isset($settings) ? json_encode($settings): '{}' !!}
        window.localStorage.removeItem('permissions');
    </script>
@endguest

@auth()
    <script>
        window.settings = {!! isset($settings) ? json_encode($settings): '{}' !!}
        window.localStorage.setItem('permissions', JSON.stringify(
            <?php echo json_encode(array_merge(
                    resolve(\App\Repositories\Core\Auth\UserRepository::class)->getPermissionsForFrontEnd(),
                    [
                        'is_app_admin' => auth()->user()->isAppAdmin(),
                    ]
                )
            )
            ?>
        ))

        window.onload = function () {
            window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth'
            })
        }
    </script>
@endauth

<script>
    let appVersion = window.localStorage.getItem('app_version')
    if((!appVersion) || (String(appVersion) !== "{{strval(config('gain.app_version'))}}")) {
        window.localStorage.removeItem('cartState')
        window.localStorage.removeItem('cash_register_id')
        window.localStorage.removeItem('currentBranchWahrehouseId')
        window.localStorage.removeItem('currentBranchWahrehouseName')
    }
    window.localStorage.setItem('app_version', "{{strval(config('gain.app_version'))}}");
    window.localStorage.setItem('app-language', '<?php echo app()->getLocale() ?? "en"; ?>');
    window.localStorage.setItem('app-languages',
        JSON.stringify(
            <?php echo json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(app()->getLocale() ?? 'en').DIRECTORY_SEPARATOR.'default.php')?>
        )
    );
    window.localStorage.setItem('base_url', '<?php echo request()->root() ?>');
    window.appLanguage = window.localStorage.getItem('app-language');
</script>
@stack('before-scripts')
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
{!! script('vendor/summernote/summernote-bs4.js') !!}
@stack('after-scripts')
</body>
</html>

