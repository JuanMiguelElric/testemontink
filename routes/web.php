<?php

use App\Http\Controllers\Cupom\CupomController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\Product\ProductController;
use App\Models\Produto;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $produtos = Produto::all();
    return view('home', compact('produtos'));
})->name("home");

Route::resource('produtos', ProductController::class)->only(["index","store","update","show","destroy"])->missing(function(){
    return route("produtos.index");
});

Route::resource('cupons', CupomController::class)->only(['index','store','update','show','destroy'])->missing(function(){

});

Route::get("/cupon/json",[CupomController::class,"CuponsJson"])->name("cupom.json");

Route::post("/cupons/verificar",[CupomController::class,"verificarCupom"]);

Route::post("/pedidos",[PedidoController::class,'store']);


