<?php
namespace App\Http\Controllers;

use App\Models\Homeworks;
use App\Models\Rating;
use App\Models\Students;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class JournalController extends Controller
{
    public function index($accessToken, Students $studentsRepository, Homeworks $homeworksRepository)
    {
        $students = $studentsRepository->orderBy('first_name')->get();
        $homeworks = $homeworksRepository->where('course_id', 1)->orderBy('finish')->get();

        return view(
            'journal.index',
            [
                'students' => $students,
                'homeworks' => $homeworks,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }

    public function show($accessToken, Students $studentsRepository, Homeworks $homeworksRepository)
    {
        $students = $studentsRepository->orderBy('first_name')->get();
        $homeworks = $homeworksRepository->where('course_id', 1)->orderBy('finish')->get();

        return view(
            'journal.show',
            [
                'students' => $students,
                'homeworks' => $homeworks,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }

    public function view($accessKey, Students $studentsRepository, Homeworks $homeworksRepository, Rating $ratingRepository)
    {
        $student = $studentsRepository->where('access_key', $accessKey)->first();

        if ($student === null) {
            throw new NotFoundHttpException();
        }

        $sql = "SELECT h.id, h.is_homework, h.number, h.title, hs.rating, (h.finish > NOW()) is_finish
        FROM homeworks h
        LEFT JOIN homeworks_to_students hs ON hs.homework_id=h.id AND hs.student_id=?
        WHERE 1
        ORDER BY h.finish";

        $homeworks = \DB::select($sql, [$student->id]);

        $ready = $ratingRepository->where('student_id', $student->id)->count();

        $next = $homeworksRepository->whereRaw('finish > NOW()')->orderBy('finish')->first();

        return view(
            'journal.view',
            [
                'student' => $student,
                'homeworks' => $homeworks,
                'ready' => $ready,
                'next' => $next
            ]
        );
    }



    public function rating($accessToken, Request $request)
    {
        $rating = (int)$request->get('rating');
        $studentId = (int)$request->get('student');
        $homeworkId = (int)$request->get('homework');

        $entity = Rating::where('homework_id', $homeworkId)
            ->where('student_id', $studentId)
            ->first();

        if ($entity !== null && !empty($rating)) {
            $entity->rating = $rating;
            $entity->save();

            return 'update';
        }

        if ($entity === null && !empty($rating)) {
            $entity = new Rating();
            $entity->homework_id = $homeworkId;
            $entity->student_id = $studentId;
            $entity->rating = $rating;
            $entity->save();
            return 'create';
        }

        if ($entity !== null && empty($rating)) {
            $entity->delete();
            return 'delete';
        }


        return 'default';
    }

    /*
    protected function builder()
    {
        $studentsRepository = new Students();
        $homeworksRepository = new Homeworks();

        $students = $studentsRepository->all();

        $result = [0 => [null]];

        foreach ()
    }
    */
}