<?php
namespace App\Http\Controllers\Auditor;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\IncomingLetter;
use App\Models\OfficialTravelRequest;
use App\Models\OutgoingLetter;
use App\Models\RoomBooking;
use App\Models\StockRequest;
use App\Models\VehicleRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportViewerController extends Controller
{
    public function index()
    {
        return view("auditor.reports.index");
    }

    public function correspondence(Request $r)
    {
        $incoming = IncomingLetter::latest("received_at")->paginate(20, ["*"], "in_page")->withQueryString();
        $outgoing = OutgoingLetter::latest()->paginate(20, ["*"], "out_page")->withQueryString();
        return view("auditor.reports.correspondence", compact("incoming","outgoing"));
    }

    public function loans()
    {
        $loans = AssetLoan::with(["asset","requester"])->latest()->paginate(20);
        return view("auditor.reports.loans", compact("loans"));
    }

    public function stock()
    {
        $requests = StockRequest::with(["requester","items.supplyItem"])->latest()->paginate(20);
        return view("auditor.reports.stock", compact("requests"));
    }

    public function vehicles()
    {
        $requests = VehicleRequest::with(["vehicle","requester"])->latest()->paginate(20);
        return view("auditor.reports.vehicles", compact("requests"));
    }

    public function bookings()
    {
        $bookings = RoomBooking::with(["room","requester"])->latest()->paginate(20);
        return view("auditor.reports.bookings", compact("bookings"));
    }

    public function travel()
    {
        $requests = OfficialTravelRequest::with("requester")->latest()->paginate(20);
        return view("auditor.reports.travel", compact("requests"));
    }

    public function exportCsv(string $type): StreamedResponse
    {
        $filename = "laporan-{$type}-".now()->format("Ymd-His").".csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
        ];
        return response()->streamDownload(function () use ($type) {
            $out = fopen("php://output", "w");
            fputs($out, "\xEF\xBB\xBF");
            if ($type === "asset-loans") {
                fputcsv($out, ["No Pinjaman","Aset","Pemohon","Mulai","Kembali","Status"]);
                AssetLoan::with(["asset","requester"])->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) fputcsv($out, [$r->loan_number, $r->asset->name ?? "-", $r->requester->name ?? "-", $r->starts_at?->format("Y-m-d"), $r->expected_return_at?->format("Y-m-d"), $r->status]);
                });
            } elseif ($type === "incoming-letters") {
                fputcsv($out, ["Agenda","Nomor","Pengirim","Perihal","Tanggal","Status"]);
                IncomingLetter::chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) fputcsv($out, [$r->register_year."/".str_pad($r->agenda_sequence,4,"0",STR_PAD_LEFT), $r->external_letter_number, $r->sender_name, $r->subject, $r->letter_date?->format("Y-m-d"), $r->status]);
                });
            } elseif ($type === "vehicle-requests") {
                fputcsv($out, ["No","Pemohon","Tujuan","Berangkat","Status"]);
                VehicleRequest::with(["requester","vehicle"])->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) fputcsv($out, [$r->request_number, $r->requester->name, $r->destination, $r->starts_at?->format("Y-m-d H:i"), $r->status]);
                });
            } elseif ($type === "stock-requests") {
                fputcsv($out, ["No","Pemohon","Item","Qty","Status"]);
                StockRequest::with(["requester","items.supplyItem"])->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) foreach ($r->items as $it) fputcsv($out, [$r->request_number, $r->requester->name, $it->supplyItem->name ?? "-", $it->requested_quantity, $r->status]);
                });
            } elseif ($type === "travel-requests") {
                fputcsv($out, ["No","Pemohon","Tujuan","Berangkat","Status"]);
                OfficialTravelRequest::with("requester")->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $r) fputcsv($out, [$r->request_number, $r->requester->name, $r->destination, $r->departure_at?->format("Y-m-d H:i"), $r->status]);
                });
            }
            fclose($out);
        }, $filename, $headers);
    }
}
