<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
class ProductController extends Controller
{
   
    public function index()
    {
        $categoryNames = $this->getcategorynames();
        $products = $this->getinfoproducts();
        return view('users/products', compact('categoryNames', 'products'));
    }
    
    protected function getcategorynames()
    {
        $categorynames = Category::pluck('category_name', 'id');
        return ( $categorynames );
    }
    
    protected function getinfoproducts()
    {
        $products = Product::all();
        return $products;
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        
        return view('users/product-detail', compact('product'));
    }
    
}