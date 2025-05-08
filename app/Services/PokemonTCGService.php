<?php

namespace App\Services;

use GuzzleHttp\Client;

class PokemonTCGService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.pokemontcg.io/v2/',
            'headers' => [
                'X-Api-Key' => config('services.pokemon_tcg.api_key'),
            ],
        ]);
    }

    public function getCards($page = 1, $pageSize = 10)
    {
        $response = $this->client->get('cards', [
            'query' => [
                'page' => $page,
                'pageSize' => $pageSize,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function getCardById($id)
    {
        $response = $this->client->get("cards/{$id}");

        return json_decode($response->getBody(), true);
    }
}