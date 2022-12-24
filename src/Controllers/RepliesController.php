<?php

namespace AsayHome\AsayComponents\Controllers;

use AsayHome\AsayComponents\Models\AsayReplies;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class RepliesController
{
    public function getReplies()
    {
        $messages = AsayReplies::paginate(3, ['*'], 'page', Request::get('pageNo'));
        return response([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function store()
    {
        $roles = [
            'title' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make(Request::all(), $roles, [
            '*.required' => __('This field is required'),
        ]);


        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($errors) {
                return $errors[0];
            })->toArray();
            return response()->json(['success' => false, 'errors' => $errors]);
        }

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

        return response()->json(['success' => true, 'msg' => __('Saved successfully')]);
    }

    public function destroy()
    {
        // dd(Request::get('id'));
        if (Request::get('id')) {
            $reply = AsayReplies::where('id', Request::get('id'))->first();
            if ($reply) {
                $reply->delete();
            }
        }
        return response()->json(['success' => true, 'msg' => __('Deleted successfully')]);
    }
}
