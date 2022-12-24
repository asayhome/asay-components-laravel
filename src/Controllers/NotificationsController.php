<?php

namespace AsayHome\AsayComponents\Controllers;

use Illuminate\Support\Facades\Request;
use Tik\AppSettings\Helpers\NotificationsHelper;
use Tik\AppSettings\Models\NotifyLogs;
use Yajra\DataTables\Facades\DataTables;

class NotificationsController
{

    public function getNotificationsLogs()
    {
        // $notify = NotifySender::where('group', Request::get('group'))
        //     ->where('group_id', Request::get('groupId'))
        //     ->first();
        // dd(Request::all());
        $logs = NotifyLogs::where('notify_id', Request::get('notifyId'))
            ->when((Request::get('notify_type') && Request::get('notify_type') != '*'), fn($query, $type) => $query->where('driver', Request::get('notify_type')))
            ->when(Request::get('status'), fn($query, $type) => $query->where('status', $type == 'success' ? 1 : 0))
            ->orderBy('id', 'desc');

        return DataTables::of($logs->orderBy('id', 'desc')->get())
            ->addColumn('id', function ($notifyLog) {
                return $notifyLog->id;
            })
            ->addColumn('resend_count', function ($notifyLog) {
                return '(' . $notifyLog->resend_count . ') out of (' . config('notifications-sender.number_of_retransmission_attempts', 5) . ') times';
            })
            ->addColumn('name', function ($notifyLog) {
                return $notifyLog->receiver->name;
            })
            ->addColumn('subject', function ($notifyLog) {
                return $notifyLog->notify->title;
            })
            ->addColumn('data', function ($notifyLog) {
                return json_decode($notifyLog->notify->data, true);
            })
            ->addColumn('notify_type', function ($notifyLog) {
                $type = $notifyLog->driver;
                if ($notifyLog->driver == 'database') {
                    $type = 'local';
                } else if ($notifyLog->driver == 'mail') {
                    $type = 'email';
                }
                return __(ucfirst($type));
            })
            ->addColumn('status', function ($notifyLog) {
                if ($notifyLog->status == NotificationsHelper::$notify_log_wait_status) {
                    return '<span style="color: blue">' . __('Waiting to send') . '</span>';
                } else if ($notifyLog->status == NotificationsHelper::$notify_log_sent_status) {
                    return '<span style="color: green">' . __('Success') . '</span>';
                } else {
                    return '<span style="color: red">' . __('Fail') . '</span>';
                }
            })
            ->addColumn('actions', function ($order) {
                $html = '';
                $html .= '<a href="javascript:void(0)" data-action="openDetails" class="btn btn-icon btn-sm btn-primary m-1"><i class="bi bi-eye" data-toggle="tooltip" data-placement="top" title="' . __('Show') . '"></i></a>';
                return $html;
            })
            ->rawColumns(['actions', 'resend_count', 'message', 'notify_type', 'status'])
            ->make(true);
    }
}
