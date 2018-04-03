<?php
namespace App\Http\Controllers;

use App\Models\Members;
use App\Models\Messages;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index(Members $members)
    {
        $users = $members->where("chanel_id", "<>", null)->get();

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
        $user = $members->where("id", $userId)->first();
        $users = $members->where("chanel_id", "<>", null)->get();
        $message = $messages->where("user_id", $userId)->orderBy("added_on")->paginate(5);

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
