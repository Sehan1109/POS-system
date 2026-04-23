<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of cashier-related activity logs.
     */
    public function index(Request $request)
    {
        // Define cashier-relevant action types
        $cashierActions = [
            'login' => 'Cashier Login',
            'logout' => 'Cashier Logout',
            'sale_created' => 'Sale Created',
            'user_created' => 'Cashier User Created',
            'user_deleted' => 'Cashier User Deleted',
        ];

        $query = ActivityLog::with('user')
            ->whereIn('action', ['login', 'logout', 'sale_created', 'user_created', 'user_deleted'])
            ->latest();
        
        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Filter by user (cashier only)
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activityLogs = $query->paginate(20);
        
        // Get only cashier users for the filter dropdown
        $users = User::where('role', 'cashier')->get();
        
        // Cashier-specific actions for filter dropdown
        $actions = $cashierActions;
        
        return view('manager.activity-logs.index', compact('activityLogs', 'users', 'actions'));
    }
    
    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        // Ensure only cashier-related logs can be viewed
        $cashierActions = ['login', 'logout', 'sale_created', 'user_created', 'user_deleted'];
        
        if (!in_array($activityLog->action, $cashierActions)) {
            abort(403, 'Access denied. Only cashier-related logs can be viewed.');
        }
        
        $activityLog->load('user');
        return view('manager.activity-logs.show', compact('activityLog'));
    }
}