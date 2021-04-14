<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DrawSmothingController extends Controller
{
    private $combs = array();

    public function combinate(Request $request)
    {   
        $chars = $request->letters;
        $size = $request->charNumber;
        $just_words = $request->justWords;
        // echo $just_words;
        // echo json_encode($request->all());        
        // $this->getCombinations($chars, $size);
        // $combinations = $this->combs;
        $combinations = $this->getPermutations($chars, $size);
        echo json_encode($combinations);

        $this->getRealWords(implode(" ",$this->combs));

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


    function getPermutations($characters, $word_size)
    {
        $characters_array = str_split($characters);        
        $characters_size = sizeof($characters_array);        
        return $this->permutationUtil($characters_array);
    }

    function permutationUtil($input)
    {
        $miarray = array();
        $cadena="";
        //copio el array
        $temporal=$input;
        //borro el primer numero del array
         
         
        array_shift($input);
        //ahora la cuenta esta en que solo quedan 3
        for($u=0;$u<count($temporal);$u++)
        {
            for($i=0;$i<count($input);$i++)
            { 
                array_push($input,$input[0]);
                array_shift($input);
                for($e=0;$e<count($input);$e++)
                {
                    $cadena.=$input[$e];
                }
                array_push($miarray,$temporal[$u].$cadena);
                array_push($miarray,$temporal[$u].strrev($cadena));
                $cadena="";
            }
            array_shift($input);
            array_push($input,$temporal[$u]);            
        }
        return $miarray;
    }

    private function getRealWords($words)
    {
        $HTTPresponse = Http::post('https://language.googleapis.com/v1/documents:analyzeSyntax?key=', [
            'encodingType' => 'UTF8',
            'document' => [
                "type" => "PLAIN_TEXT",
                "language" => "es",        
                "content" => $words
            ],
        ]);
        $response = json_decode($HTTPresponse,true);
        $real_words = array();
        foreach ($response["tokens"] as &$token) {
            // echo $token["text"]["content"];
            // echo $token["partOfSpeech"]["aspect"];
            // echo "\n";
            if (strcmp($token["partOfSpeech"]["aspect"], "ASPECT_UNKNOWN") <> 0 ) {
                array_push($real_words, $token["text"]["content"]);
            }
        }
        echo json_encode($real_words);
        return $real_words;
    }
}
