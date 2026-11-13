<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $r)
    {
        $logs = ActivityLog::with("actor")
            ->when($r->filled("action"), fn($q) => $q->where("action", "like", "%".$r->action."%"))
            ->when($r->filled("user"), fn($q) => $q->whereHas("actor", fn($u) => $u->where("name","like","%".$r->user."%")))
            ->latest("created_at")->paginate(30)->withQueryString();
        return view("super-admin.activity-log.index", compact("logs"));
    }
}
