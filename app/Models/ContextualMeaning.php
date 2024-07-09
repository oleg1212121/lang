<?php

namespace App\Models;

use App\Models\CustomRelations\HasCustomRelations;
use App\Models\Languages\EnglishWord;
use App\Models\Languages\RussianWord;
use GuzzleHttp\Psr7\Query;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContextualMeaning extends Model
{

    use HasFactory;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    protected $table = "contextual_meanings";

    public function englishWords(): BelongsToMany
    {
        return $this->belongsToMany(EnglishWord::class, 'english_context_meanings', 'context_meaning_id', 'english_word_id');

    }


    public function russianWords()
    {
        return $this->hasManyDeep(RussianWord::class,
            ['english_context_meanings', EnglishWord::class, 'english_russian_relations'],
            [
                'context_meaning_id', // Foreign key on the "role_user" table.
                'id',      // Foreign key on the "roles" table (local key).
                'english_word_id'  // Foreign key on the "permissions" table.
            ],
            [
                'id',      // Local key on the "users" table.
                'english_word_id', // Local key on the "role_user" table (foreign key).
                'id'       // Local key on the "roles" table.
            ]
        );
    }
}
