<?php

namespace App\Models\Languages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RussianWord extends Model
{
    use HasFactory;

    public function englishWords(): BelongsToMany
    {
        return $this->belongsToMany(
            EnglishWord::class,
            'english_russian_relations',
            'russian_word_id',
            'english_word_id')
//            ->where('level', '<=', 3)
            ->orderByPivot('priority', 'desc')
            ->orderBy("frequency", 'desc')
            ;

    }
}
