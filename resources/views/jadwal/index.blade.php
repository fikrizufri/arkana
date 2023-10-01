@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}
                        </h3>
                        <div class="">
                            <a href="{{ route($route . '.create') }}"
                                class="btn btn-sm btn-primary float-right text-light ml-1">
                                <i class="fa fa-plus"></i> Tambah Data
                            </a>
                            @if (isset($import))
                                <a href="{{ route($import) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-file"></i> Import Data
                                </a>
                            @endif
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                @foreach ($searches as $key => $item)
                                    <div class="col-lg-2">

                                        <label for="{{ $item['name'] }}">{{ ucfirst($item['alias']) }}</label>
                                        @include('template.formsearch')
                                    </div>
                                @endforeach

                                <div class="col-lg-3">
                                    <label for="">Aksi</label>
                                    <div class="input-group">


                                        <button type="submit" class="btn btn-warning">
                                            <span class="fa fa-search"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-bordered table-responsive" id="example">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle" rowspan="3">No</th>
                                    <th class="text-center align-middle" width="50%" rowspan="3">Nama</th>
                                    <th class="text-center align-middle" rowspan="3">NIK</th>
                                    <th class="text-center align-middle" colspan="{{ $tanggal * 2 }}">Tanggal</th>
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < $tanggal; $i++)
                                        <th class="text-center">{{ $i + 1 }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < $tanggal; $i++)
                                        <th class="text-center">Shift</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawan as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->hasRoster }}</td>
                                        {{-- @foreach ($item->hasRoster as $value)
                                            <td>{{ $value }}</td>
                                        @endforeach --}}

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $data->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}">
    </script>
    <script>
        // $('#example').DataTable({
        //   "paging": true,
        //   "lengthChange": true,
        //   "searching": true,
        //   "ordering": true,
        //   "info": true,
        //   "autoWidth": true,
        //   "pageLength": 20,
        // });
    </script>
@endpush
