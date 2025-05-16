@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Explorar Cartas</h1>

    <div class="mb-3">
        <label for="expansion" class="form-label">Selecciona una expansión:</label>
        <select id="expansion" class="form-select">
            <option value="">-- Seleccionar --</option>
            {{-- Asegúrate de pasar la variable $expansions desde el controlador que renderiza esta vista --}}
            @foreach ($expansions as $expansion)
                <option value="{{ $expansion->id }}">{{ $expansion->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="pokemonName" class="form-label">Buscar por nombre de Pokémon:</label>
        <input type="text" id="pokemonName" class="form-control" placeholder="Escribe el nombre del Pokémon">
    </div>

    {{-- Este bloque @auth/@endauth define si se muestra el filtro de posesión --}}
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

    {{-- Contenedor donde se renderizarán las cartas --}}
    <div id="cardsContainer" class="row mt-4"></div>

    {{-- Contenedor donde se renderizarán los enlaces de paginación --}}
    <div id="paginationLinks" class="mt-4 d-flex justify-content-center">
        {{-- Los botones de paginación se añadirán aquí con JavaScript --}}
    </div>

</div> {{-- Cierre del div .container --}}

{{-- Script JavaScript para la lógica AJAX y paginación --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchBtn = document.getElementById('searchBtn');
        const expansionSelect = document.getElementById('expansion');
        const pokemonNameInput = document.getElementById('pokemonName');
        const cardFilterSelect = document.getElementById('cardFilter'); 
        const cardsContainer = document.getElementById('cardsContainer');
        const paginationLinksContainer = document.getElementById('paginationLinks');

        let currentPageUrl = '';

        function buildSearchUrl(page = 1) {
            let expansionId = expansionSelect.value;
            let pokemonName = pokemonNameInput.value;
            let filter = cardFilterSelect ? cardFilterSelect.value : 'all';

            let url = `/cards/search?page=${page}`;

            if (expansionId) {
                url += `&expansion_id=${expansionId}`;
            }
            if (pokemonName) {
                url += `&name=${encodeURIComponent(pokemonName)}`;
            }
            if (filter !== 'all') {
                 url += `&filter=${filter}`;
            }

            return url;
        }

        function fetchAndRenderCards(url) {
            currentPageUrl = url;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => { 
                    renderCards(data.data);
                    renderPagination(data);
                })
                .catch(error => {
                    console.error('Error fetching cards:', error);
                    cardsContainer.innerHTML = '<p>Error al cargar las cartas.</p>';
                    paginationLinksContainer.innerHTML = ''; 
                });
        }

        function renderCards(cards) {
            cardsContainer.innerHTML = ''; 

            if (cards.length === 0) {
                cardsContainer.innerHTML = '<p>No se encontraron cartas con los filtros seleccionados.</p>';
                return;
            }

            cards.forEach(card => {
                cardsContainer.innerHTML += `
                    <div class="col-md-3 mb-4"> {{-- Columna con ancho para 4 cartas por fila en pantallas md y superior, y margen inferior --}}
                        <div class="card h-100"> {{-- Tarjeta con altura fija --}}
                            {{-- La imagen puede ser grande, 'loading="lazy"' ayuda a la performance --}}
                            <img src="${card.image_url}" loading="lazy" class="card-img-top" alt="${card.name}">
                            <div class="card-body d-flex flex-column"> {{-- Cuerpo de la tarjeta, usando flexbox --}}
                                <h5 class="card-title">${card.name}</h5>
                                <p class="card-text mb-1">Expansión: ${card.expansion?.name || 'Desconocida'}</p>
                                <p class="card-text mb-2">Rareza: ${card.rarity || 'Desconocida'}</p>
                                {{-- El div con mt-auto empuja el contenido restante (el botón) hacia abajo --}}
                                <div class="mt-auto">
                                    ${card.can_mark ? (
                                        card.owned ?
                                        `<button class="btn btn-sm btn-outline-danger w-100" onclick="unmarkOwned(${card.id})">No la tengo</button>` :
                                        
                                        `<button class="btn btn-sm btn-outline-primary w-100" onclick="markOwned(${card.id})">Tengo esta</button>`
                                    ) : ''} {{-- Si can_mark es false (usuario no logueado), no muestra nada --}}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        function renderPagination(paginationData) {
            paginationLinksContainer.innerHTML = ''; 

            if (paginationData.last_page <= 1) {
                return;
            }

            paginationData.links.forEach(link => {
                if (link.url === null) {
                    return; 
                }

                const button = document.createElement('button');
                button.innerHTML = link.label; 
                button.className = `btn btn-outline-primary mx-1 ${link.active ? 'active' : ''}`;
                button.disabled = link.active; 
                button.setAttribute('data-url', link.url); 

                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    if (url) {
                        fetchAndRenderCards(url); 
                    }
                });

                paginationLinksContainer.appendChild(button);
            });
        }


        searchBtn.addEventListener('click', function() {
            const initialUrl = buildSearchUrl(1); 
            fetchAndRenderCards(initialUrl);
        });
        
        window.markOwned = function(cardId) {
            fetch(`/cards/mark-owned/${cardId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                }
            })
            .then(response => {
                 if (!response.ok) {
                     throw new Error(`HTTP error! status: ${response.status}`);
                 }
                 return response.json();
            })
            .then(data => {
                if (currentPageUrl) {
                   fetchAndRenderCards(currentPageUrl);
                } else {
                   searchBtn.click();
                }
            })
            .catch(error => console.error('Error marking owned:', error));
        };

        window.unmarkOwned = function(cardId) {
            fetch(`/cards/unmark-owned/${cardId}`, {
                method: 'DELETE', 
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                     'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                 if (currentPageUrl) {
                   fetchAndRenderCards(currentPageUrl);
                 } else {
                    searchBtn.click();
                 }
            })
             .catch(error => console.error('Error unmarking owned:', error));
        };


        

    }); 
</script>

@endsection {{-- Cierre de la sección de contenido --}}