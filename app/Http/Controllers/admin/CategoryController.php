<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('created_at','ASC')->paginate(10);

        return view('admin.categories.list',[
            'categories' => $categories
        ]);
    }

    public function createCategory() {
        $categories = Category::orderBy('name','ASC')->where('status',1)->get();

        return view('admin.categories.create', [
            'categories' => $categories
        ]);
    }

    public function saveCategory(Request $request) {
        $rules = [
            'name' => 'required|min:5|max:200',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->save();

            session()->flash('success','Categoría añadida satisfactoriamente');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function deleteCategory(Request $request) {
        $category = Category::where([
            'id' => $request->id
        ])->first();

        if ($category == null) {
            session()->flash('error','Categoría eliminada o no encontrada');
            return response()->json([
                'status' => true
            ]);
        }

        Category::where('id', $request->id)->delete();
        session()->flash('success','Categoría eliminada satisfactoriamente');
            return response()->json([
                'status' => true
            ]);
    }
}
