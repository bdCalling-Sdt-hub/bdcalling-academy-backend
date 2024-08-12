<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
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
        $total_student = Student::count();
        $running_student = BatchStudent::where('status','enrolled')->count();
        $course_complet = BatchStudent::where('status','completed')->count();
        $total_trainer =  Teacher::count();

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_income'=> $total_income,
                'daily_income' => $daily_income,
                'weekly_income' => $weekly_income,
                'monthly_income' => $monthly_income,
                'total_student' => $total_student,
                'running_student'=>$running_student,
                'complete_course' => $course_complet,
                'total_trainer'=>$total_trainer,
            ]
        ]);
    }

    public function averageMonthlyAdmissions(Request $request)
    {
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
         $admissions = Student::select(
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
        $total_student = Student::count();
        $running_student = BatchStudent::where('status','enrolled')->count();
        $course_complete = BatchStudent::where('status','complete')->count();
        $total_trainer =  Teacher::count();

        // Return the response as JSON
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_income'=> $total_income,
                'daily_income' => $daily_income,
                'weekly_income' => $weekly_income,
                'monthly_income' => $monthly_income,
                'total_student' => $total_student,
                'running_student'=>$running_student,
                'complete_course' => $course_complete,
                'total_teacher'=>$total_trainer,
            ]
        ]);
    }


}
