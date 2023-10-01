@extends('template.app')
@section('title', 'Daftar Penggajian')
@section('content')

    @push('head')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush`

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>
                        <div class="d-flex justify-content-between">
                            <div class="card-body">
                                <form action="" method="GET">
                                    <div class="input-group mb-3 col-md-12 float-right">
                                        <input type="text" id="daterange" name="date" class="form-control"
                                            value="{{ $requestdate }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nama Karyawan</label>
                                <input type="text" class="form-control" value="{{ $karyawan->nama }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Gaji Pokok</label>
                                <input type="text" class="form-control" value="{{ format_uang($gajipokok) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Absen</label>
                                <input type="text" class="form-control" value="{{ format_uang($absensi) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Absen Lembur</label>
                                <input type="text" class="form-control" value="{{ $lembur }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Total Komisi Lembur</label>
                                <input type="text" class="form-control" value="{{ format_uang($jumlahLembur) }}"
                                    readonly>
                            </div>
                            @if ($tipeGajih != 'perbulan')
                                <div class="form-group">
                                    <label>Total Gaji Absen</label>
                                    <input type="text" class="form-control" value="{{ format_uang($totalGajih) }}"
                                        readonly>
                                </div>
                            @endif
                            <div class="form-group">
                                <label>Total Penjualan Keseluruhan</label>
                                <input type="text" class="form-control" value="{{ format_uang($total_penjualan_all) }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label>Target Penjualan</label>
                                <input type="text" class="form-control"
                                    value="{{ format_uang($karyawan->target_pendapatan) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Bonus Target Penjualan</label>
                                <input type="text"
                                    class="{{ $bonus == 0 ? 'form-control is-invalid' : 'form-control' }}"
                                    value="{{ format_uang($bonus) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Total Komisi Layanan</label>
                                <input type="text" class="form-control" value="{{ format_uang($total_komisi) }}"
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label>Total Tip</label>
                                <input type="text" class="form-control" value="{{ format_uang($total_tip) }}" readonly>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>Total Denda Absen</label>
                                <input type="text" class="form-control" value="{{ format_uang($denda) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label>Total Kas Bon</label>
                                <input type="text" class="form-control" value="{{ format_uang($kasbon) }}" readonly>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label><strong>Total Keseluruhan Gaji</strong></label>
                                <input type="text" class="form-control" value="{{ format_uang($thp) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <h1>Detail Layanan</h1>
                        <div class="salestable ">
                            <table class="table-responsive table table-head-fixed  table-bordered " id="tableProduk"
                                style="height:400px">
                                <thead>
                                    <tr>
                                        <th class=" text-center" width="5%">No.</th>
                                        <th class="">No Nota</th>
                                        <th class="">Tanggal</th>
                                        <th class="">Pelanggan</th>
                                        <th class="">Layanan / Produk</th>
                                        <th class="">Kategori</th>
                                        <th class="">Qty</th>
                                        <th class="">Diskon</th>
                                        <th class="">Total Harga</th>
                                        <th class="">Komisi</th>
                                        <th class="">Total Komisi</th>
                                        <th class="">Tips</th>
                                    </tr>
                                </thead>
                                <tbody id="body-table">
                                    @foreach ($penjualanDetail as $index => $item)
                                        <tr id="item_{{ $item->id }}" class="list-produk">
                                            <td class=" text-center" width="5%">{{ $index + 1 }}</td>
                                            <td>{{ $item->no_nota }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->pelanggan }}</td>
                                            <td>{{ $item->produk }}</td>
                                            <td>{{ $item->nama_kategori }}</td>
                                            <td>{{ $item->qty }}</td>
                                            @if ($item->type_diskon == 'persen')
                                                <td>{{ $item->diskon_produk }} %</td>
                                            @else
                                                <td> Rp. {{ format_uang($item->diskon_produk) }}</td>
                                            @endif
                                            <td>Rp. {{ format_uang($item->total_harga) }}</td>
                                            @if ($item->nama_kategori == 'Produk' || $item->nama_kategori == 'Bahan Baku')
                                                <td> Rp. {{ format_uang($item->produk_komisi) }}</td>
                                            @else
                                                <td>{{ $karyawan->komisi }} %</td>
                                            @endif

                                            <td>Rp. {{ format_uang($item->komisi) }}</td>
                                            <td>Rp. {{ format_uang($item->tip) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-right" colspan="9">Total Layanan</th>
                                        <th class="">{{ $penjualanDetail->count('qty') }}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="9">Total Tips</th>
                                        <th class="">Rp.
                                            {{ format_uang($penjualanDetail->sum('tip')) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="9">Total Penjualan</th>
                                        <th class="">Rp. {{ format_uang($total_penjualan_all) }}
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="9">Total Komisi</th>
                                        <th class="">Rp. {{ format_uang($penjualanDetail->sum('komisi')) }}
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- ./col -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
@stop

@push('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>\

    @if ($requestdate)
        <script type="text/javascript">
            $(document).ready(function() {
                $('#daterange').daterangepicker()
            })
        </script>
    @else
        <script type="text/javascript">
            $(document).ready(function() {
                let start = moment().startOf('month')
                let end = moment().endOf('month')
                $('#daterange').daterangepicker({
                    startDate: start,
                    endDate: end,
                })
            })
        </script>
    @endif
@endpush
