<!DOCTYPE html>
<html>
<head>
    <title>Pokémon Cards</title>
</head>
<body>
    <h1>Pokémon Cards</h1>
    <ul>
        @foreach ($cards['data'] as $card)
            <li>
                <a href="{{ route('pokemon.show', $card['id']) }}">
                    {{ $card['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>