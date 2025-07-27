<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;


class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('activity-logs.index', compact('logs'));
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $logs = ActivityLog::with('user')
            ->where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                ->orWhere('description', 'LIKE', "%{$search}%")
                ->orWhere('ip_address', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();
        
        return response()->json($logs);
    }
}
