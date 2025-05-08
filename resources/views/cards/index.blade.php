@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Explorar Cartas</h1>
    
    <div class="mb-3">
        <label for="expansion" class="form-label">Selecciona una expansión:</label>
        <select id="expansion" class="form-select">
            <option value="">-- Seleccionar --</option>
            @foreach ($expansions as $expansion)
                <option value="{{ $expansion->id }}">{{ $expansion->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="pokemonName" class="form-label">Buscar por nombre de Pokémon:</label>
        <input type="text" id="pokemonName" class="form-control" placeholder="Escribe el nombre del Pokémon">
    </div>

@auth
    <div class="mb-3">
        <label for="cardFilter" class="form-label">Filtrar por:</label>
        <select id="cardFilter" class="form-select">
            <option value="all">Todas</option>
            <option value="owned">Cartas en posesión</option>
            <option value="missing">Cartas faltantes</option>
        </select>
    </div>
@endauth


    <div class="mb-3">
        <button id="searchBtn" class="btn btn-primary">Buscar</button>
    </div>


    <div id="cardsContainer" class="row mt-4"></div>
</div>


<script>
document.getElementById('searchBtn').addEventListener('click', function() {
    let expansionId = document.getElementById('expansion').value;
    let pokemonName = document.getElementById('pokemonName').value;
    let filterElement = document.getElementById('cardFilter');
    let filter = filterElement ? filterElement.value : 'all';


    // Construir la URL con el filtro incluido
    let url = `/cards/search?expansion_id=${expansionId}&name=${encodeURIComponent(pokemonName)}&filter=${filter}`;

    fetch(url)
        .then(response => response.json())
        .then(cards => {
            let container = document.getElementById('cardsContainer');
            container.innerHTML = '';
            if (cards.length === 0) {
                container.innerHTML = '<p>No se encontraron cartas.</p>';
                return;
            }
            cards.forEach(card => {
                container.innerHTML += `
                    <div class="col-md-3">
                        <div class="card">
                            <img src="${card.image_url}" class="card-img-top" alt="${card.name}">
                            <div class="card-body">
                                <h5 class="card-title">${card.name}</h5>
                                <p class="card-text">Expansión: ${card.expansion?.name || 'Desconocida'}</p>
                                <p class="card-text">Rareza: ${card.rarity || 'Desconocida'}</p>
                                ${card.can_mark ? (
                                    card.owned ?
                                    `<button class="btn btn-sm btn-outline-danger" onclick="unmarkOwned(${card.id})">No la tengo</button>` :
                                    `<button class="btn btn-sm btn-outline-primary" onclick="markOwned(${card.id})">Tengo esta</button>`
                                ) : ''}

                            </div>
                        </div>
                    </div>
                `;
            });
        });
});

// Función para marcar una carta como poseída
function markOwned(cardId) {
    fetch(`/cards/mark-owned/${cardId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
    document.getElementById('searchBtn').click(); // vuelve a cargar las cartas
});

}

function unmarkOwned(cardId) {
    fetch(`/cards/unmark-owned/${cardId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
    document.getElementById('searchBtn').click(); // vuelve a cargar las cartas
});

}
</script>

@endsection