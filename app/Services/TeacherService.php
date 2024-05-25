<?php
namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TeacherService
{
    public function createTeacher(array $data)
    {
        DB::beginTransaction();

        try {
        // Create the user
        $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']), // Hash the password
        'otp' => 0,
        'role' => 'TEACHER',
        ]);

        // Create the teacher
        $teacher = Teacher::create([
        'user_id' => $user->id,
        'course_category_id' => $data['course_category_id'],
        'phone_number' => $data['phone_number'],
        'designation' => $data['designation'],
        'expert' => $data['expert'],
        'created_by' => 'super admin add', // Assuming you're using authentication
        'status' => 'active',
        ]);

        // Commit the transaction
        DB::commit();

        return $teacher;

        } catch (Exception $e) {
        // Rollback the transaction and log the error
        DB::rollBack();
        Log::error('Error creating teacher: ' . $e->getMessage());
        throw $e;
        }
    }
}
