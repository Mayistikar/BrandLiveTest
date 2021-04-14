<?php
namespace App\Http\Controllers;
ini_set('max_execution_time', 300);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DrawSmothingController extends Controller
{
    private $pemutations = array();    

    public function combinate(Request $request)
    {   
        $chars = $request->letters;
        $size = $request->charNumber;
        $check_real_words = $request->justWords ?? "";

        $errors = array();
        if (strlen($chars) > 7 || strlen($chars) < 3) {
            array_push($errors, "La longitud de caractéres ingresados es inválida!");
            return view('draw-smothing', compact('errors'));
        }

        if ($size < 3 || strlen($chars) < $size) {
            array_push($errors, "El número de caracteres no corresponde con la longitud de los mismos!");
            return view('draw-smothing', compact('errors'));
        }

        $just_words = $request->justWords;
        $this->getPermutations($chars, $size);

        $combinations = array();
        if (strcmp($check_real_words, "on") <> 0 ) {
            $combinations = $this->pemutations;
            return view('draw-smothing', compact('combinations'));
        }

        $combinations = $this->getRealWords(implode(" ",$this->pemutations));
        return view('draw-smothing', compact('combinations'));
    }

    function getPermutations($characters, $word_size)
    {        
        $characters_array = str_split($characters);        
        $characters_size = sizeof($characters_array);        
        return $this->permutar($characters_array, $word_size);
    }

    function permutar($elementos, $word_size, $concatena=""){        
        $num = count($elementos);        
        if ($num > 2){
            $array_resultado = array();
            foreach($elementos as $posicion => $actual){
                $extraidos = $elementos;
                array_splice($extraidos, $posicion, 1);
                $perm2 = $this->permutar($extraidos, $word_size, $concatena);                
                foreach($perm2 as $valor){   
                    if (strlen($actual.$concatena.$valor) == $word_size) {                               
                        $this->pemutations[] = $actual.$concatena.$valor;                   
                    }
                    if (strlen($actual.$concatena.$valor) <= $word_size) {
                        $array_resultado[] = $actual.$concatena.$valor;
                    }                    
                }
            }
            return $array_resultado;
        } else if ($num == 2){
            return array($elementos[0].$concatena.$elementos[1], 
                         $elementos[1].$concatena.$elementos[0]);
        } else if ($num == 1){
            return array($elementos[0]);        
        } else {
           return array();
        }
    } 

    private function getRealWords($words)
    {
        $GOOGLE_KEY = env("GOOGLE_KEY", "");        
        $HTTPresponse = Http::post('https://language.googleapis.com/v1/documents:analyzeSyntax?key='.$GOOGLE_KEY, [
            'encodingType' => 'UTF8',
            'document' => [
                "type" => "PLAIN_TEXT",
                "language" => "es",        
                "content" => $words
            ],
        ]);
        $response = json_decode($HTTPresponse,true);
        // echo json_encode($response);
        $real_words = array();
        foreach ($response["tokens"] as &$token) {
            if (strcmp($token["partOfSpeech"]["aspect"], "ASPECT_UNKNOWN") <> 0 ) {
                array_push($real_words, $token["text"]["content"]);
            }
        }
        return $real_words;
    }
}
