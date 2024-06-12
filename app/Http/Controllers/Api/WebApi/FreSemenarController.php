<?php

namespace App\Http\Controllers\Api\WebApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreeSemenar;
use App\Http\Requests\FreddSemenarRequest;
use Validator;
use App\Models\subscribe;
class FreSemenarController extends Controller
{
    public function store(FreddSemenarRequest $request)
    {
      
        $free_semenar = new FreeSemenar();
        $free_semenar->name = $request->name;
        $free_semenar->email = $request->email;
        $free_semenar->phone = $request->phone;
        $free_semenar->address = $request->address;
        $free_semenar->category = $request->category;
        $free_semenar->save();
        if($free_semenar){
            return response()->json(['status'=>'success', 'message'=>'successfully join you free semenar'], 200);
        }else{
            return response()->json(['status'=>'error', 'message'=>'Faile join you free semenar'], 401);
        }
    }

    public function show_semenar()
    {
        $free_semenar =  FreeSemenar::all();
        
        if($free_semenar){
            return response()->json(['status'=>'success', 'data'=>$free_semenar], 200);
        }else{
            return response()->json(['status'=>'error', 'data'=>'Data not found'], 401);
        }
    }

    public function destroy($id)
    {
        $free_semenar =  FreeSemenar::find($id);
        if($free_semenar){
            $free_semenar->delete();
            return response()->json(['message'=>'Remove your semenar attend'],401);
        }else{
            return response()->json(['message'=>'Record not found'],401);
        }
    }


    // ---------- Subscriptions ----------------- //

    public function subscrib_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email'
        ]);
      
        $subscriber = new subscribe();
        $subscriber->email = $request->email;

        $subscriber->save();
        if($subscriber){
            return response()->json(['status'=>'success', 'message'=>'successfully your subscriptins'], 200);
        }else{
            return response()->json(['status'=>'error', 'message'=>'Faile add your subscribe'], 401);
        }
    }

    public function show_subscriber()
    {
        $free_semenar =  subscribe::all();
        
        if($free_semenar){
            return response()->json(['status'=>'success', 'data'=>$free_semenar], 200);
        }else{
            return response()->json(['status'=>'error', 'data'=>'Data not found'], 401);
        }
    }

    public function destroy_subscriber($id)
    {
        $free_semenar =  subscribe::find($id);
        if($free_semenar){
            $free_semenar->delete();
            return response()->json(['message'=>'Remove your subscriber attend'],401);
        }else{
            return response()->json(['message'=>'Record not found'],401);
        }
    }

}
