@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form
            @if ($store == 'update') action="{{ route($route . '.' . $store, $data->id) }}" @else action="{{ route($route . '.' . $store) }}" @endif
            method="post" role="form" enctype="multipart/form-data">

            <div class="row">
                {{ csrf_field() }}
                @if ($store == 'update')
                    {{ method_field('PUT') }}
                @endif

                @foreach ($colomField as $index => $value)
                    <div class="col-md-{{ $countColom }}">
                        <div class="card">
                            <!-- /.card-header -->

                            <div class="card-body">
                                @foreach (array_slice($form, $value[0], $value[1]) as $key => $item)
                                    @include('template.input')
                                @endforeach
                                @if ($index >= 2)
                                    <div id="shift-tampil">
                                        <label for="" class=" form-control-label">
                                            Shift dan Denda
                                        </label>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbShift">
                                                <option selected="selected" value="">Pilih Shift
                                                </option>
                                                @foreach ($dataShift as $i => $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="salestable ">
                                            <table class="table-responsive table table-head-fixed  table-bordered "
                                                id="tableShift" style=" height:400px">
                                                <thead>
                                                    <tr>
                                                        <th class=" text-center" width="2%">No.</th>
                                                        <th class="" width="20%">Nama Shift</th>
                                                        <th class="" width="10%">Menit</th>
                                                        <th class="" width="25%">Denda</th>
                                                        <th class="" width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($store == 'update')
                                                        @isset($data)
                                                            @foreach ($data->hasShift()->get() as $index => $item)
                                                                <tr id="shift_{{ $item->id }}_{{ $index + 1 }}">
                                                                    <td class="text-center nomor_shift"
                                                                        data-index="{{ $index + 1 }}">{{ $index + 1 }}
                                                                    </td>
                                                                    <td>{{ $item->name }}</td>
                                                                    <td class="" width="20%">
                                                                        <input name="menit[]" class="form-control"
                                                                            id="shift_{{ $item->id }}"
                                                                            value="{{ $item->pivot->menit }}" />
                                                                        <input name="id_shift[]" type="hidden"
                                                                            value="{{ $item->id }}" />
                                                                    </td>
                                                                    <td class="" width="20%">
                                                                        <div class="input-group mb-2 mr-sm-2">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">Rp.</div>
                                                                            </div>
                                                                            <input name="denda[]" placeholder="Masukan Denda"
                                                                                value="{{ format_uang($item->pivot->denda) }}"
                                                                                class="form-control denda"
                                                                                id="denda_shift_{{ $item->id }}_{{ $index + 1 }}" />
                                                                        </div>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-warning btn-xs text-center"
                                                                            onclick="hapusitem('shift_{{ $item->id }}_{{ $index + 1 }}')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endisset
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12" id="button-card">
                    <div class="card">
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
@endsection

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        $('#diskon').bind("input keyup paste ", function() {
            let text = this.value.replace(/[^0-9]/g, '');
            let type_diskon = $('input[name="type_diskon"]:checked').val();
            value = $(this).val();
            if (type_diskon === 'persen') {
                if ((value !== '') && (value.indexOf('.') === -1)) {
                    $(this).val(Math.max(Math.min(value, 100), -100));
                }
            } else {
                $('#diskon').val(formatRupiah(text.toString(), ' '));
            }
        });

        $('#cmbShift').select2({
            width: '100%'
        });

        $('.denda').on("input", function() {
            let val = formatRupiah(this.value, '');
            $(this).val(val);
        });

        var gajih = $("input[name='gajih']:checked").val();

        if (gajih === 'perbulan') {
            $('#form_gajih_perbulan').show();
            $('#form_gajih_perhari').hide();
            $('#gajih_perhari').val(0);
        } else {
            $('#form_gajih_perbulan').hide();
            $('#form_gajih_perhari').show();
            $('#gajih_perbulan').val(0);
        }

        $('input[name="gajih"]').click(function() {
            var gajih = $("input[name='gajih']:checked").val();

            if (gajih === 'perbulan') {
                $('#form_gajih_perbulan').show();
                $('#form_gajih_perhari').hide();
                $('#gajih_perhari').val(0);
            } else {
                $('#form_gajih_perbulan').hide();
                $('#form_gajih_perhari').show();
                $('#gajih_perbulan').val(0);
            }
        });

        //tambah Shift
        $("#cmbShift").on("change", function(e) {
            let selected = [];
            selected = $(e.currentTarget).val();
            text = $("#cmbShift option[value=" + selected + "]").text();
            $("#cmbShift option[value=" + selected + "]").prop("selected", false);
            let content = '';
            let nomorTeakhir = $('#tableShift > tbody > tr').length;
            const tombolHapus = `<td class="text-center">
                                <button type="button" class="btn btn-warning btn-xs text-center" onclick="hapusitem('shift_${selected}_${nomorTeakhir + 1}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>`;

            content += `<tr id="shift_${selected}_${nomorTeakhir + 1}">
                            <td width="2%" class="text-center nomor_shift" data-index="${nomorTeakhir + 1}">
                                ${nomorTeakhir + 1}
                            </td>
                            <td class="" width="25%">${text}</td>
                            <td class="" width="20%">
                                <input name="menit[]" class="form-control" id="shift_${selected}" />
                                <input name="id_shift[]" type="hidden" value="${selected}" />
                            </td>
                            <td class="" width="20%">
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp.</div>
                                        </div>
                                        <input name="denda[]" placeholder="Masukan Denda" class="form-control denda" id="denda_shift_${selected}_${nomorTeakhir}" />
                                    </div>

                            </td>
                            ${tombolHapus}
                        </tr>`;

            $('#tableShift').append(content);
            $('.denda').on("input", function() {
                let val = formatRupiah(this.value, '');
                $(this).val(val);
            });
        });

        function hapusitem(id) {
            let item = $('#' + id).length;
            if (item > 0) {
                $('#' + id).remove();
            }
            $('.nomor_shift').each(function(index, item) {
                console.log(index);
                if (parseInt($(item).data('index')) > 2) {
                    $(item).text(index + 1);
                }
            });

        }

        @foreach ($dataShift as $index => $item)
            $('#{{ $item->slug }}').on("input", function() {

            let val = formatRupiah(this.value, '')
            $('#{{ $item->slug }}').val(val)
            });
        @endforeach

        $('#komisi').bind("input keyup paste ", function() {
            let text = this.value.replace(/[^0-9]/g, '');
            value = $(this).val();
            if ((value !== '') && (value.indexOf('.') === -1)) {
                $(this).val(Math.max(Math.min(value, 100), -100));
            }
        });

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }
    </script>
@endpush
