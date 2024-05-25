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
            'password' => bcrypt($data['password']),
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

    public function updateTeacher(array $data, string $id)
    {
        DB::beginTransaction();

        try {
            // Find the teacher
            $teacher = Teacher::findOrFail($id);

            // Update the user
            $user = $teacher->user;
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                // Only update the password if provided
                'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
            ]);

            // Update the teacher
            $teacher->update([
                'course_category_id' => $data['course_category_id'],
                'phone_number' => $data['phone_number'],
                'designation' => $data['designation'],
                'expert' => $data['expert'],
                'status' => $data['status'],
            ]);

            // Commit the transaction
            DB::commit();

            return $teacher;

        } catch (Exception $e) {
            // Rollback the transaction and log the error
            DB::rollBack();
            Log::error('Error updating teacher: ' . $e->getMessage());
            throw $e;
        }
    }
}
