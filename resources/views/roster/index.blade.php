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
                            {{-- <a href="{{ route($route . '.create') }}"
            class="btn btn-sm btn-primary float-right text-light ml-1">
            <i class="fa fa-plus"></i> Tambah Data
            </a> --}}
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
                                    <th class="text-center align-middle" rowspan="3">Nama</th>
                                    <th class="text-center align-middle" rowspan="3">NIK</th>
                                    <th class="text-center align-middle " colspan="{{ $tanggal * 2 }}">Bulan
                                    </th>
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < $tanggal; $i++)
                                        <th class="text-center">Tanggal {{ $i + 1 }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 0; $i < $tanggal; $i++)
                                        <th class="text-center">Shift</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($listKaryawnShift as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            {{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}
                                        </td>
                                        <td>{!! $item['nama'] !!}</td>
                                        <td>{!! $item['nik'] !!}</td>
                                        @foreach ($item['shift'] as $key => $val)
                                            <td>

                                                @if ($searchBulanDipilih > $bulan)
                                                    <form action="{{ route('roster.proses') }}"
                                                        id="form_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}"
                                                        method="post">
                                                        <input type="hidden" name="tanggal" value="{{ $val['tanggal'] }}"
                                                            id="tanggal_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <input type="hidden" name="roster_id"
                                                            value="{{ $val['roster_id'] }}"
                                                            id="roster_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <input type="hidden" name="karyawan_id"
                                                            value="{{ $val['karyawan_id'] }}"
                                                            id="karyawan_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <div class="form-group" style=" width: 120px !important;">
                                                            <select class="form-control select2"
                                                                id="shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}"
                                                                name="shift_id">
                                                                <option selected="selected" value="">Belum ada</option>
                                                                @foreach ($shift as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $val['shift_id'] == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </form>
                                                @elseif ($val['no'] >= $hariini)
                                                    <form action="{{ route('roster.proses') }}"
                                                        id="form_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}"
                                                        method="post">
                                                        <input type="hidden" name="tanggal" value="{{ $val['tanggal'] }}"
                                                            id="tanggal_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <input type="hidden" name="roster_id"
                                                            value="{{ $val['roster_id'] }}"
                                                            id="roster_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <input type="hidden" name="karyawan_id"
                                                            value="{{ $val['karyawan_id'] }}"
                                                            id="karyawan_shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}">
                                                        <div class="form-group" style=" width: 120px !important;">
                                                            <select class="form-control select2"
                                                                id="shift_{{ $val['karyawan_id'] }}{{ $index }}{{ $key }}{{ $val['no'] }}"
                                                                name="shift_id">
                                                                <option selected="selected" value="">Belum ada</option>
                                                                @foreach ($shift as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $val['shift_id'] == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </form>
                                                @else
                                                    {{ $val['shift'] }}
                                                @endif
                                            </td>
                                        @endforeach

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

    <style>
        .select2 {
            width: 1000px !important;
        }

    </style>
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}">
    </script>
    <script>
        $(".select2").select2({
            width: 'resolve'
        });

        $('.select2').on('select2:selecting', function(e) {
            var value = e.params.args.data.id;
            let id = $(this).attr('id');
            $('#' + id).val(value);
            let shift_id = $('#' + id).val();
            let karyawan_id = $('#karyawan_' + id).val();
            let tanggal = $('#tanggal_' + id).val();
            let roster = $('#roster_' + id).val();
            console.log('#karyawan_' + id);
            var form = $("#form_" + id);
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'karyawan_id': karyawan_id,
                    'tanggal': tanggal,
                    'roster_id': roster,
                    'shift_id': shift_id
                },
                success: function(data) {
                    // console.log(data);
                    $.toast({
                        heading: 'Success',
                        text: 'Sukses mengubah roster',
                        showHideTransition: 'slide',
                        icon: 'success',
                        loaderBg: '#f2a654',
                        position: 'top-right'
                    })
                }
            });
        });
    </script>
@endpush
