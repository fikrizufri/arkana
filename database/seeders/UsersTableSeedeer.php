<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UsersTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new Role();
        $superadmin->name = 'Superadmin';
        $superadmin->save();

        $adminRole = new Role();
        $adminRole->name = 'Admin';
        $adminRole->save();

        $kasirRole = new Role();
        $kasirRole->name = 'Kasir';
        $kasirRole->save();

        $KaryawanRole = new Role();
        $KaryawanRole->name = 'Karyawan';
        $KaryawanRole->save();

        $superadmin = Role::where('slug', 'superadmin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $adminKasir = Role::where('slug', 'kasir')->first();

        $superadminUser = new User();
        $superadminUser->name = 'Superadmin';
        $superadminUser->username = 'Superadmin';
        $superadminUser->email = 'Superadmin@admin.com';
        $superadminUser->password = bcrypt('secret');
        // $superadminUser->icon = 'default-icon.png';
        $superadminUser->save();

        $superadminUser->role()->attach($superadmin);

        $kasirUser = new User();
        $kasirUser->name = 'kasir';
        $kasirUser->username = 'kasir';
        $kasirUser->email = 'kasir@admin.com';
        $kasirUser->password = bcrypt('secret');
        // $kasirUser->icon = 'default-icon.png';
        $kasirUser->save();

        $kasirUser->role()->attach($adminKasir);

        $admin = new User();
        $admin->name = 'admin';
        $admin->username = 'admin';
        $admin->email = 'admin@admin.com';
        $admin->password = bcrypt('secret');
        // $admin->icon = 'default-icon.png';
        $admin->save();
        $admin->role()->attach($adminRole);

        $taskUser = new Task();
        $taskUser->name = 'User';
        $taskUser->description = 'Manajemen Pengguna';
        $taskUser->save();

        $taskRole = new Task();
        $taskRole->name = 'Roles';
        $taskRole->description = 'Manajemen Hak Akses ';
        $taskRole->save();


        $taskProduk = new Task();
        $taskProduk->name = 'Produk';
        $taskProduk->description = 'Manajemen Produk';
        $taskProduk->save();

        $taskSatuan = new Task();
        $taskSatuan->name = 'Satuan';
        $taskSatuan->description = 'Manajemen Satuan';
        $taskSatuan->save();

        $taskKategori = new Task();
        $taskKategori->name = 'Kategori';
        $taskKategori->description = 'Manajemen Kategori';
        $taskKategori->save();

        $taskJenis = new Task();
        $taskJenis->name = 'Jenis';
        $taskJenis->description = 'Manajemen Jenis';
        $taskJenis->save();

        $taskToko = new Task();
        $taskToko->name = 'Toko';
        $taskToko->description = 'Manajemen Toko';
        $taskToko->save();

        $taskSupplier = new Task();
        $taskSupplier->name = 'Supplier';
        $taskSupplier->description = 'Manajemen Supplier';
        $taskSupplier->save();


        $taskJabatan = new Task();
        $taskJabatan->name = 'Jabatan';
        $taskJabatan->description = 'Manajemen Jabatan';
        $taskJabatan->save();

        $taskKaryawan = new Task();
        $taskKaryawan->name = 'Karyawan';
        $taskKaryawan->description = 'Manajemen Karyawan';
        $taskKaryawan->save();

        $taskShift = new Task();
        $taskShift->name = 'Shift';
        $taskShift->description = 'Manajemen Shift';
        $taskShift->save();

        $taskRoster = new Task();
        $taskRoster->name = 'Roster';
        $taskRoster->description = 'Manajemen Roster';
        $taskRoster->save();

        $taskAbsensi = new Task();
        $taskAbsensi->name = 'Absensi';
        $taskAbsensi->description = 'Manajemen Absensi';
        $taskAbsensi->save();

        $taskKasBon = new Task();
        $taskKasBon->name = 'Kas Bon';
        $taskKasBon->description = 'Manajemen Kas Bon';
        $taskKasBon->save();

        $taskPembayaranKasBon = new Task();
        $taskPembayaranKasBon->name = 'Pembayaran Kas Bon';
        $taskPembayaranKasBon->description = 'Manajemen Pembayaran Kas Bon';
        $taskPembayaranKasBon->save();

        $taskPenjualan = new Task();
        $taskPenjualan->name = 'Penjualan';
        $taskPenjualan->description = 'Manajemen Penjualan';
        $taskPenjualan->save();

        $taskPromosi = new Task();
        $taskPromosi->name = 'Promosi';
        $taskPromosi->description = 'Manajemen Promosi';
        $taskPromosi->save();

        $taskPembelian = new Task();
        $taskPembelian->name = 'Pembelian';
        $taskPembelian->description = 'Manajemen Pembelian';
        $taskPembelian->save();

        $taskMetodePembayaran = new Task();
        $taskMetodePembayaran->name = 'Metode Pembayaran';
        $taskMetodePembayaran->description = 'Manajemen Metode Pembayaran';
        $taskMetodePembayaran->save();

        $taskBank = new Task();
        $taskBank->name = 'Bank';
        $taskBank->description = 'Manajemen Bank';
        $taskBank->save();

        $taskPelanggan = new Task();
        $taskPelanggan->name = 'Pelanggan';
        $taskPelanggan->description = 'Manajemen Pelanggan';
        $taskPelanggan->save();

        $taskPenggajian = new Task();
        $taskPenggajian->name = 'Penggajian';
        $taskPenggajian->description = 'Manajemen Penggajian';
        $taskPenggajian->save();


        $tasks = Task::all();

        foreach ($tasks as $task) {
            $name = $task->name;
            $data = array(

                [
                    'name'    => 'View ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Create ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Edit ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Delete ' . $name,
                    'task_id' => $task->id
                ],
            );

            foreach ($data as $induk) {
                $Permission = Permission::Create($induk);
                $adminRole->permissions()->attach($Permission->id);
            }
        }
    }
}
