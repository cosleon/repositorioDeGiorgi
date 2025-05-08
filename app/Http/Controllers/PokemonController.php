<?php

namespace App\Http\Controllers;

use App\Services\PokemonTCGService;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    protected $pokemonTCGService;

    public function __construct(PokemonTCGService $pokemonTCGService)
    {
        $this->pokemonTCGService = $pokemonTCGService;
    }

    public function index()
    {
        $cards = $this->pokemonTCGService->getCards();
        return view('pokemon.index', compact('cards'));
    }

    public function show($id)
    {
        $card = $this->pokemonTCGService->getCardById($id);
        return view('pokemon.show', compact('card'));
    }
}