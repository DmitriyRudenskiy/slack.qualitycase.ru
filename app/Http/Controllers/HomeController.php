<?php
namespace App\Http\Controllers;

use App\Models\Members;
use App\Models\Messages;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
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

    public function view($accessToken, $userId, Members $members, Messages $messages)
    {
        $user = $members->where("id", $userId)
            ->where("is_master", false)
            ->first();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        // список пользователей
        $users = $members->where("is_master", false)->get();

        // список сообщений
        $message = $messages
            ->with("member")
            ->where("channel_member_id", $user->id)
            ->orderBy("added_on")
            ->paginate(5);

        return view(
            'home.view',
            [
                'user' => $user,
                'users' => $users,
                'message' => $message,
                'accessToken' => env('ACCESS_TOKEN')
            ]
        );
    }
}
