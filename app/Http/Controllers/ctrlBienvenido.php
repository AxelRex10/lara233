<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ctrlBienvenido extends Controller
{
    public function Bienvenidos(){
        Return view('welcome');
    }
    public function Suma(){
        Return (3+3);
    }

    public function DatosSuma($n1){
        // Obtener datos del url
        Return ($n1);
    }

    public function SumaDos($n1,$n2){
        Return ("El resultado de la suma es: ".$n1+$n2);
    }
    public function Suma3($n1,$n2){

        // Mostrar datos en la vista Welcome
        $datos = $n1 + $n2;
        $resultado = "El resultado de la suma es: ".$datos;
        return view ('welcome', compact('n1','n2','datos'));
    }
}
