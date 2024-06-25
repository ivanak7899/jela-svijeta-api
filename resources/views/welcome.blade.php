<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jela Svijeta API</title>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            text-align: center;
            padding: 50px;
        }
        .content {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Welcome to Jela Svijeta API</h1>
        <p>This is an API for managing meals, ingredients, categories, and tags.</p>
        <p>Below are some examples of URLs for interacting with the API:</p>
        <ul>
            <li><strong><a href="{{ url('/api/meals?lang=en&per_page=5') }}">/api/meals?lang=en&per_page=5</a></strong> - List meals with pagination and language filter</li>
            <li><strong><a href="{{ url('/api/meals?lang=en&tags=1,2&with=ingredients,category,tags') }}">/api/meals?lang=en&tags=1,2&with=ingredients,category,tags</a></strong> - List meals with specific tags and include related data</li>
            <li><strong><a href="{{ url('/api/meals?lang=en&diff_time=1493902343') }}">/api/meals?lang=en&diff_time=1493902343</a></strong> - List meals deleted/modified/created after a specific time</li>
        </ul>
    </div>
</body>
</html>
