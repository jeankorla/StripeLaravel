<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all(); // Busca todos os produtos
        return view('products.index', compact('products'));
    }

}
