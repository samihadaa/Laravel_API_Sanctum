<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        return Product::all();
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:products',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);
       return Product::create($request->all());
    }
    public function show(Product $product){

        return $product;
    }
    public function destroy(Product $product){
        return $product->delete();
    }
    public function update(Request $request,Product $product){
        $product->update($request->all());
        return $product;
    }
    public function search($name){
        return Product::where('name','like','%'.$name.'%')->get();
    }
}
