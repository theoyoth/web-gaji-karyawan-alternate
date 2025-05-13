<!-- resources/views/user/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/table.css')
    <title>Print User Details</title>

</head>
<body>
    <div class="px-4">
        {{-- <div>
            <h1 class="header-text text-2xl font-bold text-center">PT.GUNUNG SELATAN</h3>
            <h1 class="header-subtext text-xl font-bold text-center">DAFTAR :  GAJI KARYAWAN KANTOR 1</h3>
            <h1 class="header-subtext text-xl font-bold text-center">BULAN : {{ $month ?? '' }} {{ $year ?? '' }}</h3>
        </div> --}}
        <div class="kop-surat">
          <h1 class="header-text">PT.GUNUNG SELATAN</h3>
          <h1 class="header-subtext">KONTRAKTOR & LEVERANSIR</h1>
          <h1 class="header-subtext">NABIRE - PAPUA</h1>
          <h1 class="small-text">Alamat: Jln. R.E.Martadinata No.216, Telp: (0984) 21722, Bank: Mandiri & BPD</h1>
        </div>
        <div>
          <h1 class="subtext">DAFTAR :  GAJI KARYAWAN & KARYAWATI KANTOR 1</h1>
          <h1 class="subtext">BULAN : {{ $month ?? '' }} {{ $year ?? '' }}</h3>
        </div>



        <div>
            <a href="{{ route('kantor1.index') }}" class="link-button inline-block my-4 px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800"><- Kembali</a>
            <button class="print-button" onclick="window.print()">🖨️ Print</button>
        </div>

        <form method="GET" action="{{ route('print.kantor1.filtered') }}">
            <select name="bulan" required class="select-input">
                <option value="">-- Pilih Bulan --</option>
                @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                    <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                @endforeach
            </select>

            <select name="tahun" required class="select-input">
                <option value="">-- Pilih Tahun --</option>
                @for ($y = 2020; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="submit" class="select-input">Filter</button>

            {{-- Reset Filter Button --}}
            @if(request('bulan') || request('tahun'))
              <a href="{{ route('print.kantor1.filtered') }}" class="select-input btn-reset">Reset</a>
            @endif
        </form>

        <div class="bg-gray-100">
          @if($users->filter(fn($user) => $user->salary)->isNotEmpty())
              <!-- your table -->
          @else
              <p class="text-red-500 mt-4 empty-list">Tidak ada data gaji untuk bulan dan tahun yang dipilih.</p>
          @endif
          <table class="min-w-full table-auto border-collapse">
            <thead>
              <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2" class="h-name">Nama</th>
                {{-- <th rowspan="2" class="py-2 border border-black bg-gray-500">Tempat, Tanggal Lahir</th> --}}
                {{-- <th rowspan="2" class="py-2 border border-black bg-gray-500">Tanggal diangkat</th> --}}

                <!-- Gaji Pokok with 3 sub-columns -->
                <th rowspan="2" >Gaji Pokok</th>

                <!-- Tunjangan -->
                <th>Tunjangan</th>

                <!-- Jumlah Gaji -->
                <th rowspan="2">Jumlah Gaji</th>

                <!-- Potongan with 3 sub-columns -->
                <th colspan="3">Potongan</th>

                <!-- Jumlah Bersih -->
                <th rowspan="2">Jumlah Bersih</th>

                <!-- TTD -->
                <th rowspan="2" class="h-ttd">TTD</th>
              </tr>
              <tr>
                <!-- Sub-columns for tunjangan -->
                <th class="h-tunjangan">Makan</th>
                {{-- <th class="py-2 border border-black bg-gray-500 h-tunjangan">Hari tua</th>
                <th class="py-2 border border-black bg-gray-500 h-tunjangan">Retase</th> --}}

                <!-- Sub-columns for Potongan -->
                <th class="h-potongan">BPJS</th>
                <th class="h-potongan">Tabungan hari tua</th>
                <th class="h-potongan">Kredit/kasbon</th>
              </tr>
            </thead>
            <tbody>
              @php $no = 1; @endphp
              @foreach($users as $user)
                @php
                  $salary = $user->salary;
                @endphp
                @if ($user->salary)
                  <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{$user->nama}}</td>
                    {{-- <td>{{ $user->tempat_lahir . ', ' . $user->tanggal_lahir->format('d M Y') }}</td> --}}
                    {{-- <td>{{$user->tanggal_diangkat->format('d F Y')}}</td> --}}

                    <td>Rp.{{number_format($salary->gaji_pokok, 0, ',', '.')}}</td>
                    <td>Rp.{{number_format($salary->tunjangan_makan, 0, ',', '.')}}</td>
                    {{-- <td>Rp.{{number_format($salary->tunjangan_hari_tua, 0, ',', '.')}}</td> --}}
                    {{-- <td>Rp.{{number_format($salary->tunjangan_retase, 0, ',', '.')}}</td> --}}
                    <td>Rp.{{number_format($salary->jumlah_gaji, 0, ',', '.')}}</td>
                    <td>Rp.{{number_format($salary->potongan_bpjs, 0, ',', '.')}}</td>
                    <td>Rp.{{number_format($salary->potongan_tabungan_hari_tua, 0, ',', '.')}}</td>
                    <td>Rp.{{number_format($salary->potongan_kredit_kasbon, 0, ',', '.')}}</td>
                    <td>Rp.{{number_format($salary->jumlah_bersih, 0, ',', '.')}}</td>
                    <td>
                      <img src="{{ asset('storage/ttd/' . $user->nama. '.png') }}" alt="{{ "ttd" . $user->nama }}" class="ttd">
                    </td>
                  </tr>
                @endif
              @endforeach
              <tr class="row-total">
                <td></td>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td>Rp.{{number_format($totalUsersSalary['totalTunjanganMakan'], 0)}}</td>
                {{-- <td>Rp.{{number_format($salary->tunjangan_hari_tua, 0, ',', '.')}}</td> --}}
                <td>Rp.{{number_format($totalUsersSalary['totalJumlahGaji'], 0)}}</td>
                <td>Rp.{{number_format($totalUsersSalary['totalPotonganBpjs'], 0)}}</td>
                <td>Rp.{{number_format($totalUsersSalary['totalPotonganHariTua'], 0)}}</td>
                <td>Rp.{{number_format($totalUsersSalary['totalPotonganKreditKasbon'], 0)}}</td>
                <td>Rp.{{number_format($totalUsersSalary['totalGeneral'], 0)}}</td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
    </div>
</body>
</html>
