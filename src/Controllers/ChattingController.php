<?php

namespace AsayHome\AsayComponents\Controllers;

use AsayHome\AsayComponents\Models\AsayChattings;
use AsayHome\AsayComponents\Models\UserModel;
use Illuminate\Support\Facades\Request;

class ChattingController
{
    public function getMessages()
    {
        $messages = AsayChattings::where('group', Request::get('group'))
            ->where('group_id', Request::get('group_id'))
            ->get();
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
    public function getUsers()
    {
        $ids = AsayChattings::where('group', Request::get('group'))
            ->where('group_id', Request::get('group_id'))
            ->pluck('sender_id')->unique();
        $users = UserModel::whereIn('id', $ids)->get();
        return response([
            'success' => true,
            'users' => $users
        ]);
    }
    public function sendMessage()
    {
        Request::validate([
            'group' => 'required',
            'group_id' => 'required',
            'sender_id' => 'required',
            'message' => 'required',
            'atachments' => 'array',
            'atachments.*' => 'file',
        ], [], [
            'message' => __('Message')
        ]);
        AsayChattings::create([
            'group' => Request::get('group'),
            'group_id' => Request::get('group_id'),
            'sender_id' => Request::get('sender_id'),
            'receiver_id' => Request::get('receiver_id'),
            'message' => Request::get('message'),
            // 'atachments'
        ]);
        return back()->with(['msg' => __('Sent successfully')]);
    }
}
