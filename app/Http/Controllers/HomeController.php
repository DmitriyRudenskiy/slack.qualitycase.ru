<?php
namespace App\Http\Controllers;

use App\Models\Members;
use App\Models\Messages;
use App\Models\Students;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    public function index(Students $studentsRepository)
    {
        $users = $studentsRepository->where("is_master", false)->get();

        return view(
            'home.index',
            [
                'users' => $users,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }

    public function view($accessToken, $userId, Students $studentsRepository, Messages $messages)
    {
        $user = $studentsRepository->where("id", $userId)
            ->where("is_master", false)
            ->first();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        // список пользователей
        $users = $studentsRepository->where("is_master", false)->get();

        $master = $studentsRepository->where("is_master", true)->first();

        // список сообщений
        $message = $messages
            ->with("students")
            ->where("channel_member_id", $user->id)
            ->orderBy("added_on")
            ->paginate(5);

        return view(
            'home.view',
            [
                'user' => $user,
                'users' => $users,
                'message' => $message,
                'master' => $master,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }
}
