<?php

namespace AsayHome\AsayComponents\Controllers;

use AsayHome\AsayComponents\Models\AsayChattings;
use AsayHome\AsayComponents\Models\AsayReplies;
use AsayHome\AsayComponents\Models\UserModel;
use Illuminate\Support\Facades\Request;

class RepliesController
{
    public function getReplies()
    {
        $messages = AsayReplies::all();
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
    public function store()
    {
        Request::validate([
            'title' => 'required',
            'content' => 'required',
        ], [], [
            'title' => __('Title'),
            'content' => __('Content')
        ]);
        if (Request::get('id')) {
            AsayReplies::where('id', Request::get('id'))->update([
                'title' => Request::get('title'),
                'content' => Request::get('content'),
            ]);
        } else {
            AsayReplies::create([
                'title' => Request::get('title'),
                'content' => Request::get('content'),
            ]);
        }

        return back()->with(['msg' => __('Sent successfully')]);
    }
    public function destroy()
    {


        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
