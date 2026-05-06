<?php

namespace Tests\Feature;

use App\Models\Pokemon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PokemonCadastroTest extends TestCase
{
    use RefreshDatabase;

    public function test_cadastro_manual_salva_pokemon_e_imagem_no_banco(): void
    {
        Storage::fake('public');

        $response = $this->postJson(route('pokemon.cadastrar'), [
            'pokemon_id' => 11000,
            'nome' => 'Nebulince',
            'tipo_primario' => 'water',
            'tipo_secundario' => 'psychic',
            'hp' => 80,
            'ataque' => 70,
            'defesa' => 65,
            'sp_ataque' => 90,
            'sp_defesa' => 75,
            'velocidade' => 60,
            'altura' => 12,
            'peso' => 340,
            'flavor_text' => 'Pokemon criado manualmente.',
            'apelido' => 'Nebu',
            'imagem' => $this->fakePng('nebulince.png'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('pokemons', [
            'pokemon_id' => 11000,
            'nome' => 'Nebulince',
            'tipo_primario' => 'water',
            'tipo_secundario' => 'psychic',
            'imagem_local' => 'pokemons/pokemon_11000_nebulince.png',
        ]);

        Storage::disk('public')->assertExists('pokemons/pokemon_11000_nebulince.png');
    }

    public function test_cards_usam_rota_laravel_para_imagem_local(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('pokemons/pokemon_12000_cardmon.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        ));

        Pokemon::create([
            'pokemon_id' => 12000,
            'nome' => 'Cardmon',
            'tipo_primario' => 'fire',
            'tipo_secundario' => null,
            'hp' => 50,
            'ataque' => 55,
            'defesa' => 45,
            'sp_ataque' => 65,
            'sp_defesa' => 40,
            'velocidade' => 70,
            'altura' => 10,
            'peso' => 200,
            'imagem_url' => null,
            'imagem_local' => 'pokemons/pokemon_12000_cardmon.png',
            'flavor_text' => null,
            'apelido' => null,
        ]);

        $imageResponse = $this->get(route('pokemon.imagem', ['path' => 'pokemons/pokemon_12000_cardmon.png']));
        $imageResponse->assertOk();

        $response = $this->getJson(route('pokedex.buscar', ['status' => 'registered']));

        $response->assertOk();
        $this->assertStringContainsString(
            '/pokemon/imagem/pokemons/pokemon_12000_cardmon.png',
            $response->json('html')
        );

        $detailResponse = $this->getJson(route('pokedex.show', 12000));
        $detailResponse->assertOk()
            ->assertJsonPath('pokemon.delete_url', '/pokemon/12000/excluir');
    }

    public function test_endpoint_pokemon_novo_salva_no_banco(): void
    {
        $response = $this->postJson(route('pokemon.novo'), [
            'nome' => 'BancoMon',
            'tipo' => 'water',
            'ataque' => 77,
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('pokemons', [
            'nome' => 'BancoMon',
            'tipo_primario' => 'water',
            'ataque' => 77,
        ]);
    }

    public function test_excluir_pokemon_remove_registro_e_imagem_local(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('pokemons/pokemon_13000_deletemon.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        ));

        Pokemon::create([
            'pokemon_id' => 13000,
            'nome' => 'DeleteMon',
            'tipo_primario' => 'ghost',
            'tipo_secundario' => null,
            'hp' => 40,
            'ataque' => 50,
            'defesa' => 45,
            'sp_ataque' => 60,
            'sp_defesa' => 55,
            'velocidade' => 70,
            'altura' => 8,
            'peso' => 120,
            'imagem_url' => null,
            'imagem_local' => 'pokemons/pokemon_13000_deletemon.png',
            'flavor_text' => null,
            'apelido' => null,
        ]);

        $response = $this->deleteJson('/pokemon/13000');

        $response->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('pokemons', [
            'pokemon_id' => 13000,
        ]);
        Storage::disk('public')->assertMissing('pokemons/pokemon_13000_deletemon.png');
    }

    private function fakePng(string $name): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'pokemon_png_');
        file_put_contents($path, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        ));

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
