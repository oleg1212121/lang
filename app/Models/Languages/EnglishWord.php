<?php

namespace App\Models\Languages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EnglishWord extends Model
{
    use HasFactory;
    protected $table = "english_words";
    public function russianWords(): BelongsToMany
    {
        return $this->belongsToMany(
            RussianWord::class,
            'english_russian_relations',
            'english_word_id',
            'russian_word_id')
            ->orderByPivot('priority', 'desc')
            ->orderBy("frequency", 'desc')
            ;

    }
}
