<?php

namespace AsayHome\AsayComponents\Controllers;

use App\Helpers\PermissionsHelper;
use AsayHome\AsayComponents\Models\UserModel;
use AsayHome\AsayHelpers\Helpers\AlertsHelper;
use AsayHome\AsayHelpers\Helpers\TimestampHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Tik\AppSettings\Helpers\NotificationsHelper;
use Tik\AppSettings\Models\NotifySender;

class AlertsController
{
    public function getConfig()
    {
        $drivers = [
            'database', // will be transformed to local in translation
            'mail',
            'sms',
            'oneSignal'
        ];
        return response()->json([
            'props' => [
                'locale' => app()->getLocale(),
                'appName' => 'Rtosh',
                'timezone' => config('app.timezone')
            ],
            'roles' => PermissionsHelper::getRoles(),
            'drivers' => $drivers,
            'account_statuses' => [],
            'message_statuses' => [AlertsHelper::$message_statuses_wait, AlertsHelper::$message_statuses_sent],
        ]);
    }
    public function getAlerts()
    {
        $sender = UserModel::where('id', Request::get('user_id'))->first();
        if (in_array($sender->id, config('asay-components.admin_users_ids'))) {
            $ids = NotifySender::where('group', Request::get('group'))
                ->where('group_id', Request::get('group_id'))
                ->orderBy('id', 'desc')
                ->cursor()->filter(function ($message) use ($sender) {
                    return ($message->sender_id == $sender->id ||
                        in_array($sender->id, json_decode($message->receiver_ids, true)));
                })->pluck('id');
        } else {
            $ids = NotifySender::where('group', Request::get('group'))
                ->where('group_id', request('group_id'))
                ->where('status',  NotificationsHelper::$notify_sent_status)
                ->orderBy('id', 'desc')
                ->cursor()->filter(function ($message) use ($sender) {
                    return ($message->sender_id == $sender->id ||
                        in_array($sender->id, json_decode($message->receiver_ids, true)));
                })->pluck('id');
        }

        $messages = NotifySender::whereIn('id', $ids->toArray())
            ->orderBy('id', 'desc')
            ->paginate(3, ['*'], 'page', Request::get('pageNo'))
            ->through(function ($message) {
                $message->created_time = TimestampHelper::getLocaledTimestamp($message->created_at)->diffForHumans();
                $message->sending_at = TimestampHelper::getLocaledTimestamp($message->sending_time)->format(('Y-m-d H:i:s'));
                $message->remaining_time = TimestampHelper::getRemainingTime($message->sending_time);
                return $message;
            });

        return response()->json([
            'messages' => $messages,
        ]);
    }
    public function getReceivers()
    {
        $users = config('asay-components.userModelInstance');
        $receivers = $users::when(Request::get('receiversIds') && Request::get('receiversIds') != '*' && !in_array('*', Request::get('receiversIds')), function ($query) {
            $query->whereIn('id', Request::get('receiversIds'));
        })->when(Request::get('selected_roles'), function ($query) {
            $query->whereHas('roles', function ($role) {
                if (is_array(Request::get('selected_roles'))) {
                    $role->whereIn('name', Request::get('selected_roles'));
                } else {
                    $role->where('name', Request::get('selected_roles'));
                }
            });
        })->select(['id', DB::raw('concat(first_name," ",last_name) as name')])->get();
        return response([
            'success' => true,
            'receivers' => $receivers
        ]);
    }
    public function makeAlertMessageAsRead()
    {
        $notify = NotifySender::where('id', Request::get('id'))->first();
        if ($notify) {
            $notify->status = NotificationsHelper::$notify_sent_status;
            $notify->read_at = date('Y-m-d H:i:s');
            $notify->save();
        }
        return response([
            'success' => true,
            'msg' => __('Saved successfully')
        ]);
    }
    public function deleteAlertMessage()
    {
        $notify = NotifySender::where('id', Request::get('id'))->first();
        if ($notify) {
            $notify->delete();
        }
        return response([
            'success' => true,
            'msg' => __('Deleted successfully')
        ]);
    }
    public function sendAlert()
    {
        $roles = [
            'title' => 'required',
            'message' => 'required|string|min:1',
            'sending_by' => 'required',
            'sendingType' => 'required',
            'received_by' => 'required|array',
        ];


        if (Request::get('sendingType') == 'later') {
            $roles['sending_time'] = 'required';
        }
        if (
            is_array(Request::get('received_by')) &&
            in_array('*', Request::get('received_by')) &&
            Request::get('group') == 'users'
        ) {
            $roles['selected_roles'] = 'required';
            // $roles['statuses'] = 'required';
        }


        $validator = Validator::make(Request::all(), $roles, [
            '*.required' => __('This field is required'),
        ]);


        if ($validator->fails()) {
            $errors = collect($validator->errors())->map(function ($errors) {
                return $errors[0];
            })->toArray();
            return response()->json(['success' => false, 'errors' => $errors]);
        }


        if (Request::get('sendingType') == 'later') {
            $sending_time = Request::get('sending_time');
        } else {
            $sending_time = date('Y-m-d H:i:s');
        }


        if (!in_array('*', Request::get('received_by'))) {
            $received_by = Request::get('received_by');
        } else {
            if (Request::get('group') == 'users') {
                $users = config('asay-components.userModelInstance');
                $query =  $users::where('id', '<>', Request::get('sender_id'));
                // if (is_array(Request::get('statuses')) && sizeof(Request::get('statuses')) > 0) {
                //     $query->whereIn('account_status', Request::get('statuses'));
                // }
                if (is_array(Request::get('selected_roles')) && sizeof(Request::get('selected_roles')) > 0) {
                    $query->whereHas('roles', function ($role) {
                        $role->whereIn('name', Request::get('selected_roles'));
                    });
                }
                $received_by = $query->pluck('id')->toArray();
            } else {
                $received_by = UserModel::whereIn('id', Request::get('receiversIds'))->pluck('id')->toArray();
            }
        }




        if (Request::get('action') == 'getCount') {
            return response()->json(['success' => true, 'count' => sizeof($received_by)]);
        }
        $isSend = false;
        if (sizeof($received_by)) {
            $data = [
                'group' => Request::get('group'),
                'group_id' => Request::get('group_id'),
                'sender_id' =>  Request::get('sender_id'), // admin user
                'receiver_ids' => $received_by,
                'subject' => Request::get('title'),
                'body' =>  Request::get('message'),
                'drivers' => Request::get('sending_by'),
                'template' => 'general',
                'sending_time' => $sending_time,
                // aditional params
                'additional_params' => [],
            ];
            NotificationsHelper::registerNotify($data);
            $isSend = true;
            $msg = __('Saved, transmission will complete at specified time');
        } else {
            $msg = __('Alert receivers not selected');
        }

        return response()->json([
            'success' => $isSend,
            'msg' =>    $msg
        ]);
    }
}
