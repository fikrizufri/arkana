@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        Pilih Absensi dibawah
                    </div>
                    <div class="card-body">
                        <form id="form" action="{{ route('absensi.store') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            {{ method_field('post') }}
                            <input type="hidden" name="status" value="{{ $status }}">
                        </form>
                        <button class="btn btn-danger btn-sm" title="{{ ucfirst($status) }}"
                            onclick=absensi('{{ strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $status))) }}')>
                            {{ ucfirst($status) }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
    <script>
        function absensi(abensi) {
            let abens = abensi.replace("-", " ");

            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                buttonsStyling: false,
            });

            swalWithBootstrapButtons({
                title: "Anda yakin absen " + abens,
                text: "Absen " + abens,
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, absen data!",
                cancelButtonText: "Tidak, kembali!",
            }).then((result) => {
                if (result.value) {
                    swalWithBootstrapButtons(
                        "Absensi!",
                        "Anda berhasil absen",
                        "success"
                    );
                    document.getElementById("form").submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        "Kembali",
                        "Mohon berhati-hati melakukan absensi",
                        "error"
                    );
                }
            });
        }
    </script>
@endpush
