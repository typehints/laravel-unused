<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Laravel Unused | {{ config('app.name') }}</title>
    <link href="{{ asset(mix('app.css', 'vendor/laravelunused')) }}" rel="stylesheet">
</head>
<body>

<div id="laravel-unused" v-cloak>
    <router-view
        :usedviews="{{ json_encode($usedViews) }}"
        :unusedviews="{{ json_encode($unusedViews) }}"
    ></router-view>
</div>

    <script>
        window.LaravelUnused = @json($laravelUnusedScriptVariables);
    </script>

    <script src="{{asset(mix('app.js', 'vendor/laravelunused'))}}"></script>
</body>
</html>
