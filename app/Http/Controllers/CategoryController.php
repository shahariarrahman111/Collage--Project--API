<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Exception;

class CategoryController
{
    
    public function CreateCategory(Request $request){

        try{

            $request->validate([

                "name"=> "required|string|max:255",
                "description"=> "required|string|max:500"

            ]);

            //   $this->validateCategory($request); //Another  style validation

            // Anther way category save..........

            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->save();

            return response()->json([
                "status"=> "success",
                "message"=> "Catgory created succesfully!",
                "data"=> $category
               
            ]);

        }catch(Exception $e){
            return response()->json([
                "status"=> "error",
                "message"=> $e->getMessage()
            ]);
        }

    }


    public function ShowCategory($id){

        try{

            $category = Category::findOrFail($id);
            return response()->json([
                "status"=> "success",
                "data"=> $category
            ]);

        }catch(Exception $e){
            return response()->json([
                "status"=> "error",
                "message"=> $e->getMessage()
                ]);
        }



    }



    public function UpdateCategory(Request $request, $id){

        try{
            
            $request->validate([

                "name"=> "required|string|max:255",
                "description"=> "required|string|max:500"

            ]);


           
            $category = Category::findOrFail($id);
            $category->name = $request->input('name');
            $category->name = $request->input('description');
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully!',
                'data' => $category
            ]);
            
            }catch(Exception $e){
                return response()->json([
                    'status'=> 'error',
                    'message'=> $e->getMessage()
                    ]);
            }  

    }


    public function DeleteCategory(Request $request, $id){

        try{

            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'status'=> 'success',
                'message'=> 'Category deleted successfully!'
                ]);


        }catch(Exception $e){

            return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage()
                ]);

        }


    }



    public function CategoryList()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'status' => 'success',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> 'error',
                'message'=> $e->getMessage()
                ]);
        }
    }



}
