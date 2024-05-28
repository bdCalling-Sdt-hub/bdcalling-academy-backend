<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncludeCost;
use App\Http\Requests\IncludeCostRequest;
class IncludeCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $cost = IncludeCost::orderBy('id', 'desc')->paginate(10);
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
    public function store(IncludeCostRequest $request)
    {
        $reson = $request->reason;
        $cost = $request->cost;       
        
        foreach($reson as $key => $item)
        {
            $input['reason'] = $item;
            $input['cost'] = $cost[$key];           
        
            IncludeCost::create($input);        
        }
        return response()->json(['status'=>'success','message'=>'Include cost add Successfully ']);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cost = IncludeCost::where('id', $id)->orderBy('id', 'desc')->first();
        if($cost){
           return response()->json(['status'=>'success', 'data'=>$cost], 201);
        }else{
           return response()->json(['status'=>'error', 'message'=>'record not found'],402);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncludeCostRequest $request, string $id)
    {
         $update_cost = IncludeCost::find($request->id);
         $update_cost->reason = $request->reason ?? $update_cost->reason;
         $update_cost->cost = $request->cost ?? $update_cost->cost;
         $update_cost->save();
         if($update_cost){
            return response()->json(['status'=>'success', 'data'=>$update_cost]);
         }else{
            return response()->json(['status'=>'error', 'message'=>'Record not found']);
         }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
