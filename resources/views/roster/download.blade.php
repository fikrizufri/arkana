<h3>Roster</h3>

<table style="border: 1px solid black;">
    <thead>
        <tr>
            <th rowspan="3" style="border: 1px solid black; vertical-align: middle;  text-align: center;">No</th>
            <th rowspan="3" style="border: 1px solid black; vertical-align: middle;  text-align: center;">Nama</th>
            <th rowspan="3" style="border: 1px solid black; vertical-align: middle;  text-align: center;">KTP</th>
            <th colspan="{{ $tanggal }}"
                style="border: 1px solid black; vertical-align: middle;  text-align: center;"> Bulan
                {{ $bulan }}
            </th>
        </tr>
        <tr>
            @for ($i = 0; $i < $tanggal; $i++)
                <th style="border: 1px solid black; vertical-align: middle;  text-align: center;">Tanggal
                    {{ $i + 1 }}
                </th>
            @endfor
        </tr>
        <tr>
            @for ($i = 0; $i < $tanggal; $i++)
                <th style="border: 1px solid black; vertical-align: middle;  text-align: center;">Shift</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @forelse ($karyawan as $index => $item)
            <tr>
                <td style="border: 1px solid black; vertical-align: middle;  text-align: center;">
                    {{ $index + 1 }}
                </td>
                <td style="border: 1px solid black; vertical-align: middle;  text-align: left;">{{ $item->nama }}
                </td>
                <td style="border: 1px solid black; vertical-align: middle;  text-align: left;">
                    {{ (int) $item->nik }}
                </td>
                @for ($i = 0; $i < $tanggal; $i++)
                    <td style="border: 1px solid black; vertical-align: middle;  text-align: center;"></td>
                @endfor
            </tr>
        @empty
            <tr>
                <td colspan="10">Data Karyawan tidak ada</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>

    </tfoot>
</table>
