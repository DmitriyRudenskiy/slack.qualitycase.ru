<?php

Route::get('/', function () {
    $users = \App\Models\Members::where("chanel_id", "<>", null)->get();

    return view('hello', ['users' => $users]);
});

Route::get('/{userId}', function ($userId) {
    $user = \App\Models\Members::where("id", $userId)->first();
    $users = \App\Models\Members::where("chanel_id", "<>", null)->get();
    $message = \App\Models\Messages::where("user_id", $userId)->orderBy("added_on")->get();

    return view(
        'hello',
        [
            'user' => $user,
            'users' => $users,
            'message' => $message
        ]
    );
});