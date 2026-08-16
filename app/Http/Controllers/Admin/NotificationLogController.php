<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    public function index()
    {
        $logs = NotificationLog::with('booking')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.settings.notification_logs', compact('logs'));
    }
}
