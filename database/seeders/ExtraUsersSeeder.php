<?php
namespace Database\Seeders;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExtraUsersSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['email'=>'faboy@sim-bu.local',              'name'=>'Fabi Fakhruddin',    'role'=>'super_admin'],
            ['email'=>'admin.persuratan@sim-bu.local',   'name'=>'Admin Persuratan',   'role'=>'admin_persuratan'],
            ['email'=>'admin.inventaris@sim-bu.local',   'name'=>'Admin Inventaris',   'role'=>'admin_inventaris'],
            ['email'=>'admin.kendaraan@sim-bu.local',    'name'=>'Admin Kendaraan',    'role'=>'admin_kendaraan'],
            ['email'=>'admin.fasilitas@sim-bu.local',    'name'=>'Admin Fasilitas',    'role'=>'admin_fasilitas'],
            ['email'=>'admin.protokol@sim-bu.local',     'name'=>'Admin Protokol',     'role'=>'admin_protokol'],
            ['email'=>'admin.travel@sim-bu.local',       'name'=>'Admin Travel',       'role'=>'admin_perjalanan_dinas'],
            ['email'=>'arsip@sim-bu.local',              'name'=>'Petugas Arsip',      'role'=>'petugas_arsip'],
            ['email'=>'pegawai3@sim-bu.local',           'name'=>'Rina Pegawai',       'role'=>'pegawai'],
            ['email'=>'pegawai4@sim-bu.local',           'name'=>'Andi Pegawai',       'role'=>'pegawai'],
            ['email'=>'pegawai5@sim-bu.local',           'name'=>'Sinta Pegawai',      'role'=>'pegawai'],
        ];

        foreach ($rows as $r) {
            $role = Role::firstOrCreate(
                ['code' => $r['role']],
                ['name' => ucwords(str_replace('_', ' ', $r['role'])), 'is_active' => true]
            );

            $user = User::firstOrCreate(
                ['email' => $r['email']],
                ['name' => $r['name'], 'password' => Hash::make('password')]
            );

            $user->roles()->syncWithoutDetaching([
                $role->id => ['valid_from' => now()]
            ]);

            $this->command->info("OK: {$user->email} -> {$role->code}");
        }

        $this->command->info('');
        $this->command->info('TOTAL USERS: ' . User::count());
    }
}
