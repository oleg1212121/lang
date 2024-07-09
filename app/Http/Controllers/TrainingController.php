<?php

namespace App\Http\Controllers;

use App\Models\ContextualMeaning;
use App\Models\Languages\EnglishWord;
use App\Models\Languages\RussianWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
//        $rows = file("newr.txt");
//        $data = [];
//        foreach($rows as $row){
//            $data[] = "('".trim(strtolower($row))."', 5)";
//        }
//        $data = implode(", ", $data);
//        DB::insert("INSERT INTO raw2 (word, rating) VALUES {$data}");
//        dd(1);

//        DB::update("
//            UPDATE user_levels ul
//        SET level = tmp.level+1
//        FROM (
//            SELECT
//                (
//                    CASE
//                        WHEN count(*) = 0 THEN min(known) + 2
//                        ELSE min(known) + 1
//                    END
//                ) as level
//        FROM russian_words rw
//        WHERE level <= (SELECT level FROM user_levels) AND known <= level
//      ) tmp"
//        );
//        $lvl = DB::selectOne("SELECT level FROM user_levels LIMIT 1");
        $orderBy = "
            (
                (extract(epoch from ((NOW()+INTERVAL '1 day')::timestamp - COALESCE(updated_at,NOW())::timestamp)) * 1
                + (frequency * 100)
            )
            / ( known * known * 1 + 1)) DESC, level ASC";
        $words = RussianWord::with('englishWords')
            ->where('level','<=', 2)
            ->orderByRaw($orderBy)
            ->limit(50)->get();


        return view('templates.training',[
            'words' => $words
        ]);
    }

    public function learn(Request $request, $id, $known)
    {
        DB::update("UPDATE russian_words SET known = {$known}, updated_at = NOW() WHERE id = {$id}");
        return response("learn completed", 200);
    }
    public function update(Request $request)
    {

    }
    public function down(Request $request, $id)
    {
        DB::update("UPDATE russian_words SET frequency = (frequency-5) WHERE id = {$id};");
        return response("Frequency down completed", 200);
    }

    public function changePriority(Request $request, $ru, $en, $num)
    {
        DB::update("UPDATE english_russian_relations SET priority = ABS(COALESCE(priority,0)+{$num}) WHERE english_word_id = {$en} AND  russian_word_id = {$ru};");
        return response("Change priority completed", 200);
    }

    public function associate(Request $request, $ru, $en)
    {
        DB::insert("INSERT INTO english_russian_relations (russian_word_id, english_word_id, priority) VALUES ({$ru}, {$en}, 0) ON CONFLICT DO NOTHING");
        return response("Associate completed", 200);
    }

    public function detach(Request $request, $ru, $en)
    {
        DB::delete("DELETE FROM english_russian_relations WHERE russian_word_id = {$ru} AND english_word_id = {$en}");
        return response("Detach completed", 200);
    }
    public function search(Request $request)
    {
        $str = $request->get("string");
        $data = [];
        if(strlen($str)>2) {
            $data = DB::select("SELECT id, word FROM english_words WHERE word LIKE '{$str}%' LIMIT 10");
        }
        return response()->json($data);
    }

    public function info(Request $request)
    {

        $stats = DB::select("SELECT known, count(*) as num FROM russian_words GROUP BY known");

        $info = [
            9 => 0,
            8 => 0,
            7 => 0,
            6 => 0,
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0,
            0 => 0
        ];
        foreach ($stats as $stat){
            if($stat->known > 9){
                $info[9] += $stat->num;
            } else {
                $info[$stat->known] += $stat->num;
            }
        }
        return view('templates.statistic',[
            'info' => $info
        ]);
    }

}
