<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="{{ asset('tailwind_theme/tailwind.css') }}" rel="stylesheet" type="text/css">

    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <script>
        /* Pinegrow Interactions, do not remove */
        (function() {
            try {
                if (!document.documentElement.hasAttribute('data-pg-ia-disabled')) {
                    window.pgia_small_mq = typeof pgia_small_mq == 'string' ? pgia_small_mq : '(max-width:767px)';
                    window.pgia_large_mq = typeof pgia_large_mq == 'string' ? pgia_large_mq : '(min-width:768px)';
                    var style = document.createElement('style');
                    var pgcss =
                        'html:not(.pg-ia-no-preview) [data-pg-ia-hide=""] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show=""] {opacity:1;visibility:visible;display:block;}';
                    if (document.documentElement.hasAttribute('data-pg-id') && document.documentElement.hasAttribute(
                            'data-pg-mobile')) {
                        pgia_small_mq = '(min-width:0)';
                        pgia_large_mq = '(min-width:99999px)'
                    }
                    pgcss += '@media ' + pgia_small_mq +
                        '{ html:not(.pg-ia-no-preview) [data-pg-ia-hide="mobile"] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show="mobile"] {opacity:1;visibility:visible;display:block;}}';
                    pgcss += '@media ' + pgia_large_mq +
                        '{html:not(.pg-ia-no-preview) [data-pg-ia-hide="desktop"] {opacity:0;visibility:hidden;}html:not(.pg-ia-no-preview) [data-pg-ia-show="desktop"] {opacity:1;visibility:visible;display:block;}}';
                    style.innerHTML = pgcss;
                    document.querySelector('head').appendChild(style);
                }
            } catch (e) {
                console && console.log(e);
            }
        })()
    </script>
</head>

<body class="min-h-screen font-serif text-gray-500 bg-base-200/50 dark:bg-base-200">
    {{ $slot }}
</body>

</html>
