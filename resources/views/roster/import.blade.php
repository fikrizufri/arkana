@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <form action="{{ route($action) }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <a class="btn btn-success" href="{{ route('roster.download') }}"><i
                                        class="fas fa-download"></i> Download template Roster</a>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <label for="exampleInputFile">Pilih Bulan</label>
                                <select class="form-control select2" id="listbulan" name="bulan">
                                    <option selected="selected" value="">Pilih Bulan
                                    </option>
                                    @foreach ($month as $index => $bulan)
                                        <option value="{{ $index + 1 }}" id="bulan_{{ $bulan }}">
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('file'))
                                    <span class="text-danger">Mohon upload dengan benar, file harus berekstensi .csv
                                        dengan format yang sesuai.</span>
                                @endif
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <label for="exampleInputFile">Silahkan Input File Roster Di bawah</label>
                                <input type="file" name="file" class="file-upload-default">
                                <div class="input-group">
                                    <input type="text" class="form-control file-upload-info" disabled
                                        placeholder="Upload File Excel">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Pilih File</button>
                                    </span>
                                </div>
                                @if ($errors->has('file'))
                                    <span class="text-danger">Mohon upload dengan benar, file harus berekstensi .csv
                                        dengan format yang sesuai.</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
    <script src="{{ asset('js/form-components.js') }}"></script>
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        $('#listbulan').select2({
            width: '100%'
        });
    </script>
@endpush
