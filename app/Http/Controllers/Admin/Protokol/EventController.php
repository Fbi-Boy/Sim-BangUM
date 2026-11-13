<?php
namespace App\Http\Controllers\Admin\Protokol;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAgenda;
use App\Models\EventTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index() {
        $events = Event::with("coordinator")->latest()->paginate(15);
        return view("admin.protokol.events.index", compact("events"));
    }
    public function create() { return view("admin.protokol.events.create"); }
    public function store(Request $r) {
        $d = $r->validate([
            "title"=>"required|string|max:220",
            "event_type"=>"required|string|max:40",
            "starts_at"=>"required|date","ends_at"=>"required|date|after:starts_at",
            "location"=>"nullable|string|max:255","description"=>"nullable|string",
        ]);
        $year = now()->year;
        $seq = (Event::whereYear("created_at",$year)->count() ?? 0) + 1;
        $d["event_number"] = "EVT-{$year}-".str_pad($seq,4,"0",STR_PAD_LEFT);
        $d["coordinator_user_id"] = auth()->id();
        $d["status"] = "planned";
        $event = Event::create($d);
        return redirect()->route("admin.protokol.events.show", $event)->with("success","Kegiatan dibuat.");
    }
    public function show(Event $event) {
        $event->load(["agendas","tasks.assignee"]);
        $users = User::orderBy("name")->get();
        return view("admin.protokol.events.show", compact("event","users"));
    }
    public function storeAgenda(Request $r, Event $event) {
        $d = $r->validate([
            "agenda_title"=>"required|string|max:180",
            "starts_at"=>"required|date","ends_at"=>"required|date|after:starts_at",
            "person_in_charge"=>"nullable|string|max:180","notes"=>"nullable|string",
        ]);
        $d["event_id"] = $event->id;
        $d["sequence_number"] = ($event->agendas()->max("sequence_number") ?? 0) + 1;
        EventAgenda::create($d);
        return back()->with("success","Agenda ditambahkan.");
    }
    public function storeTask(Request $r, Event $event) {
        $d = $r->validate([
            "task_title"=>"required|string|max:180",
            "assigned_to_user_id"=>"nullable|exists:users,id",
            "due_at"=>"nullable|date","priority"=>"required|in:low,normal,high,urgent",
        ]);
        $d["event_id"] = $event->id;
        $d["status"] = "open";
        EventTask::create($d);
        return back()->with("success","Tugas ditambahkan.");
    }
    public function completeTask(EventTask $task) {
        $task->update(["status"=>"completed","completed_at"=>now()]);
        return back()->with("success","Tugas selesai.");
    }
}
