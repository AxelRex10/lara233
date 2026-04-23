<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ctrlDatos extends Controller
// Poblacion de categoria junto con productos con 7 categorias, cada categoria tiene que tener 30 productos
{
    public function AccesoDatos(){
        $categorias = category::with('products')->get();

        return view('vistadatos')->with(compact('categorias'));
    }

    public function AccesoDatosLink(){
        $enlace = Http::get('https://jsonplaceholder.typicode.com/posts');
        $traductorjson = $enlace->json();
        Return view ('vistadatosLink')->with(compact('traductorjson'));
        
    }

        public function AccesoDatosOtro(){
        $enlace = Http::get('https://holisss.mundoiti.com');
        $traductorjson = $enlace->json();
        Return view ('vistadatosotra')->with(compact('traductorjson'));
        
    }

    public function SitioWeb(){
        $urlSitio = Http::get('https://axelbienjson.netlify.app/Json/hamburguesas.json');
        $traductorsitio = $urlSitio->json();
        Return view ('vistaSitio')->with(compact('traductorsitio'));
    }

    public function SitioWebDetalle($id){
        $urlSitio = Http::get('https://axelbienjson.netlify.app/Json/hamburguesas.json');
        $traductorsitio = $urlSitio->json();

        $detalle = collect($traductorsitio)->firstWhere('id', (int) $id);

        return view('vistaSitioDetalle')->with(compact('detalle'));
    }

    public function AccesoCategory(){
        $categorias = category::all();

        return view('vistaCategor')->with(compact('categorias'));
    }

    public function AccesoProductos(){
        $productos = Product::with('category')->get();

        return view('vistaProduct')->with(compact('productos'));
    }
}
