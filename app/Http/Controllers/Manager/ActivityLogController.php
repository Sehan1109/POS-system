<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // Get IDs of all cashier users
        $cashierIds = User::where('role', 'cashier')->pluck('id');

        $query = ActivityLog::with('user')
            ->where(function ($q) use ($cashierIds) {
                // Rule 1: Product and Sale related activities
                $q->whereIn('model_type', ['Product', 'Sale']);

                // Rule 2: All activities performed by cashiers
                $q->orWhereIn('user_id', $cashierIds);
            })
            // Exclude manager login/logout: skip login/logout entries made by managers
            ->where(function ($q) {
                $managerIds = User::where('role', 'manager')->pluck('id');
                $q->whereNotIn('action', ['login', 'logout'])
                  ->orWhereNotIn('user_id', $managerIds);
            })
            ->latest();

        // Optional filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->paginate(30)->withQueryString();

        // Users visible in the filter dropdown: cashiers + any user who touched products
        $users = User::whereIn('role', ['cashier', 'admin', 'manager'])->orderBy('name')->get();

        // Actions visible to managers
        $actions = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'login'   => 'Login',
            'logout'  => 'Logout',
        ];

        return view('manager.activity-logs.index', compact('activityLogs', 'users', 'actions'));
    }

    public function show(ActivityLog $activityLog)
    {
        $cashierIds = User::where('role', 'cashier')->pluck('id');
        $managerIds = User::where('role', 'manager')->pluck('id');

        $isProductLog  = $activityLog->model_type === 'Product';
        $isCashierLog  = $cashierIds->contains($activityLog->user_id);
        $isManagerAuth = in_array($activityLog->action, ['login', 'logout'])
                         && $managerIds->contains($activityLog->user_id);

        if ((!$isProductLog && !$isCashierLog) || $isManagerAuth) {
            abort(403, 'Access denied.');
        }

        $activityLog->load('user');
        return view('manager.activity-logs.show', compact('activityLog'));
    }
}