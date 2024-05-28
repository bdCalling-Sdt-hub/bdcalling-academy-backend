<?php

namespace App\Rules;

use App\Models\Routine;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueDateTime implements ValidationRule
{
    protected $date;
    protected $time;

    public function __construct($date,$time)
    {
        $this->date = $date;
        $this->time = $time;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

    }

    public function passes($attribute, $value)
    {
        return !Routine::where('date', $this->date)
            ->where('time', $this->time)
            ->exists();
    }

    public function message()
    {
        return 'The combination of date and time must be unique.';
    }
}
