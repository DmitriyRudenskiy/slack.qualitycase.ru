<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homeworks extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'homeworks';

    public function rating($studentId)
    {

        $tmp = Rating::where('homework_id', $this->id)
            ->where('student_id', $studentId)
            ->first();


        if ($tmp !== null) {
            return (int)$tmp->rating;
        }

        return null;
    }
}


