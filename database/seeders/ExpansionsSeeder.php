<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExpansionsSeeder extends Seeder
{
    public function run()
    {
        $path = storage_path('app/sets.json');
        $json = File::get($path);
        $sets = json_decode($json, true);

        if (!$sets) {
            dd("Error al decodificar JSON: " . json_last_error_msg());
        }

        // Verificar que existe un juego base (Pokémon TCG)
        $game = DB::table('games')->where('name', 'Pokemon TCG')->first();

        if (!$game) {
            $gameId = DB::table('games')->insertGetId(['name' => 'Pokemon TCG']);
        } else {
            $gameId = $game->id;
        }

        // Insertar las expansiones en la base de datos
        foreach ($sets as $set) {
            DB::table('expansions')->insert([
                'game_id' => $gameId,
                'set' => $set['id'],
                'name' => $set['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Expansiones insertadas correctamente.');
    }
}
