@extends('template.app')
@section('title', 'Daftar Penggajian')
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Bank</th>
                                    <th>Nomor Rekening</th>
                                    <th>Gaji Bulan {{ $now }}</th>
                                    <th>Gaji Bulan ini </th>
                                    <th class="text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawans as $item)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->bank }}</td>
                                        <td>{{ $item->no_rek }}</td>
                                        <td>Rp. {{ format_uang($item->total_gaji_bulan_lalu) }}</td>
                                        <td>Rp. {{ format_uang($item->total_gaji) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('penggajian.show', $item->slug) }}"
                                                class="btn btn-sm btn-primary text-light">
                                                <i class="nav-icon fas fa-edit"></i> Rincian</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Data karyawan tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $karyawans->links() }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div>
@stop
