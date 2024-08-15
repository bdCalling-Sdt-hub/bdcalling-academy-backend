<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncludeCost;
use App\Http\Requests\IncludeCostRequest;
class IncludeCostController extends Controller
{

    public function index(Request $request)
    {
        $query = IncludeCost::query();
        if ($request->filled('date')) {
            $query->whereDate('created_at',$request->date);
        }
        $costs = $query->paginate(10);
        return response()->json($costs);
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $include_cost = new IncludeCost();
        $include_cost->costing = $request->costing;
        $include_cost->save();
        return response()->json(['message' => 'Cost added successfully.'], 200);
    }

    public function show(string $id)
    {
        $cost = IncludeCost::where('id', $id)->orderBy('id', 'desc')->first();
        if($cost){
           return response()->json(['status'=>'success', 'data'=>$cost], 201);
        }else{
           return response()->json(['status'=>'error', 'message'=>'record not found'],402);
        }
    }

    public function edit(string $id)
    {

    }

    public function update(IncludeCostRequest $request, string $id)
    {
         $update_cost = IncludeCost::find($request->id);
         $update_cost->costing = $request->costing ?? $update_cost->costing;
         $update_cost->save();
         if($update_cost){
            return response()->json(['status'=>'success', 'data'=>$update_cost]);
         }else{
            return response()->json(['status'=>'error', 'message'=>'Record not found']);
         }
    }

    public function destroy(string $id)
    {
        //
    }
}
