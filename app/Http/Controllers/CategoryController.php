<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Categories::paginate(10);
        return response()->json($categories, 200);
    }

    public function show($id)
    {
        $category = Categories::find($id);
        if($category) {
            return response()->json($category, 200);
        }else{
            return response()->json("Category not found", 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'=> 'required|unique:categories,name',
                'image'=> 'required'
            ]);

            $category = new Categories();

            $path = "assets/uploads/category" . $category->image;
            if(File::exists($path)) {
                File::delete($path);
            }

            $file = $request->file("image");
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;

            try {
                $file->move('assets/uploads/category', $filename);
            }catch (Exception $e) {
                return response()->json("Error : " . $e, 500);
            }

            $category->name = $request->name;
            $category->image = $request->image;
            $category->save();
            return response()->json('Category added', 201);
        }catch(Exception $e){
            return response()->json($e, 500);
        }
    }

    public function update_category($id, Request $request)
    {
        try{
            $validated = $request->validate([
                'name'=> 'required|unique:categories,name',
                'image'=> 'required'
            ]);

            $category = Categories::find($id);
            if($request->hasFile('image')) {
                $path = 'assets/uploads/category/' . $category->image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;

                try {
                    $file->move('assets/uploads/category', $filename);
                }catch(Exception $e) {
                    return response()->json("Error : " . $e, 500);
                }
                $category->image = $filename;
            }

            $category->name = $request->name;
            $category->update();

            return response()->json('Category updated', 200);
        }catch(Exception $e){
            return response()->json($e, 500);
        }
    }

    public function delete_category($id)
    {
        $category = Categories::find($id);
        if($category) {
            $category->delete();
            return response()->json('Category Deleted');
        }else{
            return response()->json("Category not found");
        }
    }
}
