<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CardsSeeder extends Seeder
{
    public function run()
    {
        $files = Storage::disk('local')->files('cards');

        if (empty($files)) {
            dump("No se encontraron archivos en storage/app/cards/");
            return;
        }

        foreach ($files as $file) {
            dump("Procesando archivo: $file");

            $json = Storage::disk('local')->get($file);
            $cards = json_decode($json, true);

            if (!$cards) {
                dump("Error al leer o decodificar JSON del archivo: $file");
                continue;
            }

            foreach ($cards as $card) {
                // Extraer la parte del ID antes del guion
                $cardId = $card['id'];
                $expansionCode = explode('-', $cardId)[0]; // Obtiene la parte antes del guion

                // Buscar la expansión en la base de datos usando el código extraído
                $expansion = DB::table('expansions')->where('set', $expansionCode)->first();

                if (!$expansion) {
                    dump("⚠️ No se encontró la expansión con set '$expansionCode', se omitirá la carta '{$card['name']}'");
                    continue; // Omitir la carta si no se encuentra la expansión
                }

                // Insertar la carta en la base de datos
                DB::table('cards')->insert([
                    'expansion_id' => $expansion->id,
                    'name' => $card['name'],
                    'rarity' => $card['rarity'] ?? 'Promo',
                    'image_url' => $card['images']['large'] ?? null,
                    'other_attributes' => json_encode($card),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                dump("✅ Carta '{$card['name']}' insertada correctamente con expansion_id: {$expansion->id}");
            }
        }

        dump("¡Todas las cartas se han procesado correctamente!");
    }
}