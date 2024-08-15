<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseCategoryRequest;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RCategoryController extends Controller
{
    public function index(Request $request)
    {
        //
        $category_name = $request->search;
        $query = CourseCategory::query();

        // Apply filters if category is provided
        if ($category_name) {
            $query->where('name', 'like', '%' . $category_name . '%');
        }
        if ($request->filled('no_pagination')) {
            $category = $query->get();
            return response()->json([
                'message' => 'Categories',
                'data' => $category,
            ]);
        }
        $category = $query->paginate(9);
        return response()->json([
            'message' => 'Categories',
            'data' => $category,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        $category = new CourseCategory();
        $category->category_name = $request->category_name;
        $category->save();
        return response()->json([
            'message' => 'Category added Successfully',
            'data' => $category
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request,string $id)
    {
        $category = CourseCategory::where('id', $id)->first();
        if ($category) {
            $validator = Validator::make($request->all(), [
                'category_name' => 'string|min:2',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $category->category_name = $request->category_name ?? $category->category_name;
            $category->update();
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category,
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found',
                'data' => []
            ]);
        }

    }

    public function destroy(string $id)
    {
        //
        $category = CourseCategory::where('id', $id)->first();
        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully',
            ],200);
        }
        return response()->json([
            'message' => 'Category Not Found',
        ],404);
    }
}
