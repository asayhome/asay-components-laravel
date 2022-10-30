<?php

namespace AsayHome\AsayComponents\Controllers;

use AsayHome\AsayComponents\Models\AsayChattings;
use Illuminate\Support\Facades\Request;

class ChattingController
{
    public function getMessages()
    {
        $messages = AsayChattings::where('group', Request::ge('group'))
            ->where('group_id', Request::get('group_id'))
            ->get();
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
    public function getUsers()
    {
        $messages = AsayChattings::where('group', Request::ge('group'))
            ->where('group_id', Request::get('group_id'))
            ->get();
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
    public function sendMessage()
    {
        $messages = AsayChattings::where('group', Request::ge('group'))
            ->where('group_id', Request::get('group_id'))
            ->get();
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
