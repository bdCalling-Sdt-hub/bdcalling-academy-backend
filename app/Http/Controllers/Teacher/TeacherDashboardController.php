<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveRequest;
use App\Models\Attendance;
use App\Models\BatchStudent;
use App\Models\BatchTeacher;
use App\Models\CourseModule;
use App\Models\Payment;
use App\Services\LeaveService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function requestLeaveApplication(LeaveRequest $request)
    {
        try {
            $leave_request =  $this->leaveService->leaveRequest($request);
            return response()->json(['message' => 'Leave application submitted successfully.','data' => $leave_request],200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Leave application processed to fail' ,'error' => $e->getMessage()],500);
        }
    }

    public function showLeaveRequest()
    {
        try {
            $leave_request = $this->leaveService->showLeaveRequest();
            return response()->json(['message' => 'Leave application List', 'data' => $leave_request]);
        }catch (\Exception $e){
            return response()->json(['message' => 'You did dont apply for leave', 'error' => $e->getMessage()],500);
        }
    }

    public function teacherBaseStudent(Request $request)
    {
        $teacher_id = auth()->user()->teacher->id;
        return BatchTeacher::where('teacher_id',$teacher_id)->paginate(9);
    }

//    public function teacherDashboard(Request $request)
//    {
//        $teacher_id = auth()->user()->teacher->id;
//        $amount_per_class_or_per_month = auth()->user()->teacher;
//        $total_batch = BatchTeacher::where('teacher_id',$teacher_id)->count();
//        $total_amount = Payment::where('teacher_id',$teacher_id)->sum('amount');
//        $courses = BatchTeacher::with('batch.course')->get();
//        $course_id = $courses->pluck('batch.course.id');
//        return $total_class = CourseModule::count();
//        $total_student = BatchStudent::where('teacher_id',$teacher_id)->count();
//        $today_attendance = Attendance::where('teacher_id',$teacher_id)->where('date',date('Y-m-d'))->count();
//
//        return response()->json([
//            'total_batch' => $total_batch,
//            'total_amount' => $total_amount,
//            'total_class' => $total_class,
//            'total_student' => $total_student,
//            'amount_per_class_or_per_month' => $amount_per_class_or_per_month,
//            'total_attendance' => $today_attendance,
//        ]);
//
//    }
//    public function teacherDashboard(Request $request)
//    {
//        $teacher_id = auth()->user()->teacher->id;
//        $amount_per_class_or_per_month = auth()->user()->teacher;
//
//        // Calculate totals
//        $total_batch = BatchTeacher::where('teacher_id', $teacher_id)->count();
//        $total_amount = Payment::where('teacher_id', $teacher_id)->sum('amount');
//
//        // Fetch courses associated with the teacher
//        $courses = BatchTeacher::where('teacher_id', $teacher_id)
//            ->with('batch.course')
//            ->get();
//
//        // Pluck all course IDs
//        $course_ids = $courses->pluck('batch.course.id')->flatten()->unique();
//
//        // Count total classes based on the course IDs
//        $total_class = CourseModule::whereIn('course_id', $course_ids)->count();
//
//        // Pluck all batch IDs associated with the teacher
//        $batch_ids = $courses->pluck('batch.id')->flatten()->unique();
//
//        // Count total students based on the batch IDs
//        $total_student = BatchStudent::whereIn('batch_id', $batch_ids)->count();
//
//        $date = Carbon::today();
//        // Count today's attendance for the teacher
//        $today_attendance = Attendance::where('date', $date)
//            ->count();
//        $attendance = Attendance::get();
//
//        // Return response with calculated values
//        return response()->json([
//            'total_batch' => $total_batch,
//            'total_amount' => $total_amount,
//            'total_class' => $total_class,
//            'total_student' => $total_student,
//            'amount_per_class_or_per_month' => $amount_per_class_or_per_month,
//            'total_attendance' => $today_attendance,
//            'attendance' => $attendance,
//        ]);
//    }
//    public function teacherDashboard(Request $request)
//    {
//        $teacher_id = auth()->user()->teacher->id;
//        $amount_per_class_or_per_month = auth()->user()->teacher;
//
//        // Calculate totals
//        $total_batch = BatchTeacher::where('teacher_id', $teacher_id)->count();
//        $total_amount = Payment::where('teacher_id', $teacher_id)->sum('amount');
//
//        // Fetch courses associated with the teacher
//        $courses = BatchTeacher::where('teacher_id', $teacher_id)
//            ->with('batch.course')
//            ->get();
//
//        // Pluck all course IDs
//        $course_ids = $courses->pluck('batch.course.id')->flatten()->unique();
//
//        // Count total classes based on the course IDs
//        $total_class = CourseModule::whereIn('course_id', $course_ids)->count();
//
//        // Pluck all batch IDs associated with the teacher
//        $batch_ids = $courses->pluck('batch.id')->flatten()->unique();
//
//        // Count total students based on the batch IDs
//        $total_student = BatchStudent::whereIn('batch_id', $batch_ids)->count();
//
//        // Get the current date
//        $today = Carbon::today();
//
//        // Calculate attendance comparison for the last two weeks
//        $attendance_comparison = [];
//        foreach (['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
//            $this_week_start = $today->copy()->startOfWeek(Carbon::SATURDAY);
//            $last_week_start = $this_week_start->copy()->subWeek();
//
//            $this_week_attendance_count = Attendance::whereIn('batch_id', $batch_ids)
//                ->whereBetween('date', [$this_week_start->copy()->next($day), $this_week_start->copy()->next($day)->endOfDay()])
//                ->count();
//
//            $last_week_attendance_count = Attendance::whereIn('batch_id', $batch_ids)
//                ->whereBetween('date', [$last_week_start->copy()->next($day), $last_week_start->copy()->next($day)->endOfDay()])
//                ->count();
//
//            $total_students_this_day = BatchStudent::whereIn('batch_id', $batch_ids)->count();
//
//            $this_week_percentage = $total_students_this_day > 0 ? ($this_week_attendance_count / $total_students_this_day) * 100 : 0;
//            $last_week_percentage = $total_students_this_day > 0 ? ($last_week_attendance_count / $total_students_this_day) * 100 : 0;
//
//            $attendance_comparison[$day] = [
//                'this_week' => round($this_week_percentage, 2),
//                'last_week' => round($last_week_percentage, 2),
//            ];
//        }
//
//        // Count today's attendance for the teacher
//        $today_attendance = Attendance::where('date', $today)
//            ->count();
//
//        // Return response with calculated values
//        return response()->json([
//            'total_batch' => $total_batch,
//            'total_amount' => $total_amount,
//            'total_class' => $total_class,
//            'total_student' => $total_student,
//            'amount_per_class_or_per_month' => $amount_per_class_or_per_month,
//            'total_attendance' => $today_attendance,
//            'attendance_comparison' => $attendance_comparison,
//        ]);
//    }
    public function teacherDashboard(Request $request)
    {
        $teacher_id = auth()->user()->teacher->id;
        $amount_per_class_or_per_month = auth()->user()->teacher;

        // Calculate totals
        $total_batch = BatchTeacher::where('teacher_id', $teacher_id)->count();
        $total_amount = Payment::where('teacher_id', $teacher_id)->sum('amount');

        // Fetch courses associated with the teacher
        $courses = BatchTeacher::where('teacher_id', $teacher_id)
            ->with('batch.course')
            ->get();

        // Pluck all course IDs
        $course_ids = $courses->pluck('batch.course.id')->flatten()->unique();

        // Count total classes based on the course IDs
        $total_class = CourseModule::whereIn('course_id', $course_ids)->count();

        // Pluck all batch IDs associated with the teacher
        $batch_ids = $courses->pluck('batch.id')->flatten()->unique();

        // Count total students based on the batch IDs
        $total_student = BatchStudent::whereIn('batch_id', $batch_ids)->count();

        // Get the current date and start of the week
        $today = Carbon::today();
        $week_start = $today->copy()->startOfWeek(Carbon::SATURDAY);

        // Initialize counters for the total present and absent students for the week
        $total_present_this_week = 0;
        $total_absent_this_week = 0;

        // Calculate attendance ratio for each day of the current week
        $attendance_ratio = [];
        foreach (['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
            $day_start = $week_start->copy()->next($day);

            // Count present and absent students for the day
            $present_count = Attendance::whereIn('batch_id', $batch_ids)
                ->whereDate('date', $day_start)
                ->where('is_present', 1)
                ->count();

            $absent_count = Attendance::whereIn('batch_id', $batch_ids)
                ->whereDate('date', $day_start)
                ->where('is_present', 0)
                ->count();

            // Update the weekly totals
            $total_present_this_week += $present_count;
            $total_absent_this_week += $absent_count;

            // Calculate ratios
            $total_students = $present_count + $absent_count;
            $present_ratio = $total_students > 0 ? ($present_count / $total_students) * 100 : 0;
            $absent_ratio = $total_students > 0 ? ($absent_count / $total_students) * 100 : 0;

            // Store the day's attendance ratio
            $attendance_ratio[$day] = [
                'present' => round($present_ratio, 2),
                'absent' => round($absent_ratio, 2),
            ];
        }

        // Count today's attendance for the teacher
        $today_attendance = Attendance::where('date', $today)
            ->count();
        $attendance = Attendance::get();

        // Return response with calculated values
        return response()->json([
            'total_batch' => $total_batch,
            'total_amount' => $total_amount,
            'total_class' => $total_class,
            'total_student' => $total_student,
            'amount_per_class_or_per_month' => $amount_per_class_or_per_month,
            'total_attendance' => $today_attendance,
            'attendance_ratio' => $attendance_ratio,
            'total_present_this_week' => $total_present_this_week,
            'total_absent_this_week' => $total_absent_this_week,
        ]);
    }





}
