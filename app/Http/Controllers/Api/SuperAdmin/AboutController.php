<?php

namespace App\Http\Controllers\Api\SuperAdmin;
use App\Http\Requests\AboutRequest;
use App\Http\Requests\PrivactytRequest;
use App\Http\Requests\TermstRequest;
use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function show_privacy()
    {
        $privacy = DB::table('abouts')->select('privacy')->where('id', 2)->first();
        if ($privacy) {
            return response()->json([
                'status' => 'success',
                'data' => $privacy
            ], 200);
        } else {
            return response()->json([
                'status' => 'data not found',
                'data' => $privacy
            ], 500);
        }
    }

    public function privacyPolicy(PrivactytRequest $request)
    {

        $update_privacy = About::find($request->id);
        $update_privacy->privacy = $request->privacy;
        $update_privacy->save();
        if ($update_privacy) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'internal server error',
                'data' => $update_privacy
            ], 500);
        }
    }

    // =================== ABOUT =====================//

    public function show_about()
    {
        $about = DB::table('abouts')->select('about')->first();
        if ($about) {
            return response()->json([
                'status' => 'success',
                'data' => $about
            ], 200);
        } else {
            return response()->json([
                'status' => 'data not found',
                'data' => $about
            ], 500);
        }
    }

    public function updateAbout(AboutRequest $request)
    {
        $update_about = About::find($request->id);
        $update_about->about = $request->about;
        $update_about->save();
        if ($update_about) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'internal server error',
                'data' => 'About does not exist',
            ], 500);
        }
    }

    // =================== TERMS & CONDITIONS ================== //

    public function show_terms()
    {
        $terms = DB::table('abouts')->select('terms_condition')->where('id',3)->first();
        if ($terms) {
            return response()->json([
                'status' => 'success',
                'data' => $terms
            ], 200);
        } else {
            return response()->json([
                'status' => 'data not found',
                'data' => $terms
            ], 500);
        }
    }

    public function terms_condition(TermstRequest $request)
    {
        $update_terms = About::find($request->id);
        $update_terms->terms_condition = $request->terms_condition;
        $update_terms->save();
        if ($update_terms) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'internal server error',
                'data' => 'About update successfully'
            ], 500);
        }
    }
}
