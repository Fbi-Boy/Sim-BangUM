<?php
namespace Database\Seeders;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetLoan;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\Event;
use App\Models\EventAgenda;
use App\Models\EventTask;
use App\Models\IncomingLetter;
use App\Models\LetterDisposition;
use App\Models\OfficialTravelRequest;
use App\Models\OrganizationalUnit;
use App\Models\OutgoingLetter;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\StockRequest;
use App\Models\StockRequestItem;
use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Creating roles...");
        $roles = [
            ["code" => "super_admin", "name" => "Super Admin"],
            ["code" => "kepala_bagian", "name" => "Kepala Bagian"],
            ["code" => "kasubbag", "name" => "Kasubbag"],
            ["code" => "pegawai", "name" => "Pegawai"],
            ["code" => "auditor", "name" => "Auditor"],
        ];
        foreach ($roles as $r) Role::firstOrCreate(["code" => $r["code"]], $r + ["is_active" => true]);

        $this->command->info("Creating organizational units...");
        $bagian = OrganizationalUnit::firstOrCreate(["code" => "BAG-UMUM"], ["name" => "Bagian Umum", "unit_type" => "bagian", "is_active" => true]);
        $subbag = OrganizationalUnit::firstOrCreate(["code" => "SUB-TU"], ["name" => "Subbagian Tata Usaha", "unit_type" => "subbagian", "parent_id" => $bagian->id, "is_active" => true]);

        $this->command->info("Creating positions...");
        $kaban = Position::firstOrCreate(["code" => "KABAG"], ["name" => "Kepala Bagian", "position_type" => "struktural", "is_active" => true]);
        $kasub = Position::firstOrCreate(["code" => "KASUB"], ["name" => "Kepala Subbagian", "position_type" => "struktural", "is_active" => true]);
        $staf = Position::firstOrCreate(["code" => "STAF"], ["name" => "Staf Pelaksana", "position_type" => "pelaksana", "is_active" => true]);

        $this->command->info("Creating users...");
        $users = [
            ["email" => "admin@sim-bu.local", "name" => "Administrator", "role" => "super_admin"],
            ["email" => "kaban@sim-bu.local", "name" => "Drs. Ahmad Suryadi", "role" => "kepala_bagian"],
            ["email" => "kasubbag@sim-bu.local", "name" => "Siti Nurhaliza", "role" => "kasubbag"],
            ["email" => "pegawai1@sim-bu.local", "name" => "Budi Santoso", "role" => "pegawai"],
            ["email" => "pegawai2@sim-bu.local", "name" => "Dewi Lestari", "role" => "pegawai"],
            ["email" => "auditor@sim-bu.local", "name" => "BPK Auditor", "role" => "auditor"],
        ];
        foreach ($users as $u) {
            $user = User::firstOrCreate(["email" => $u["email"]], ["name" => $u["name"], "password" => Hash::make("password")]);
            $role = Role::where("code", $u["role"])->first();
            if ($role) $user->roles()->syncWithoutDetaching([$role->id => ["valid_from" => now()]]);
        }

        $this->command->info("Creating employees...");
        foreach ($users as $i => $u) {
            $emp = Employee::firstOrCreate(
                ["employee_number" => "198001012005011".str_pad($i+1, 3, "0", STR_PAD_LEFT)],
                ["full_name" => $u["name"], "employment_status" => "active"]
            );
            EmployeeAssignment::firstOrCreate(
                ["employee_id" => $emp->id],
                ["organizational_unit_id" => $bagian->id, "position_id" => $staf->id, "assignment_type" => "utama", "starts_on" => now()->subYears(2)->toDateString(), "is_primary" => true]
            );
        }

        $this->command->info("Creating inventory...");
        $cat = AssetCategory::firstOrCreate(["code" => "LAPTOP"], ["name" => "Laptop/Notebook"]);
        $cat2 = AssetCategory::firstOrCreate(["code" => "FURN"], ["name" => "Furniture"]);
        $assets = [
            ["asset_code" => "LPT-001", "name" => "Laptop Lenovo ThinkPad T14", "brand" => "Lenovo", "cat" => $cat->id],
            ["asset_code" => "LPT-002", "name" => "Laptop Dell Latitude 5420", "brand" => "Dell", "cat" => $cat->id],
            ["asset_code" => "MEJA-001", "name" => "Meja Kerja Kayu Jati", "brand" => "Lokal", "cat" => $cat2->id],
            ["asset_code" => "KURSI-001", "name" => "Kursi Ergonomis", "brand" => "Informa", "cat" => $cat2->id],
        ];
        foreach ($assets as $a) {
            Asset::firstOrCreate(["asset_code" => $a["asset_code"]], [
                "asset_category_id" => $a["cat"], "name" => $a["name"], "brand" => $a["brand"],
                "condition" => "good", "status" => "available", "created_by_user_id" => User::where("email", "admin@sim-bu.local")->first()->id,
            ]);
        }

        $this->command->info("Creating ATK...");
        $atkCat = SupplyCategory::firstOrCreate(["code" => "ATK"], ["name" => "Alat Tulis Kantor"]);
        $supplies = [
            ["sku" => "HVS-A4", "name" => "Kertas HVS A4", "unit" => "rim", "min" => 10, "stock" => 50],
            ["sku" => "PULPEN-1", "name" => "Pulpen Hitam", "unit" => "pcs", "min" => 20, "stock" => 100],
            ["sku" => "MAP-1", "name" => "Map Plastik", "unit" => "pcs", "min" => 15, "stock" => 8],
        ];
        foreach ($supplies as $s) {
            SupplyItem::firstOrCreate(["sku" => $s["sku"]], [
                "supply_category_id" => $atkCat->id, "name" => $s["name"], "unit" => $s["unit"],
                "minimum_stock" => $s["min"], "current_stock" => $s["stock"], "is_active" => true,
            ]);
        }

        $this->command->info("Creating vehicles...");
        Vehicle::firstOrCreate(["license_plate" => "B 1234 XYZ"], ["make" => "Toyota", "model" => "Avanza", "manufacture_year" => 2020, "passenger_capacity" => 7, "status" => "available"]);
        Vehicle::firstOrCreate(["license_plate" => "B 5678 ABC"], ["make" => "Mitsubishi", "model" => "L300", "manufacture_year" => 2019, "passenger_capacity" => 12, "status" => "available"]);

        $this->command->info("Creating rooms...");
        $r1 = Room::firstOrCreate(["room_code" => "RAPAT-1"], ["name" => "Ruang Rapat Utama", "capacity" => 20, "room_type" => "meeting", "status" => "active"]);
        Room::firstOrCreate(["room_code" => "AULA"], ["name" => "Aula Serbaguna", "capacity" => 100, "room_type" => "auditorium", "status" => "active"]);

        $this->command->info("Creating letters...");
        $admin = User::where("email", "admin@sim-bu.local")->first();
        $pegawai = User::where("email", "pegawai1@sim-bu.local")->first();
        $year = now()->year;

        for ($i = 1; $i <= 3; $i++) {
            IncomingLetter::firstOrCreate(
                ["register_year" => $year, "agenda_sequence" => $i],
                [
                    "external_letter_number" => "001/UND/".$year,
                    "letter_date" => now()->subDays($i)->toDateString(),
                    "received_at" => now()->subDays($i),
                    "sender_name" => "Sekretariat Daerah",
                    "sender_organization" => "Pemda",
                    "subject" => "Undangan Rapat Koordinasi ".($i == 1 ? "Persiapan HUT" : ($i == 2 ? "Evaluasi Program" : "Musrenbang")),
                    "confidentiality" => "internal", "urgency" => "normal", "received_channel" => "physical",
                    "recipient_unit_id" => $bagian->id,
                    "registered_by_user_id" => $admin->id,
                    "status" => "registered",
                ]
            );
        }

        for ($i = 1; $i <= 2; $i++) {
            OutgoingLetter::firstOrCreate(
                ["register_year" => $year, "agenda_sequence" => $i],
                [
                    "subject" => $i == 1 ? "Laporan Bulanan Bagian Umum" : "Permohonan Narasumber",
                    "body_summary" => "Draft surat keluar untuk keperluan ".($i == 1 ? "laporan rutin" : "kegiatan pelatihan"),
                    "creating_unit_id" => $bagian->id,
                    "created_by_user_id" => $admin->id,
                    "status" => "draft",
                ]
            );
        }

        $letter1 = IncomingLetter::where("register_year", $year)->where("agenda_sequence", 1)->first();
        if ($letter1 && $letter1->dispositions()->count() == 0) {
            LetterDisposition::create([
                "incoming_letter_id" => $letter1->id,
                "from_user_id" => $admin->id,
                "to_user_id" => $pegawai->id,
                "instruction" => "Mohon hadir dan siapkan bahan rapat",
                "priority" => "urgent",
                "due_at" => now()->addDays(3),
                "status" => "assigned",
            ]);
        }

        $this->command->info("Creating pending approvals...");
        $asset = Asset::where("asset_code", "LPT-001")->first();
        if ($asset && !AssetLoan::where("asset_id", $asset->id)->exists()) {
            AssetLoan::create([
                "loan_number" => "LOAN-".$year."-0001",
                "asset_id" => $asset->id,
                "requester_user_id" => $pegawai->id,
                "starts_at" => now()->addDay(),
                "expected_return_at" => now()->addDays(3),
                "purpose" => "Untuk keperluan presentasi di luar kantor",
                "status" => "submitted",
            ]);
        }

        if (!VehicleRequest::where("request_number", "VR-".$year."-0001")->exists()) {
            VehicleRequest::create([
                "request_number" => "VR-".$year."-0001",
                "requester_user_id" => $pegawai->id,
                "purpose" => "Kunjungan kerja ke Kecamatan",
                "destination" => "Kecamatan Sukamaju",
                "starts_at" => now()->addDays(2)->setTime(8, 0),
                "ends_at" => now()->addDays(2)->setTime(16, 0),
                "passenger_count" => 3,
                "status" => "submitted",
            ]);
        }

        if (!RoomBooking::where("booking_number", "RB-".$year."-0001")->exists()) {
            RoomBooking::create([
                "booking_number" => "RB-".$year."-0001",
                "room_id" => $r1->id,
                "requester_user_id" => $pegawai->id,
                "purpose" => "Rapat koordinasi tim",
                "attendee_count" => 15,
                "starts_at" => now()->addDays(1)->setTime(9, 0),
                "ends_at" => now()->addDays(1)->setTime(11, 0),
                "status" => "submitted",
            ]);
        }

        $hvs = SupplyItem::where("sku", "HVS-A4")->first();
        if ($hvs && !StockRequest::where("request_number", "ATK-".$year."-0001")->exists()) {
            $req = StockRequest::create([
                "request_number" => "ATK-".$year."-0001",
                "requester_user_id" => $pegawai->id,
                "purpose" => "Kebutuhan ATK bulanan subbagian",
                "status" => "submitted",
            ]);
            StockRequestItem::create([
                "stock_request_id" => $req->id,
                "supply_item_id" => $hvs->id,
                "requested_quantity" => 5,
            ]);
        }

        if (!OfficialTravelRequest::where("request_number", "PD-".$year."-0001")->exists()) {
            OfficialTravelRequest::create([
                "request_number" => "PD-".$year."-0001",
                "requester_user_id" => $pegawai->id,
                "purpose" => "Bimbingan teknis pengelolaan arsip",
                "destination" => "Hotel Grand Kota Bandung",
                "departure_at" => now()->addDays(7)->setTime(6, 0),
                "return_at" => now()->addDays(9)->setTime(20, 0),
                "transport_mode" => "darat",
                "status" => "submitted",
            ]);
        }

        $this->command->info("Creating events...");
        if (!Event::where("event_number", "EVT-".$year."-0001")->exists()) {
            $event = Event::create([
                "event_number" => "EVT-".$year."-0001",
                "title" => "Rapat Koordinasi Bulanan Bagian Umum",
                "event_type" => "rapat",
                "coordinator_user_id" => $admin->id,
                "starts_at" => now()->addDays(5)->setTime(9, 0),
                "ends_at" => now()->addDays(5)->setTime(12, 0),
                "location" => "Ruang Rapat Utama",
                "description" => "Rapat koordinasi rutin bulanan",
                "status" => "planned",
            ]);
            EventAgenda::create([
                "event_id" => $event->id, "sequence_number" => 1,
                "agenda_title" => "Pembukaan", "starts_at" => now()->addDays(5)->setTime(9, 0),
                "ends_at" => now()->addDays(5)->setTime(9, 15), "person_in_charge" => "Siti Nurhaliza",
            ]);
            EventAgenda::create([
                "event_id" => $event->id, "sequence_number" => 2,
                "agenda_title" => "Pembahasan Program", "starts_at" => now()->addDays(5)->setTime(9, 15),
                "ends_at" => now()->addDays(5)->setTime(11, 0), "person_in_charge" => "Drs. Ahmad Suryadi",
            ]);
            EventTask::create([
                "event_id" => $event->id, "task_title" => "Siapkan sound system",
                "assigned_to_user_id" => $pegawai->id, "due_at" => now()->addDays(4),
                "priority" => "high", "status" => "open",
            ]);
        }

        $this->command->info("");
        $this->command->info("=== DEMO SEEDER SELESAI ===");
        $this->command->info("Akun login (semua password: 'password'):");
        $this->command->info("  admin@sim-bu.local      → Super Admin");
        $this->command->info("  kaban@sim-bu.local      → Kepala Bagian");
        $this->command->info("  kasubbag@sim-bu.local   → Kasubbag");
        $this->command->info("  pegawai1@sim-bu.local   → Pegawai (Budi)");
        $this->command->info("  pegawai2@sim-bu.local   → Pegawai (Dewi)");
        $this->command->info("  auditor@sim-bu.local    → Auditor");
        $this->command->info("");
        $this->command->info("Data demo: 3 surat masuk, 2 surat keluar, 1 disposisi");
        $this->command->info("  4 aset, 3 item ATK, 2 kendaraan, 2 ruangan");
        $this->command->info("  5 pengajuan pending (aset/kendaraan/ruangan/ATK/travel)");
        $this->command->info("  1 event dengan agenda & task");
    }
}
