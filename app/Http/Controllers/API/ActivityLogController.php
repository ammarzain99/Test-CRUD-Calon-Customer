<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    public function index(Request $req)
    {
        $query = ActivityLog::with('user');

        if ($req->search) {
            $query->where(function ($q) use ($req) {
                $q->where('action', 'like', "%{$req->search}%")
                ->orWhere('model', 'like', "%{$req->search}%")
                ->orWhereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$req->search}%")
                );
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();

        if ($req->wantsJson()) {
            return ActivityLog::collection($logs);
        }

        return Inertia::render('ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $req->only('search')
        ]);
    }
}
