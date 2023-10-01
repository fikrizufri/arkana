<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Gudang;
use App\Models\Toko;
use App\Models\Karyawan;
use App\Traits\CrudTrait;
use Session;
use Carbon\Carbon;
use Auth;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'absensi';
        $this->sort = 'created_at';
        $this->desc = 'desc';
        // $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        // // $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama_karyawan',
                'alias'    => 'Nama Karyawan',
            ],
            [
                'name'    => 'status',
                'alias'    => 'Status',
            ],
            [
                'name'    => 'keterangan',
                'alias'    => 'Keterangan',
            ],
            [
                'name'    => 'waktu',
                'alias'    => 'Waktu',
            ],
            [
                'name'    => 'menit_tampil',
                'alias'    => 'Jumlah Terlambat',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Status',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama',
                'validasi'    => ['required', 'unique', 'min:1'],
            ]
        ];
    }

    public function model()
    {
        return new Absensi();
    }

    public function create()
    {
        return view('absensi.create');
    }

    public function store(Request $request)
    {
        $karyawanId = auth()->user()->karyawan->id;
        $gudang = Karyawan::find($karyawanId)->hasGudang;
        $gudangId = [];
        $gudangIp = '';
        $toko = Karyawan::find($karyawanId)->hasToko;
        $tokoId = [];
        $tokoIp = '';
        $status = $request->status;

        $jadwal = Karyawan::with('hasRosterToday')->find($karyawanId);

        if (isset($toko) || isset($gudang)) {
            // Cek Toko
            foreach ($toko as $index => $value) {
                $tokoId[$index] = $value->id;
            }
            $tokos = Toko::whereIn('id', $tokoId)->first();

            if (isset($tokos)) {
                $tokoIp = $tokos->ip_public;
            }

            // Cek Gudang
            foreach ($gudang as $index => $value) {
                $gudangId[$index] = $value->id;
            }
            $gudangs = Gudang::whereIn('id', $gudangId)->first();

            if (isset($gudangs)) {
                $gudangIp = $gudangs->ip_public;
            }
        }
        // Execute Absensi
        $denda = 0;
        $menit = 0;

        if ($jadwal) {
            $jam_masuk = $jadwal->jam_masuk;
            $jam_pulang = $jadwal->jam_pulang;
            if ($jam_masuk && $jam_pulang) {
                // return request()->ip(); gethostbyname('www.example.com');

                if (request()->ip() == $gudangIp || request()->ip() == $tokoIp || request()->ip() == gethostbyname($gudangIp) || request()->ip() == gethostbyname($tokoIp)) {
                    // cek absens
                    $absensi = Absensi::where('karyawan_id', $karyawanId)->whereDate('created_at', now())->latest()->first();
                    if ($absensi) {

                        if ($absensi->status == 'masuk') {
                            if (now()->format('H:i') <= Carbon::parse($jam_pulang)->format('H:i')) {

                                $message = 'Belum bisa absen pulang, tunggu sampai jam' . $jam_pulang;
                                return redirect()->route('absensi.absen')->with('message', $message)->with('Class', 'danger');
                            }
                        }
                        if ($status == 'lembur') {
                            if ($absensi->status != 'pulang') {
                                $message = 'Belum bisa absen lembur, silahkan absen pulang';
                                return redirect()->route('absensi.absen')->with('message', $message)->with('Class', 'danger');
                            }
                        }

                        if ($absensi->status == 'pulang lembur') {
                            $message = 'Belum bisa absen, silahkan absen besok';
                            return redirect()->route('absensi.absen')->with('message', $message)->with('Class', 'danger');
                        }
                        $absensi = new Absensi;
                        $absensi->keterangan = "Tepat Waktu";
                    } else {
                        $absensi = new Absensi;
                        if (now()->format('H:i') <= Carbon::parse($jam_masuk)->addMinutes(15)->format('H:i')) {
                            $absensi->keterangan = "Tepat Waktu";
                        } else {
                            $menit = Carbon::parse($jam_masuk)->diffInMinutes(Carbon::parse(now()));
                            $absensi->keterangan = "Terlambat";
                            $absensi->menit = $menit;
                            $tes = auth()->user()->karyawan->hasJabatan->hasShift;
                            foreach ($tes as $index => $value) {
                                if ($menit >= $value->pivot->menit) {
                                    $denda = $value->pivot->denda;
                                }
                            }
                            $absensi->denda = $denda;
                        }
                    }
                    $absensi->status = $status;
                    $absensi->karyawan_id = $karyawanId;

                    $absensi->save();

                    if (!request()->user()->hasRole('superadmin')) {
                        if (!request()->user()->hasRole('admin')) {
                            return redirect()->route('penggajian.detail')->with('message', 'Berhasil melakukan absensi ' . $status . ' pada jam '  . $absensi->created_at->format('h:i A'))->with('Class', 'success');
                        }
                    }
                    return redirect()->route('home')->with('message', 'Berhasil melakukan absensi ' . $status . ' pada jam '  . $absensi->created_at->format('h:i A'))->with('Class', 'success');
                } else {
                    return redirect()->route('absensi.scan')->with('message', 'Pastikan anda dikantor')->with('Class', 'danger');
                }
            } else {
                return redirect()->route('absensi.scan')->with('message', 'Jadwal tidak ada')->with('Class', 'danger');
            }
        } else {
            return redirect()->route('absensi.scan')->with('message', 'Karyawan tidak ada')->with('Class', 'danger');
        }
    }

    public function scan()
    {
        // return request()->ip();
        $title = "Absensi";
        return view('absensi.scan', compact('title'));
    }

    public function absen(Type $var = null)
    {
        $title = "Pilih Absensi";
        $karyawanId = auth()->user()->karyawan->id;
        $now = now();
        $cekAbsen = Absensi::where('karyawan_id',  $karyawanId)->whereDate('created_at',  $now)->latest()->first();
        $status = "masuk";
        if ($cekAbsen) {
            if ($cekAbsen->status == "masuk") {
                $status = "pulang";
            }
            if ($cekAbsen->status == "pulang") {
                $status = "lembur";
            }
            if ($cekAbsen->status == "lembur") {
                $status = "pulang lembur";
            }
        }
        return view('absensi.absen', compact('title', 'status'));
    }
}
