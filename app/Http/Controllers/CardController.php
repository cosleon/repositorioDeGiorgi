<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Expansion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class CardController extends Controller
{
    public function index()
    {
        $expansions = Expansion::all();
        return view('cards.index', compact('expansions'));
    }

    public function search(Request $request)
{
    $userId = auth()->id();

    $expansionId = $request->query('expansion_id');
    $pokemonName = $request->query('name');
    $filter = $request->query('filter');

    $query = Card::with('expansion');

    if ($expansionId) {
        $query->where('expansion_id', $expansionId);
    }

    if ($pokemonName) {
        $query->where('name', 'like', '%' . $pokemonName . '%');
    }

    // Aplicar filtro solo si el usuario está autenticado y el filtro es válido
    if (Auth::check() && in_array($filter, ['owned', 'missing'])) {
        $user = Auth::user();

        if ($filter === 'owned') {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($filter === 'missing') {
            $query->whereDoesntHave('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
    }

    $cards = $query->get();

    // ✅ Añadir campos 'owned' y 'can_mark' a cada carta para el frontend
    if ($userId) {
        // Obtener IDs de cartas que tiene el usuario
        $userCardIds = \DB::table('user_cards')
            ->where('user_id', $userId)
            ->pluck('card_id')
            ->toArray();
    } else {
        $userCardIds = [];
    }

    $cards->each(function ($card) use ($userCardIds, $userId) {
        $card->owned = $userId ? in_array($card->id, $userCardIds) : false;
        $card->can_mark = $userId !== null;
    });

    return response()->json($cards);
}



public function markOwned($cardId)
{
    $userId = auth()->id();

    DB::table('user_cards')->updateOrInsert(
        ['user_id' => $userId, 'card_id' => $cardId],
        ['quantity' => 1]
    );

    return response()->json(['success' => true]);
}

public function unmarkOwned($cardId)
{
    $userId = auth()->id();

    DB::table('user_cards')
        ->where('user_id', $userId)
        ->where('card_id', $cardId)
        ->delete();

    return response()->json(['success' => true]);
}


public function owned(Request $request)
{
    $userId = auth()->id();

    $cards = Card::whereIn('id', function($query) use ($userId) {
        $query->select('card_id')
              ->from('user_cards')
              ->where('user_id', $userId);
    })->get();

    return response()->json($cards);
}

public function missing(Request $request)
{
    $userId = auth()->id();

    $cards = Card::whereNotIn('id', function($query) use ($userId) {
        $query->select('card_id')
              ->from('user_cards')
              ->where('user_id', $userId);
    })->get();

    return response()->json($cards);
}




    /**
     * Show the form for creating a new resource.
     */
    public function ListarPromos()
    {
        $cards = Card::where('rarity', 'Promo')->get();
        return view('cards.listarPromos', compact('cards'));
    }


    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'expansion_id' => 'required',
            'image_url' => 'nullable|url',
        ]);

        Card::create([
            'name' => $request->name,
            'expansion_id' => $request->expansion_id,
            'image_url' => $request->image_url,
            'rarity' => 'Promo', // Siempre será "Promo"
        ]);

        return redirect()->route('cards.listarPromos')->with('success', 'Carta añadida exitosamente');
    }

    public function edit(Card $card)
    {
        return view('cards.edit', compact('card'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'name' => 'required',
            'expansion_id' => 'required',
            'image_url' => 'nullable|url',
            'rarity' => 'Promo',
        ]);

        $card->update($request->all());

        return redirect()->route('cards.listarPromos')->with('success', 'Carta actualizada');
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return redirect()->route('cards.listarPromos')->with('success', 'Carta eliminada');
    }
}
