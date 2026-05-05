<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons';

    protected $fillable = [
        'nome',
        'pokemon_id',
        'tipo_primario',
        'tipo_secundario',
        'hp',
        'ataque',
        'defesa',
        'sp_ataque',
        'sp_defesa',
        'velocidade',
        'altura',
        'peso',
        'imagem_url',
        'imagem_local',
        'flavor_text',
        'apelido',
    ];

    protected $casts = [
        'pokemon_id' => 'integer',
        'hp' => 'integer',
        'ataque' => 'integer',
        'defesa' => 'integer',
        'sp_ataque' => 'integer',
        'sp_defesa' => 'integer',
        'velocidade' => 'integer',
        'altura' => 'integer',
        'peso' => 'integer',
        'tipo_secundario' => 'string',
        'imagem_url' => 'string',
        'imagem_local' => 'string',
        'flavor_text' => 'string',
        'apelido' => 'string',
    ];

    public static function jaExiste(int|string $pokemonId): bool
    {
        return static::where('pokemon_id', (int) $pokemonId)->exists();
    }
}
