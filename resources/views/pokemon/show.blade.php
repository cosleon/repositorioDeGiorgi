<!DOCTYPE html>
<html>
<head>
    <title>{{ $card['data']['name'] }}</title>
</head>
<body>
    <h1>{{ $card['data']['name'] }}</h1>
    <img src="{{ $card['data']['images']['large'] }}" alt="{{ $card['data']['name'] }}">
    <p>{{ $card['data']['flavorText'] ?? 'No description available.' }}</p>
    <a href="{{ route('pokemon.index') }}">Back to list</a>
</body>
</html>