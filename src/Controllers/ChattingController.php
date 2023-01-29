<?php

namespace AsayHome\AsayComponents\Controllers;

use AsayHome\AsayComponents\Models\AsayChattings;
use AsayHome\AsayComponents\Models\UserModel;
use Illuminate\Support\Facades\Request;

class ChattingController
{
    public function getMessages()
    {
        $messages = AsayChattings::with(['sender'])->where('group', Request::get('group'))
            ->where('group_id', Request::get('group_id'))
            ->get()->map(function ($message) {
                $message->created_time = $message->created_at->format('Y-m-d H:i:s');
                return $message;
            });
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


        $message = AsayChattings::create([
            'group' => Request::get('group'),
            'group_id' => Request::get('group_id'),
            'sender_id' => Request::get('sender_id'),
            'receiver_id' => Request::get('receiver_id'),
            'message' => Request::get('message'),
            // 'atachments'
        ]);

        if (Request::hasFile('attachments')) {
            $i = 0;
            $paths = [];
            foreach (Request::file('attachments') as $file) {
                $paths[$i] = $file->store(Request::get('group') . '/' . Request::get('group_id'), 'public');
                $i++;
            }
            $message->attachments = json_encode($paths);
            $message->save();
        }

        return back()->with(['msg' => __('Sent successfully')]);
    }
}
