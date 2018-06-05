<?php
namespace App\Http\Controllers;

use App\Models\Homeworks;
use App\Models\Members;
use App\Models\Students;
use Illuminate\Routing\Controller;

class JournalController extends Controller
{

    /**
     * @param Members $members
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Members $members)
    {
        $users = $members->where("is_master", false)->get();

        return view(
            'home.index',
            [
                'users' => $users,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }

    public function view($accessKey, Students $studentsRepository, Homeworks $homeworksRepository)
    {


        $student = $studentsRepository->where('access_key', $accessKey)->first();
        $homeworks = $homeworksRepository->orderBy('finish')->get();

        $sql = "SELECT h.is_homework, h.number, h.title, hs.rating, (h.finish > NOW()) is_finish
        FROM homeworks h
        LEFT JOIN homeworks_to_students hs ON hs.homework_id=h.id AND hs.student_id=?
        WHERE 1
        ORDER BY h.finish";

        $homeworks = \DB::select($sql, [$student->id]);

        return view(
            'journal.view',
            [
                'student' => $student,
                'homeworks' => $homeworks
            ]
        );
    }
}
