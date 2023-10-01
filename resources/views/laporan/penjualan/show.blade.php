@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group form-textinput" id="form_{{ $data->no_nota }}">

                            <div>
                                <label for="no_nota" class=" form-control-label">No Nota</label>
                            </div>
                            <input type="text" name="no_nota" id="no_nota" class="form-control"
                                value="{{ $data->no_nota }}" readonly>
                        </div>
                        <div class="form-group form-textinput" id="form_{{ $data->pelanggan }}">

                            <div>
                                <label for="pelanggan" class=" form-control-label">Pelanggan</label>
                            </div>
                            <input type="text" name="pelanggan" id="pelanggan" class="form-control"
                                value="{{ $data->pelanggan }}" readonly>
                        </div>
                        <div class="form-group form-textinput" id="form_{{ $data->grandtotal }}">

                            <div>
                                <label for="grandtotal" class=" form-control-label">Total</label>
                            </div>
                            <input type="text" name="grandtotal" id="grandtotal" class="form-control"
                                value="Rp. {{ format_uang($data->grandtotal) }}" readonly>
                        </div>
                        <div class="salestable ">
                            <table class="table-responsive table table-head-fixed  table-bordered " id="tableProduk"
                                style="height:400px">
                                <thead>
                                    <tr>
                                        <th class=" text-center" width="5%">No.</th>
                                        <th class="" width="25%">Produk</th>
                                        <th class="" width="10%">Qty</th>
                                        <th class="" width="15%">Diskon</th>
                                        <th class="" width="15%">Total Harga</th>
                                        <th class="" width="13%">Karyawan</th>
                                        <th class="" width="25%">Tips</th>
                                    </tr>
                                </thead>
                                <tbody id="body-table">
                                    @foreach ($penjualanDetail as $index => $item)
                                        <tr id="item_{{ $item->id }}" class="list-produk">
                                            <td class=" text-center" width="5%">{{ $index + 1 }}</td>
                                            <td>{{ $item->produk }}</td>
                                            <td>{{ $item->qty }}</td>
                                            @if ($item->type_diskon == 'persen')
                                                <td>{{ $item->diskon_produk }} %</td>
                                            @else
                                                <td> Rp. {{ format_uang($item->diskon_produk) }}</td>
                                            @endif
                                            <td>Rp. {{ format_uang($item->total_harga) }}</td>
                                            <td>{{ $item->karyawan }}</td>
                                            <td>Rp. {{ format_uang($item->tip) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-right" colspan="4">Total Qty</th>
                                        <th class="">{{ $penjualanDetail->count('qty') }}</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right" colspan="4">Diskon</th>
                                        <th class="">Rp. {{ format_uang($data->diskon) }} </th>
                                    </tr>
                                    <tr>
                                        <th class=" text-right" colspan="4">Grand Total </th>
                                        <th class="">Rp. {{ format_uang($data->grandtotal) }}</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->

@endsection

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
@endpush
