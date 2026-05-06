<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pokemon;
use Illuminate\Support\Facades\Http;

$pokemons = Pokemon::whereNotNull('flavor_text')->get();
$count = 0;

foreach ($pokemons as $pokemon) {
    $text = $pokemon->flavor_text;
    
    // Simplistic check to see if it's already translated or just run it anyway.
    // If it contains english words like "the", "is", "and" it might be english.
    // Shuppet description: "SHUPPET is attracted by feelings..."
    
    if (str_contains(strtolower($text), ' is ') || str_contains(strtolower($text), ' the ') || str_contains(strtolower($text), ' it ') || str_contains(strtolower($text), ' of ') || str_contains(strtolower($text), ' by ') || str_contains(strtolower($text), ' a ')) {
        
        echo "Translating: " . $pokemon->nome . "\n";
        
        try {
            $response = Http::timeout(8)->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl'     => 'en',
                'tl'     => 'pt',
                'dt'     => 't',
                'q'      => $text,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $translated = '';
                if (isset($data[0]) && is_array($data[0])) {
                    foreach ($data[0] as $segment) {
                        if (isset($segment[0])) {
                            $translated .= $segment[0];
                        }
                    }
                }

                $translated = trim($translated);
                if ($translated !== '' && $translated !== $text) {
                    $pokemon->flavor_text = $translated;
                    $pokemon->save();
                    $count++;
                    echo " -> Success\n";
                }
            }
        } catch (\Exception $e) {
            echo " -> Failed: " . $e->getMessage() . "\n";
        }
        
        // slight delay to avoid rate limits
        usleep(100000); 
    }
}

echo "Translated $count pokemons.\n";
