@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->
            <div class="col-xl-4 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">{{ $layananTerbaik->has_penjualan_count }}</p>
                                <p class="mb-0">{{ __('Layanan') }} : {{ $layananTerbaik->nama }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">Rp. {{ format_uang($penjualanHariIni) }}</p>
                                <p class="mb-0">{{ __('Penjualan Hari ini') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">Rp. {{ format_uang($penjualan) }}</p>
                                <p class="mb-0">{{ __('Penjualan bulan ini') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">Rp. {{ format_uang($penjualanBulanLalu) }}</p>
                                <p class="mb-0">{{ __('Penjualan bulan lalu') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">{{ $customer }}</p>
                                <p class="mb-0">{{ __('Pelanggan Bulan ini') }}</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">
                                    Rp. {{ $penjualan_sum_penjualan }}
                                </p>
                                <p class="mb-0">{{ __('Karyawan') }} : {{ $namakaryawanTerbaik }}
                                </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik f-30">৳</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">
                                    Rp. {{ format_uang($penjualanBulanLaluCash) }}
                                </p>
                                <p class="mb-0">{{ __('Penjualan Cash Bulan ' . $now) }}
                                </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-dollar-sign">৳</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">
                                    Rp. {{ format_uang($penjualanBulanLaluTrasnfer) }}
                                </p>
                                <p class="mb-0">{{ __('Penjualan Trasnfer Bulan ' . $now) }}
                                </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-send">৳</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-0">
                                    Rp. 0
                                </p>
                                <p class="mb-0">{{ __('Total Pengeluaran ' . $now) }}
                                </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-send">৳</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page statustic chart end -->
            <!-- sale 2 card start -->

            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-block pb-0">
                        <div class="row mb-50">
                            <div class="col">
                                <h6 class="mb-5">{{ __('Penjualan Pada Tahun ' . $tahun) }}</h6>
                                <h5 class="mb-0  fw-700">
                                    {{ __('Rp. ' . format_uang($penjualanSetahun->sum('grandtotal'))) }}</h5>
                            </div>
                        </div>
                        <div id="placeholder" class="chart-shadow"></div>
                    </div>
                </div>
            </div>
            <!-- sale 2 card end -->

            <!-- product and new customar start -->
            <div class="col-xl-12 col-md-12">
                <div class="card table-card">
                    <div class="card-header">
                        <h3>{{ __('Daftar Karyawan') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nama') }}</th>
                                        <th>{{ __('Jabatan') }}</th>
                                        <th>{{ __('Target') }}</th>
                                        <th>{{ __('Penjualan Bulan ' . $now) }}</th>
                                        <th>{{ __('Komisi Bulan ' . $now) }}</th>
                                        <th>{{ __('Penjualan Bulan ini') }}</th>
                                        <th>{{ __('Komisi Bulan Ini') }}</th>
                                        <th>{{ __('Komisi Hari Ini') }}</th>
                                        <th class="text-center">{{ __('Layanan Hari Ini') }}</th>
                                        <th>Bank</th>
                                        <th>Nomor Rekening</th>
                                        <th>Gaji Bulan {{ $now }}</th>
                                        <th>Gaji Bulan Ini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listKaryawan as $index => $karyawan)
                                        <tr>
                                            <td>{{ $karyawan->nama }}</td>
                                            <td>{{ $karyawan->nama_jabatan }}</td>
                                            <td>
                                                Rp. {{ format_uang($karyawan->target) }}
                                            </td>
                                            <td>
                                                Rp.
                                                {{ format_uang($karyawan->total_penjualan_bulan_lalu) }}
                                            </td>
                                            <td>
                                                Rp. {{ format_uang($karyawan->total_komisi_bulan_lalu) }}
                                            </td>
                                            <td>
                                                Rp.
                                                {{ format_uang($karyawan->total_penjualan_bulan_ini) }}
                                            </td>
                                            <td>
                                                Rp. {{ format_uang($karyawan->total_komisi_bulan_ini) }}
                                            </td>

                                            <td>
                                                Rp. {{ format_uang($karyawan->total_komisi_perhari_ini) }}
                                            </td>
                                            <td class="text-center">{{ $karyawan->total_layanan_perhari_ini }}
                                            </td>
                                            <td>{{ $karyawan->bank }}</td>
                                            <td>{{ $karyawan->no_rek }}</td>
                                            <td>Rp. {{ format_uang($karyawan->total_gaji_bulan_lalu) }}</td>
                                            <td>Rp. {{ format_uang($karyawan->total_gaji) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card new-cust-card">
                    <div class="card-header">
                        <h3>{{ __('List Layanan') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nama Layanan') }}</th>
                                        <th class="text-center">{{ __('TotaL Penjualan Hari ini') }}</th>
                                        <th class="text-center">{{ __('TotaL Penjualan Bulan ini') }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listlayanan as $index => $layanan)
                                        <tr>
                                            <td>{{ $layanan->nama }}</td>
                                            <td class="text-center">{{ $layanan->total }}</td>
                                            <td class="text-center">{{ $layanan->total_perbulan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card new-cust-card">
                    <div class="card-header">
                        <h3>{{ __('List Produk') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Nama Layanan') }}</th>
                                        <th class="text-center">{{ __('TotaL Penjualan Hari ini') }}</th>
                                        <th class="text-center">{{ __('TotaL Penjualan Bulan ini') }}</th>
                                        <th class="text-center">{{ __('Sisa Stok') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listProduk as $index => $produk)
                                        <tr>
                                            <td>{{ $produk->nama }}</td>
                                            <td class="text-center">{{ $produk->total }}</td>
                                            <td class="text-center">{{ $produk->total_perbulan }}</td>
                                            <td class="text-center">{{ $produk->stok }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card new-cust-card">
                    <div class="card-header">
                        <h3>{{ __('Transaksi Terbanyak') }}</h3>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="ik ik-chevron-left action-toggle"></i></li>
                                <li><i class="ik ik-minus minimize-card"></i></li>
                                <li><i class="ik ik-x close-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        @foreach ($listCustomer as $index => $pelanggan)
                            <div class="align-middle mb-25">
                                <div class="d-inline-block">
                                    <a href="#">
                                        <h6>{{ $pelanggan->nama }}</h6>
                                    </a>
                                    <p class="text-muted mb-0">Jumlah Transaksi : {{ $pelanggan->jumlah_nota }}</p>
                                    <span class="status active"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- product and new customar end -->
        </div>
    </div>
@stop

@push('chart')
@endpush
@push('style')
    <style>
        @media (max-width: 500px) {
            #perda {
                height: 52px;
            }
        }

        .modal {
            text-align: center;
        }

        @media screen and (min-width: 768px) {
            .modal:before {
                display: inline-block;
                vertical-align: middle;
                content: " ";
                position: absolute;
                height: 100%;

            }
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
            top: 50%;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('plugins/chartist/dist/chartist.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
    <!-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
    <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

    <script src="{{ asset('plugins/amcharts/amcharts.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/serial.js') }}"></script>
    <script src="{{ asset('plugins/amcharts/themes/light.js') }}"></script>

    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

    <script>
        let grafikperbulan = @json($penjualanPerbulanGrafik);
        $(window).on('resize', function() {
            categoryChart();
        });
        categoryChart();
        /*categories chart*/

        /* Fungsi formatRupiah */
        function formatRupiahGrafik(angka, prefix) {
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

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#fee',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        $("#placeholder").bind("plothover", function(event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = formatRupiahGrafik(item.datapoint[1].toString(), 'Rp. ');

                    showTooltip(item.pageX, item.pageY, y);
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });


        function categoryChart() {

            let p = $.plot("#placeholder", [grafikperbulan], {
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.6,
                        align: "center",
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0,
                    tickColor: '#f5f5f5',
                },
                colors: ["#01C0C8", "#83D6DE"],
                labelBoxBorderColor: "red",
                valueLabels: {
                    show: true
                },
                tooltip: true,
                legend: {
                    show: true,
                    noColumns: 2,
                    container: "#bar-legend"
                },
                grid: {
                    hoverable: true
                }
            });

        };
    </script>
@endpush
