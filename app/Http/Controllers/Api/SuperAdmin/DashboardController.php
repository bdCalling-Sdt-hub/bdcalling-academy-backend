<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\AddStudent;
use App\Models\Trainer;
use DB;
class DashboardController extends Controller
{
    public function counting()
    {
        $total_income = Order::sum('amount');
        // Get today's date
        $today = Carbon::today();

        // Calculate daily income
        $daily_income = Order::whereDate('created_at', $today)
                             ->sum('amount');

        // Calculate weekly income
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();
        $weekly_income = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                              ->sum('amount');

        // Calculate monthly income
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $monthly_income = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                               ->sum('amount');

        //  Total student 
        $total_student = AddStudent::count();
        $running_student = AddStudent::where('status','enrolled')->count();
        $course_complet = AddStudent::where('status','complet')->count(); 
        $total_trainer =  Trainer::count();                                  

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_incom'=> $total_income,
                'daily_income' => $daily_income,
                'weekly_income' => $weekly_income,
                'monthly_income' => $monthly_income,
                'total_student' => $total_student,
                'running_student'=>$running_student,
                'complet_course' => $course_complet,
                'total_trainer'=>$total_trainer,
            ]
        ]);
    }

    public function averageMonthlyAdmissions(Request $request)
    {
        // Fetch and group data by month
        // $admissions = AddStudent::select(
        //     DB::raw('YEAR(created_at) as year'),
        //     DB::raw('MONTH(created_at "%M") as month'),
        //     DB::raw('COUNT(*) as count')
        // )
        // ->groupBy('year', 'month')
        // ->get();

        // // Calculate the average
        // $totalAdmissions = $admissions->sum('count');
        // $numberOfMonths = $admissions->count();
        // $averageAdmissions = $numberOfMonths ? $totalAdmissions / $numberOfMonths : 0;

        // return response()->json([
        //     'average_admissions' => $averageAdmissions,
        //     'total_admissions' => $totalAdmissions,
        //     'number_of_months' => $numberOfMonths,
        //     'monthly_data' => $admissions
        // ]);

 // Validate the input year
        // $request->validate([
        //     'year' => 'required|integer|min:1900|max:' . date('Y')
        // ]);

         // Get the input year or default to the current year
         $year = $request->input('year', date('Y'));

         // Validate the year
         $validatedYear = filter_var($year, FILTER_VALIDATE_INT, [
             'options' => [
                 'min_range' => 1900,
                 'max_range' => date('Y')
             ]
         ]);
 
         if ($validatedYear === false) {
             return response()->json(['error' => 'Invalid year provided.'], 400);
         }
 
         // Fetch and group data by month with month name for the specified or current year
         $admissions = AddStudent::select(
                 DB::raw('DATE_FORMAT(created_at, "%M") as month_name'),
                 DB::raw('COUNT(*) as count')
             )
             ->whereYear('created_at', $validatedYear)
             ->groupBy('month_name')
             ->orderBy(DB::raw('MONTH(created_at)'))
             ->get();
 
         // Calculate the average
         $totalAdmissions = $admissions->sum('count');
         $numberOfMonths = $admissions->count();
         $averageAdmissions = $numberOfMonths ? $totalAdmissions / $numberOfMonths : 0;
 
         return response()->json([
             'average_admissions' => $averageAdmissions,
             'total_admissions' => $totalAdmissions,
             'number_of_months' => $numberOfMonths,
             'monthly_data' => $admissions,
             'year' => $validatedYear
         ]);
    
    }


    // Wallet counting

    public function wallet_counting()
    {
        $total_income = Order::sum('amount');
        // Get today's date
        $today = Carbon::today();

        // Calculate daily income
        $daily_income = Order::whereDate('created_at', $today)
                             ->sum('amount');

        // Calculate weekly income
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();
        $weekly_income = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                              ->sum('amount');

        // Calculate monthly income
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $monthly_income = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                               ->sum('amount');

        //  Total student 
        $total_student = AddStudent::count();
        $running_student = AddStudent::where('status','enrolled')->count();
        $course_complet = AddStudent::where('status','complet')->count(); 
        $total_trainer =  Trainer::count();                                  

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_incom'=> $total_income,
                'daily_income' => $daily_income,
                'weekly_income' => $weekly_income,
                'monthly_income' => $monthly_income,
                'total_student' => $total_student,
                'running_student'=>$running_student,
                'complet_course' => $course_complet,
                'total_trainer'=>$total_trainer,
            ]
        ]);
    }
          
    
}
