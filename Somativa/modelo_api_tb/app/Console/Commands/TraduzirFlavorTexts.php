<?php

namespace App\Console\Commands;

use App\Models\Pokemon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class TraduzirFlavorTexts extends Command
{
    protected $signature   = 'pokemon:traduzir-descricoes
                                {--dry-run : Exibe o que seria traduzido sem salvar}
                                {--force   : Reprocessa mesmo registros que ja tenham traducao detectada}';

    protected $description = 'Traduz os flavor texts dos Pokemon cadastrados para pt-BR via MyMemory API';

    /**
     * Prefixos/palavras que indicam que o texto JA esta em portugues.
     * Usamos uma heuristica simples para nao retraduzir desnecessariamente.
     */
    private const PT_HINTS = [
        'é', 'ão', 'ção', 'ões', 'ão', 'ê', 'â', 'ã', 'ú', 'ó',
        ' de ', ' do ', ' da ', ' dos ', ' das ', ' com ', ' para ',
        ' uma ', ' um ', ' os ', ' as ', ' seu ', ' sua ',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force  = (bool) $this->option('force');

        $pokemon = Pokemon::query()
            ->whereNotNull('flavor_text')
            ->orderBy('pokemon_id')
            ->get();

        if ($pokemon->isEmpty()) {
            $this->info('Nenhum Pokemon com flavor_text encontrado.');

            return self::SUCCESS;
        }

        $this->info("Verificando {$pokemon->count()} Pokemon(s)...");
        $bar = $this->output->createProgressBar($pokemon->count());
        $bar->start();

        $translated = 0;
        $skipped    = 0;
        $failed     = 0;

        foreach ($pokemon as $poke) {
            $bar->advance();
            $text = $poke->flavor_text;

            if (! $force && $this->looksLikePortuguese($text)) {
                $skipped++;
                continue;
            }

            $sourceLang = $this->detectSourceLang($text);
            $result     = $this->callMyMemory($text, $sourceLang);

            if ($result === null || $result === $text) {
                $failed++;
                continue;
            }

            if (! $dryRun) {
                $poke->flavor_text = $result;
                $poke->save();
            } else {
                $this->newLine();
                $this->line("  <fg=yellow>#{$poke->pokemon_id} {$poke->nome}</>");
                $this->line("  <fg=gray>Original ({$sourceLang}):</> {$text}");
                $this->line("  <fg=green>Traduzido:</> {$result}");
            }

            $translated++;

            // Pausa de 300 ms entre requisicoes para nao ultrapassar o limite da API.
            usleep(300_000);
        }

        $bar->finish();
        $this->newLine(2);

        $label = $dryRun ? 'Seriam traduzidos' : 'Traduzidos';
        $this->info("{$label}: {$translated} | Ja em PT: {$skipped} | Falhas: {$failed}");

        return self::SUCCESS;
    }

    private function looksLikePortuguese(string $text): bool
    {
        $lower = mb_strtolower($text);

        foreach (self::PT_HINTS as $hint) {
            if (str_contains($lower, $hint)) {
                return true;
            }
        }

        return false;
    }

    private function detectSourceLang(string $text): string
    {
        // Palavras exclusivamente espanholas (nao existem em ingles)
        $spanishHints = [' es ', ' que ', ' del ', ' los ', ' las ', 'ión', ' con ', ' una ', ' puede '];

        $lower = mb_strtolower($text);

        foreach ($spanishHints as $hint) {
            if (str_contains($lower, $hint)) {
                return 'es';
            }
        }

        return 'en';
    }

    private function callMyMemory(string $text, string $sourceLang): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q'        => $text,
                'langpair' => "{$sourceLang}|pt-BR",
            ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            if (($data['responseStatus'] ?? null) !== 200) {
                return null;
            }

            $translated = trim($data['responseData']['translatedText'] ?? '');

            return $translated !== '' ? $translated : null;
        } catch (Throwable) {
            return null;
        }
    }
}
