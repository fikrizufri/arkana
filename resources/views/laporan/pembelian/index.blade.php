@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

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
                                    <th width="5%">No</th>
                                    @foreach ($configHeaders as $key => $header)
                                        @if (isset($header['alias']))
                                            <th>{{ ucfirst($header['alias']) }}</th>
                                        @else
                                            <th>{{ ucfirst($header['name']) }}</th>
                                        @endif
                                    @endforeach
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr>
                                        <td>{{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}</td>
                                        @foreach ($configHeaders as $key => $header)
                                            @if (isset($header['input']))
                                                @if ($header['input'] == 'rupiah')
                                                    <td>Rp. {{ format_uang($item[$header['name']]) }}</td>
                                                @elseif ($header['input'] == 'date')
                                                    <td>
                                                        @if ($item[$header['name']] != null || $item[$header['name']] != '')
                                                            {{ tanggal_indonesia($item[$header['name']]) }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td>{{ $item[$header['name']] }}</td>
                                            @endif
                                        @endforeach
                                        <td class="text-center">
                                            @if (isset($button))
                                                @foreach ($button as $key => $val)
                                                    @include('template.button')
                                                @endforeach
                                            @endif
                                            <a href="{{ route($route . '.pembelian-detail', $item->slug) }}"
                                                class="btn btn-sm btn-warning text-light" data-toggle="tooltip"
                                                data-placement="top" title="Edit">
                                                <i class="nav-icon fas fa-search"></i> Rincian</a>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right" width="5%" colspan="4">Total Penjualan</th>
                                    <th class="text-left">Rp. {{ format_uang($totalRecord) }}</th>
                                </tr>
                            </tfoot>
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
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>
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
