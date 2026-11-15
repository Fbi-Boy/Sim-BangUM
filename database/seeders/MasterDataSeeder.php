<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\OrganizationalUnit;
use App\Models\Position;
use App\Models\Employee;
use App\Models\EmployeeAssignment;
use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Models\LetterDisposition;
use App\Models\LetterDispositionUpdate;
use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\AssetLoan;
use App\Models\SupplyCategory;
use App\Models\SupplyItem;
use App\Models\StockRequest;
use App\Models\StockRequestItem;
use App\Models\Vehicle;
use App\Models\VehicleRequest;
use App\Models\VehicleAssignment;
use App\Models\VehicleUsageLog;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\Event;
use App\Models\EventAgenda;
use App\Models\EventTask;
use App\Models\OfficialTravelRequest;
use App\Models\OfficialTravelParticipant;
use App\Models\ActivityLog;
use App\Models\Document;
use App\Models\ApprovalLog;
use App\Models\BmdAsset;
use Faker\Factory as Faker;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $this->command->info('Mulai mengisi data masif...');

        // 1. Bersihkan tabel transaksi (Kecuali users & roles)
        $tables = [
            'letter_disposition_updates', 'letter_dispositions', 'incoming_letters', 'outgoing_letters',
            'activity_logs', 'approval_logs', 'documents', 'bmd_assets',
            'asset_loans', 'assets', 'asset_categories',
            'stock_request_items', 'stock_requests', 'supply_items', 'supply_categories',
            'vehicle_usage_logs', 'vehicle_assignments', 'vehicle_requests', 'vehicles',
            'room_bookings', 'rooms',
            'event_tasks', 'event_agendas', 'events',
            'official_travel_participants', 'official_travel_requests',
            'employee_assignments', 'employees', 'positions', 'organizational_units'
        ];
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        foreach ($tables as $t) { DB::table($t)->truncate(); }
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        $users = User::all();
        $admin = $users->firstWhere('email', 'admin@sim-bu.local') ?? $users->first();
        $pegawai = $users->firstWhere('email', 'pegawai1@sim-bu.local') ?? $users->first();
        $pejabat = $users->firstWhere('email', 'kaban@sim-bu.local') ?? $users->first();

        // 2. Organisasi & Pegawai (15 baris)
        $this->command->info('Membuat Organisasi & Pegawai (15)...');
        for ($i = 1; $i <= 15; $i++) {
            $unit = OrganizationalUnit::create(['code' => "UNT-$i", 'name' => "Subbagian $i", 'unit_type' => 'subbagian', 'is_active' => true]);
            $pos = Position::create(['code' => "POS-$i", 'name' => "Jabatan $i", 'position_type' => 'struktural', 'is_active' => true]);
            $emp = Employee::create(['employee_number' => "1980010$i", 'full_name' => $faker->name, 'employment_status' => 'active']);
            EmployeeAssignment::create(['employee_id' => $emp->id, 'organizational_unit_id' => $unit->id, 'position_id' => $pos->id, 'assignment_type' => 'utama', 'starts_on' => now()->subYears(2), 'is_primary' => true]);
        }

        // 3. Surat Masuk & Keluar (15 baris)
        $this->command->info('Membuat Surat (15)...');
        for ($i = 1; $i <= 15; $i++) {
            $in = IncomingLetter::create([
                'register_year' => now()->year, 'agenda_sequence' => $i,
                'external_letter_number' => "X/00$i/".now()->year, 'letter_date' => now()->subDays($i),
                'received_at' => now()->subDays($i), 'sender_name' => $faker->company,
                'subject' => "Undangan Rapat Koordinasi $i", 'registered_by_user_id' => $admin->id,
                'status' => 'registered'
            ]);
            $out = OutgoingLetter::create([
                'register_year' => now()->year, 'agenda_sequence' => $i,
                'subject' => "Laporan Bulanan Bagian $i", 'created_by_user_id' => $admin->id,
                'creating_unit_id' => 1, 'status' => 'draft'
            ]);
            $disp = LetterDisposition::create([
                'incoming_letter_id' => $in->id, 'from_user_id' => $pejabat->id,
                'to_user_id' => $pegawai->id, 'instruction' => "Mohon ditindaklanjuti secepatnya $i",
                'priority' => 'normal', 'status' => 'in_progress'
            ]);
            LetterDispositionUpdate::create([
                'letter_disposition_id' => $disp->id, 'updated_by_user_id' => $pegawai->id,
                'status' => 'in_progress', 'progress_note' => "Sedang dikerjakan bagian $i"
            ]);
        }

        // 4. Inventaris & ATK (15 baris)
        $this->command->info('Membuat Inventaris & ATK (15)...');
        for ($i = 1; $i <= 15; $i++) {
            $cat = AssetCategory::create(['code' => "CAT-$i", 'name' => "Kategori Aset $i", 'is_active' => true]);
            $asset = Asset::create([
                'asset_code' => "AST-$i", 'asset_category_id' => $cat->id,
                'name' => "Laptop Model $i", 'condition' => 'good', 'status' => 'available',
                'created_by_user_id' => $admin->id
            ]);
            AssetLoan::create([
                'loan_number' => "LN-$i", 'asset_id' => $asset->id, 'requester_user_id' => $pegawai->id,
                'starts_at' => now(), 'expected_return_at' => now()->addDays(3), 'purpose' => "Pinjam untuk dinas $i", 'status' => 'submitted'
            ]);

            $sCat = SupplyCategory::create(['code' => "SC-$i", 'name' => "Kategori ATK $i", 'is_active' => true]);
            $sItem = SupplyItem::create([
                'sku' => "SKU-$i", 'supply_category_id' => $sCat->id, 'name' => "Kertas Tipe $i",
                'unit' => 'rim', 'current_stock' => 50, 'minimum_stock' => 10, 'is_active' => true
            ]);
            $req = StockRequest::create([
                'request_number' => "REQ-$i", 'requester_user_id' => $pegawai->id,
                'purpose' => "Kebutuhan bulanan $i", 'status' => 'submitted'
            ]);
            StockRequestItem::create([
                'stock_request_id' => $req->id, 'supply_item_id' => $sItem->id, 'requested_quantity' => 5
            ]);
        }

        // 5. Kendaraan (15 baris)
        $this->command->info('Membuat Kendaraan (15)...');
        $pengemudi = $users->firstWhere('email', 'pengemudi@sim-bu.local') ?? $pegawai;
        for ($i = 1; $i <= 15; $i++) {
            $veh = Vehicle::create([
                'license_plate' => "B $i XYZ", 'make' => "Merek $i", 'model' => "Model $i",
                'manufacture_year' => 2020 + ($i % 3), 'passenger_capacity' => 7, 'status' => 'available'
            ]);
            $vReq = VehicleRequest::create([
                'request_number' => "VR-$i", 'requester_user_id' => $pegawai->id,
                'purpose' => "Kunjungan Kerja $i", 'destination' => "Kecamatan $i",
                'starts_at' => now()->addDays(1), 'ends_at' => now()->addDays(2),
                'passenger_count' => 4, 'status' => 'submitted'
            ]);
            $vAss = VehicleAssignment::create([
                'vehicle_request_id' => $vReq->id, 'vehicle_id' => $veh->id,
                'driver_user_id' => $pengemudi->id, 'assigned_by_user_id' => $admin->id,
                'planned_start_at' => now()->addDays(1), 'planned_end_at' => now()->addDays(2),
                'status' => 'assigned'
            ]);
            VehicleUsageLog::create([
                'vehicle_assignment_id' => $vAss->id, 'recorded_by_user_id' => $pengemudi->id,
                'event_type' => 'note', 'happened_at' => now(), 'note' => "Catatan perjalanan $i"
            ]);
        }

        // 6. Fasilitas & Protokol (15 baris)
        $this->command->info('Membuat Fasilitas & Protokol (15)...');
        for ($i = 1; $i <= 15; $i++) {
            $room = Room::create(['room_code' => "RM-$i", 'name' => "Ruang Rapat $i", 'capacity' => 10 + $i, 'room_type' => 'meeting', 'status' => 'active']);
            RoomBooking::create([
                'booking_number' => "BK-$i", 'room_id' => $room->id, 'requester_user_id' => $pegawai->id,
                'purpose' => "Rapat Divisi $i", 'attendee_count' => 10,
                'starts_at' => now()->addDays(1), 'ends_at' => now()->addDays(1)->addHours(2), 'status' => 'submitted'
            ]);

            $ev = Event::create([
                'event_number' => "EV-$i", 'title' => "Acara Penting $i", 'event_type' => 'rapat',
                'coordinator_user_id' => $admin->id, 'starts_at' => now()->addDays(5), 'ends_at' => now()->addDays(5)->addHours(3),
                'location' => "Ruang Utama $i", 'status' => 'planned'
            ]);
            EventAgenda::create([
                'event_id' => $ev->id, 'sequence_number' => 1, 'agenda_title' => "Pembukaan Acara $i",
                'starts_at' => now()->addDays(5), 'ends_at' => now()->addDays(5)->addMinutes(30)
            ]);
            EventTask::create([
                'event_id' => $ev->id, 'task_title' => "Persiapan Logistik $i", 'assigned_to_user_id' => $pegawai->id,
                'priority' => 'normal', 'status' => 'open'
            ]);
        }

        // 7. Travel, Logs & BMD (15 baris)
        $this->command->info('Membuat Travel, Logs & BMD (15)...');
        for ($i = 1; $i <= 15; $i++) {
            $tReq = OfficialTravelRequest::create([
                'request_number' => "TR-$i", 'requester_user_id' => $pegawai->id, 'purpose' => "Tugas Luar $i",
                'destination' => "Kota $i", 'departure_at' => now()->addDays(10), 'return_at' => now()->addDays(12), 'status' => 'submitted'
            ]);
            OfficialTravelParticipant::create([
                'official_travel_request_id' => $tReq->id, 'participant_name' => $pegawai->name, 'participant_role' => 'leader'
            ]);

            ActivityLog::create([
                'actor_user_id' => $admin->id, 'action' => 'seed.data', 'subject_type' => 'App\Models\User',
                'subject_id' => $admin->id, 'description' => "Aktivitas sistem ke-$i", 'ip_address' => '127.0.0.1'
            ]);
            Document::create([
                'title' => "Dokumen Penting $i", 'documentable_type' => 'App\Models\IncomingLetter',
                'documentable_id' => 1, 'disk' => 'local', 'path' => "/tmp/file$i.pdf",
                'original_filename' => "file$i.pdf", 'mime_type' => 'application/pdf', 'file_size_bytes' => 1024 * $i,
                'uploaded_by_user_id' => $admin->id
            ]);
            ApprovalLog::create([
                'subject_type' => 'App\Models\AssetLoan', 'subject_id' => 1,
                'approver_user_id' => $pejabat->id, 'decision' => 'approved', 'note' => "Setuju ke-$i"
            ]);
            BmdAsset::create([
                'bmd_code' => "BMD-$i", 'name' => "Aset Negara $i", 'category' => "Kategori $i",
                'year_acquired' => 2020, 'acquisition_value' => 1000000 * $i, 'condition' => 'good', 'bmd_status' => 'active'
            ]);
        }

        $this->command->info('=== SEEDER SELESAI: SEMUA TABEL MINIMAL 15 BARIS ===');
    }
}
