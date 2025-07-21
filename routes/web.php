<?php

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


