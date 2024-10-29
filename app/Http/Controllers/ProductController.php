<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Exception;

class ProductController
{
    

    public function CreateProduct(Request $request)
    {

        try{

            $request->validate([

                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'price' => 'required|numeric|min:1',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
                'image' => 'required|string|max:500'

            ]);


            $product = Product::create($request->all());
          


            return response()->json([
                'status'=> 'success',
                'message'=> 'Product created successfully!',
                'data' => $product
            ]);


        }catch(Exception $e){
 return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage()
            ]);
        }    


    }


    public function ShowProduct($id)
    {

        try {
            $product = Product::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }


    }



    public function Updateproduct(Request $request, $id)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'price' => 'required|numeric|min:1',
                'category_id' => 'required|exists:categories,id',
                'stock' => 'required|integer|min:0',
                'image' => 'required|string|max:500'
            ]);

            $product = Product::findOrFail($id);
            $product->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully!',
                'data' => $product
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }


    }


    public function DeleteProduct($id)
    {

        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }


    }



    public function ProductList()
    {

        try {
            $products = Product::with('category')->get();

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }


    }



}
