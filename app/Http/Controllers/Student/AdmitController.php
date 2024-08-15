<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmitRequest;
use App\Http\Requests\DropoutRequest;
use App\Http\Requests\SuccessfulStudentRequest;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Student;
use Illuminate\Http\Request;

class AdmitController extends Controller
{
//    public function admitStudent(AdmitRequest $request)
//    {
//
//        $student_id = $request->student_id;
//        $student = Student::find($student_id);
//        if (empty($student))
//        {
//            return response()->json(['message' => 'Student Does Not Exist', 'data' => $student]);
//        }
//        $check_student_in_same_batch = BatchStudent::where('student_id',$student->id)->where('batch_id',$request->batch_id)->first();
//        if ($check_student_in_same_batch)
//        {
//            return response()->json(['message' => 'Student already enrolled'],409);
//        }
//        $batch_student = new BatchStudent();
//        $batch_student->batch_id = $request->batch_id;
//        $batch_student->student_id = $student->id;
//        $batch_student->status = 'enrolled';
//        $batch_student->save();
//        return response()->json(['message' => 'Assign Batch To Student Successfully' , 'data' => $batch_student]);
//    }

    public function admitStudent(AdmitRequest $request)
    {
        $student_id = $request->student_id;
        $student = Student::find($student_id);

        if (empty($student)) {
            return response()->json(['message' => 'Student Does Not Exist', 'data' => $student]);
        }

        // Check if the student is already enrolled in the same batch
        $check_student_in_same_batch = BatchStudent::where('student_id', $student->id)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($check_student_in_same_batch) {
            return response()->json(['message' => 'Student already enrolled'], 409);
        }

        // Update student data
        $student->category_id = $request->category_id ?? $student->category_id;
        $student->status = $request->status ?? $student->status;
        $student->phone_number = $request->phone_number ?? $student->phone_number;
        $student->gender = $request->gender ?? $student->gender;
        $student->religion = $request->religion ?? $student->religion;
        $student->registration_date = $request->registration_date ?? $student->registration_date;
        $student->dob = $request->dob ?? $student->dob;
        $student->blood_group = $request->blood_group ?? $student->blood_group;
        $student->address = $request->address ??  $student->address;
        $student->student_type = $request->student_type ?? $student->student_type;
        if ($request->file('image')) {
            if (!empty($student->image)) {
                removeImage($student->image);
            }
            $student->image = saveImage($request,'image');
        }

        $student->save();

        // Assign batch to student
        $batch_student = new BatchStudent();
        $batch_student->batch_id = $request->batch_id;
        $batch_student->student_id = $student->id;
        $batch_student->status = 'enrolled';
        $batch_student->save();

        return response()->json(['message' => 'Assign Batch To Student Successfully', 'data' => $batch_student]);
    }

    public function showAdmitStudentV2(Request $request)
    {
        $registration_date = $request->filled('registration_date');
        $name = $request->filled('name');
        $phone_number = $request->filled('phone_number');
        $category_name = $request->filled('category_name');
        $batch_id = $request->filled('batch_id');
        $query = Batch::with(['students.user','students.order', 'course.course_category'])
            ->has('students');

        if ($registration_date) {
            $query->whereHas('students', function ($query) use ($registration_date) {
                $query->where('registration_date', $registration_date);
            });
        }

        if ($name) {
            $query->whereHas('students.user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        if ($phone_number) {
            $query->whereHas('students', function ($query) use ($phone_number) {
                $query->where('phone_number', 'like', '%' . $phone_number . '%');
            });
        }

        if ($category_name) {
            $query->whereHas('course.category', function ($query) use ($category_name) {
                $query->where('category_name', 'like', '%' . $category_name . '%');
            });
        }

        if ($batch_id) {
            $query->where('id', 'like', '%' . $batch_id . '%');
//            $query->where('id',$batch_id);
        }

        return $query->paginate(12);
    }

    public function showAdmitStudent(Request $request)
    {
        // Get the list of dropout students
        $dropout_student = BatchStudent::where('status', 'enrolled')->get();

        // Extract the student IDs of the dropout students
        $dropout_student_ids = $dropout_student->pluck('student_id')->toArray();

        // Filter the student list to include only those who are in the dropout list
        $query = Batch::with(['students.user', 'students.order', 'course.course_category'])->where('batch_id', 'like', '%' . 'BCA' . '%')
            ->whereHas('students', function ($query) use ($dropout_student_ids) {
                $query->whereIn('student_id', $dropout_student_ids);
            })
            ->has('students');

        if ($request->filled('name')) {
            $query->whereHas('students.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('phone_number',$request->input('phone_number'));
            });
        }

        if ($request->filled('registration_date')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('registration_date', $request->input('registration_date'));
            });
        }

        if ($request->filled('category_name')) {
            $query->whereHas('course.category', function($q) use ($request) {
                $q->where('category_name', $request->input('category_name'));
            });
        }

        if ($request->filled('batch_id')) {

            $query->where('batch_id', $request->input('batch_id'));
        }

        return $query->paginate(12);
//        $query = Batch::with(['students.user','students.order', 'course.course_category'])
//            ->has('students');
//
//        if ($request->filled('name')) {
//            $query->whereHas('students.user', function($q) use ($request) {
//                $q->where('name', 'like', '%' . $request->input('name') . '%');
//            });
//        }
//
//        if ($request->filled('phone_number')) {
//            $query->whereHas('students', function($q) use ($request) {
//                $q->where('phone_number',$request->input('phone_number'));
//            });
//        }
//
//        if ($request->filled('registration_date')) {
//            $query->whereHas('students', function($q) use ($request) {
//                $q->where('registration_date', $request->input('registration_date'));
//            });
//        }
//
//        if ($request->filled('category_name')) {
//            $query->whereHas('course.category', function($q) use ($request) {
//                $q->where('category_name', $request->input('category_name'));
//            });
//        }
//
//        if ($request->filled('batch_id')) {
//
//            $query->where('batch_id', $request->input('batch_id'));
//        }
//
//        return $query->paginate(12);
    }

    public function dropOutStudent(DropoutRequest $request)
    {
        $student_id = $request->student_id;
        $batch_id = $request->batch_id;
        $student = BatchStudent::where('student_id',$student_id)->where('batch_id',$batch_id)->first();

        if (empty($student))
        {
            return response()->json(['message' => 'Student Does Not Exist'],404);
        }
        $student->status = 'dropout';
        $student->update();
        return response()->json(['message' => 'Student is dropout successfully'],200);
    }
//        public function showDropOutStudent(Request $request)
//        {
//           $dropout_student = BatchStudent::where('status','dropout')->get();
//
//            $student_list = Batch::with(['students.user','students.order', 'course.course_category'])
//                ->has('students')->get();
//
//            return response()->json([
//                'student_list' => $student_list,
//                'dropout_student' => $dropout_student,
//            ]);
//
//
//        }
    public function showDropOutStudent(Request $request)
    {
        // Get the list of dropout students
        $dropout_student = BatchStudent::whereIn('status',['dropout','refunded'])->get();

        // Extract the student IDs of the dropout students
        $dropout_student_ids = $dropout_student->pluck('student_id')->toArray();

        // Filter the student list to include only those who are in the dropout list
        $query = Batch::with(['students.user', 'students.order', 'course.course_category'])
            ->whereHas('students', function ($query) use ($dropout_student_ids) {
                $query->whereIn('student_id', $dropout_student_ids);
            })
            ->has('students');

        if ($request->filled('name')) {
            $query->whereHas('students.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('phone_number',$request->input('phone_number'));
            });
        }

        if ($request->filled('registration_date')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('registration_date', $request->input('registration_date'));
            });
        }

        if ($request->filled('category_name')) {
            $query->whereHas('course.category', function($q) use ($request) {
                $q->where('category_name', $request->input('category_name'));
            });
        }

        if ($request->filled('batch_id')) {

            $query->where('batch_id', $request->input('batch_id'));
        }

        return $query->paginate(12);
    }

    public function completedStudent(SuccessfulStudentRequest $request)
    {
        $student_ids =json_decode( $request->student_ids);  // Expecting an array of student IDs
        $batch_id = $request->batch_id;

        // Check if the students exist in the batch
        $students = BatchStudent::whereIn('student_id', $student_ids)
            ->where('batch_id', $batch_id)
            ->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'Students Do Not Exist'], 404);
        }

        // Update the status for each student
        foreach ($students as $student) {
            $student->status = 'completed';
            $student->update();
        }

        return response()->json(['message' => 'Students have been marked as Completed successfully'], 200);
    }


    public function showSuccessfulStudent(Request $request)
    {
        // Get the list of dropout students
        $completed_student = BatchStudent::where('status', 'completed')->get();

        // Extract the student IDs of the dropout students
        $dropout_student_ids = $completed_student->pluck('student_id')->toArray();

        // Filter the student list to include only those who are in the dropout list
        $query = Batch::with(['students.user', 'students.order', 'course.course_category'])
            ->whereHas('students', function ($query) use ($dropout_student_ids) {
                $query->whereIn('student_id', $dropout_student_ids);
            })
            ->has('students');

        if ($request->filled('name')) {
            $query->whereHas('students.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('phone_number',$request->input('phone_number'));
            });
        }

        if ($request->filled('registration_date')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('registration_date', $request->input('registration_date'));
            });
        }

        if ($request->filled('category_name')) {
            $query->whereHas('course.category', function($q) use ($request) {
                $q->where('category_name', $request->input('category_name'));
            });
        }

        if ($request->filled('batch_id')) {

            $query->where('batch_id', $request->input('batch_id'));
        }

        return $query->paginate(12);
    }


}
