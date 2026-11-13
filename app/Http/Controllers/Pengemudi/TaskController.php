<?php
namespace App\Http\Controllers\Pengemudi;
use App\Http\Controllers\Controller;
use App\Models\VehicleAssignment;
use App\Models\VehicleUsageLog;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = VehicleAssignment::with(["vehicle","request","assigner"])
            ->where("driver_user_id", auth()->id())->latest()->paginate(15);
        return view("pengemudi.tasks.index", compact("tasks"));
    }
    public function show(VehicleAssignment $task)
    {
        if ($task->driver_user_id !== auth()->id()) abort(403);
        $task->load(["vehicle","request","assigner","logs.recorder"]);
        return view("pengemudi.tasks.show", compact("task"));
    }
    public function confirm(VehicleAssignment $task)
    {
        if ($task->driver_user_id !== auth()->id()) abort(403);
        if ($task->status !== "assigned") return back()->with("error","Status tidak valid.");
        $task->update(["status"=>"confirmed"]);
        ActivityLogger::log("driver.assignment.confirmed", $task, "Pengemudi konfirmasi");
        return back()->with("success","Penugasan dikonfirmasi.");
    }
    public function depart(Request $r, VehicleAssignment $task)
    {
        if ($task->driver_user_id !== auth()->id()) abort(403);
        if ($task->status !== "confirmed") return back()->with("error","Konfirmasi dulu.");
        $r->validate(["odometer_start"=>"required|numeric|min:0"]);
        $task->update(["status"=>"departed","actual_start_at"=>now(),"odometer_start"=>$r->odometer_start]);
        VehicleUsageLog::create(["vehicle_assignment_id"=>$task->id,"recorded_by_user_id"=>auth()->id(),"event_type"=>"departure","happened_at"=>now(),"odometer"=>$r->odometer_start]);
        $task->vehicle->update(["status"=>"in_use"]);
        ActivityLogger::log("driver.assignment.departed", $task, "Km awal: ".$r->odometer_start);
        return back()->with("success","Perjalanan dimulai.");
    }
    public function returnTrip(Request $r, VehicleAssignment $task)
    {
        if ($task->driver_user_id !== auth()->id()) abort(403);
        if ($task->status !== "departed") return back()->with("error","Belum berangkat.");
        $r->validate(["odometer_end"=>"required|numeric|min:".($task->odometer_start ?? 0),"return_notes"=>"nullable|string"]);
        $task->update(["status"=>"returned","actual_end_at"=>now(),"odometer_end"=>$r->odometer_end,"return_notes"=>$r->return_notes]);
        VehicleUsageLog::create(["vehicle_assignment_id"=>$task->id,"recorded_by_user_id"=>auth()->id(),"event_type"=>"return","happened_at"=>now(),"odometer"=>$r->odometer_end,"note"=>$r->return_notes]);
        $task->vehicle->update(["status"=>"available"]);
        ActivityLogger::log("driver.assignment.returned", $task, "Km akhir: ".$r->odometer_end);
        return back()->with("success","Perjalanan selesai.");
    }
}
