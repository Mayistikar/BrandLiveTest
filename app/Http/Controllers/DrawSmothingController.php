<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DrawSmothingController extends Controller
{
    private $combs = array();

    public function combinate(Request $request)
    {   
        $chars = $request->letters;
        $size = $request->charNumber;
        $just_words = $request->justWords;
        echo $just_words;
        echo json_encode($request->all());        
        $this->getCombinations($chars, $size);
        $combinations = $this->combs;        
        return view('draw-smothing', compact('combinations'));
    }

    function getCombinations($characters, $word_size)
    {
        $characters_array = str_split($characters);        
        $characters_size = sizeof($characters_array);        
        return $this->combinationUtil($characters_array, array(), 0, $characters_size - 1, 0, $word_size);
    }

    private function combinationUtil($arr, $data, $start, $end, $index,  $word_size)
    {
        if ($index == $word_size)
        {
            array_push($this->combs, implode("", $data));
            return; 
        }
        for ($i = $start; $i <= $end && $end - $i + 1 >= $word_size - $index; $i++) 
        {
            $data[$index] = $arr[$i];
            $this->combinationUtil($arr, $data, $i + 1, $end, $index + 1, $word_size);
        }
    }
}
