<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FFIN-RESEARCH</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: Figtree, sans-serif;
            font-feature-settings: normal;
            background: #1f9d55;
        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .links {
            text-align: center;
            color: white;
        }

        .links a {
            color: inherit;
            text-decoration: underline;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 15px;
        }
    </style>
</head>

<body class="antialiased">
    <div class="flex-center">
        <div class="text-center">
            <div class="mb-8 links">
                <a href="{{ config('app.admin_url') }}">Admin</a>
                <span class="mx-2">|</span>
                <a href="{{ config('app.frontend_url') }}">Front</a>
                <span class="mx-2">|</span>
                <a href="{{ url('api/documentation') }}">Swagger</a>
            </div>
        </div>
    </div>
</body>
</html>
