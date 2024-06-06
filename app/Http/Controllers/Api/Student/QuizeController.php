<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quize;
use App\Http\Requests\QuizeRequest;
class QuizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $quizes = Quize::all();
        if($quizes){
            return response()->json(['data'=>$quizes],200);
        }
        return response()->json(['Record not found'],402);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuizeRequest $request)
    {
        $validatedData = $request->validated(); // Using validated data from QuizeRequest
        $quize = Quize::create($validatedData);

        if ($quize) {
            return response()->json(['data' => $quize], 201); // 201 for created
        }

        return response()->json(['message' => 'Record not created'], 500); // 500 for server error
    }

    public function show(string $id)
    {
        $quize = Quize::findOrFail($id);
        if($quize){
            return response()->json(['data'=>$quize],200);
        }
        return response()->json(['Record not found'],402);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(QuizeRequest $request, string $id)
    {
        $update_quize = Quize::find($request->id);
        $update_quize->course_module_id = $request->course_module_id ??  $update_quize->course_module_id;
        $update_quize->questions = $request->questions ??  $update_quize->questions;
        $update_quize->currect_ans = $request->currect_ans ??  $update_quize->currect_ans;
        $update_quize->opt_1 = $request->opt_1 ??  $update_quize->opt_1;
        $update_quize->opt_2 = $request->opt_2 ??  $update_quize->opt_2;
        $update_quize->opt_3 = $request->opt_3 ??  $update_quize->opt_3;
        $update_quize->opt_4 = $request->opt_4 ??  $update_quize->opt_4;
        $update_quize->mark = $request->mark ??  $update_quize->mark;
        $update_quize->save();
        if($update_quize){
            return response()->json(['status'=>'success', 'data'=>$update_quize], 200);
        }

        return response()->json(['status'=>false, 'message'=>'Internal server error'], 500);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quize = Quize::findOrFail($id);
        $quize->delete();

       if($quize){
        return response()->json(['message'=>'Quize delete successfully'], 200);
       }
       return response()->json(['message'=>'Quize deleted faile'], 402);
    }
}
