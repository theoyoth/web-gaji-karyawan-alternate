<!-- resources/views/user/print.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/table.css')
    <title>Gaji karyawan - alternate</title>
</head>
<body>
    <div class="px-4">
      <div class="kop-surat">
        <h1 class="header-text">PT.GUNUNG SELATAN</h3>
        <h1 class="header-subtext">KONTRAKTOR & LEVERANSIR</h1>
        <h1 class="header-subtext">NABIRE - PAPUA</h1>
        <h1 class="small-text">Alamat: Jln. R.E.Martadinata No.216, Telp: (0984) 21722, Bank: Mandiri & BPD</h1>
      </div>
      <div>
        {{-- <h1 class="header-subtext">GAJI KARYAWAN TRANSPORTIR AWAK 1 DAN AWAK 2</h3> --}}
        <h1 class="subtext">DAFTAR :  GAJI KARYAWAN & KARYAWATI {{request('kantor') === 'all' ? 'GUNUNG SELATAN' : request('kantor')}}</h1>
        <h1 class="subtext">BULAN : {{ $month ?? '' }} {{ $year ?? '' }}</h3>
      </div>


      <div>
        <a href="{{ route('users.index',['kantor' => 'all']) }}" class="link-button"><- Kembali</a>
        <button class="print-button" onclick="window.print()">🖨️ Print</button>
      </div>

      <fieldset class="fieldset-btn-filter">
        <legend class="legend-btn-filter">filter</legend>
        <div class="container-btn-filter">
          <a href="{{ route('users.print', ['kantor' => 'all']) }}"
            class="btn-filter-kantor {{ request('kantor') == 'all' ? 'btn-filter-active' : 'btn-filter-deactive' }}">
            <i class="fas fa-bars text-lg mr-1"></i>
            All
          </a>
          <a href="{{ route('users.print', ['kantor' => 'awak 1 dan awak 2']) }}"
            class="btn-filter-kantor {{ request('kantor') == 'awak 1 dan awak 2' ? 'btn-filter-active' : 'btn-filter-deactive' }}">
            <i class="fas fa-print text-lg mr-1"></i>
            Awak 1 dan Awak 2
          </a>
          <a href="{{ route('users.print', ['kantor' => 'kantor 1']) }}"
            class="btn-filter-kantor {{ request('kantor') == 'kantor 1' ? 'btn-filter-active' : 'btn-filter-deactive' }}">
            <i class="fas fa-building text-lg mr-1"></i>
            Kantor 1
          </a>
          <a href="{{ route('users.print', ['kantor' => 'kantor 2']) }}"
            class="btn-filter-kantor {{ request('kantor') == 'kantor 2' ? 'btn-filter-active' : 'btn-filter-deactive' }}">
            <i class="fas fa-building text-lg mr-1"></i>
            Kantor 2
          </a>
        </div>
        <form method="GET" action="{{ route('users.print.filter') }}">
          <input type="hidden" name="kantor" value="{{ request('kantor') }}">
          <select name="bulan" required class="select-input-filter">
            <option value="">-- Pilih Bulan --</option>
            @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
              <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
            @endforeach
          </select>
          <select name="tahun" required class="select-input-filter">
            <option value="">-- Pilih Tahun --</option>
            @for ($y = 2023; $y <= now()->year; $y++)
              <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
          <button type="submit" class="select-input-filter">Filter</button>
          {{-- Reset Filter Button --}}
          @php
            $kan = request('kantor');
          @endphp
          @if(request('bulan') || request('tahun'))
            <a href="{{ route('users.print',['kantor' => $kan]) }}" class="select-input-filter btn-reset-filter">Reset</a>
          @endif
        </form>
      </fieldset>

      <div class="bg-gray-100">
        @if($users->filter(fn($user) => $user->salary)->isNotEmpty())
            <!-- your table -->
        @else
            <p class="empty-list">Tidak ada data gaji untuk bulan dan tahun yang dipilih.</p>
        @endif
        <table class="table-auto border-collapse">
          <thead>
            <tr>
              <th rowspan="2">No.</th>
              <th rowspan="2" class="h-name">Nama</th>
              <!-- Gaji Pokok with 3 sub-columns -->
              <th rowspan="2">Gaji Pokok</th>
              <!-- hari kerja -->
              <th rowspan="2">Hari Kerja</th>
              <!-- jumlah retase -->
              <th colspan="2" class="h-retase">Jumlah Retase</th>
              <!-- tarif retase -->
              <th rowspan="2">Tarif Retase</th>
              <!-- Tunjangan -->
              <th>Tunjangan</th>
              <!-- jumlah ur -->
              <th rowspan="2">Jumlah UR</th>
              <!-- Jumlah Kotor -->
              <th rowspan="2" class="h-jumlah">Jumlah Gaji</th>
              <!-- Potongan with 3 sub-columns -->
              <th colspan="3">Potongan</th>
              <!-- Jumlah Bersih -->
              <th rowspan="2" class="h-jumlah">Jumlah Bersih</th>
              <!-- TTD -->
              <th rowspan="2" class="h-ttd">TTD</th>
            </tr>
            <tr>
              <!-- Sub-columns jumlah retase -->
              <th></th>
              <th></th>
              <!-- Sub-columns for tunjangan -->
              <th class="h-tunjangan">Makan</th>
              <!-- Sub-columns for Potongan -->
              <th class="h-potongan">BPJS</th>
              <th class="h-potongan">Tabungan hari tua</th>
              <th class="h-potongan">Kredit/kasbon</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1; @endphp
              @foreach($users as $user)
                @if ($user->salary)
                  @php 
                    $salary = $user->salary;
                    $deliveries = $salary ? $salary->deliveries : collect(); // assign deliveries or empty []
                    $deliveryCount = $deliveries->count() ?: 1; // length of deliveries in user's salary
                  @endphp
                  @for($i = 0; $i < $deliveryCount; $i++)
                    @php $delivery = $deliveries[$i] ?? null; @endphp
                    <tr>
                      @if($i === 0)
                        <td rowspan="{{ $deliveryCount }}">{{ $no++ }}</td>
                        <td rowspan="{{ $deliveryCount }}" class="user-name">{{$user->nama}}</td>
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->gaji_pokok, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">{{$salary->hari_kerja}}</td>
                      @endif

                      <td>{{ $delivery ? $delivery->jumlah_retase : "-" }}</td>
                      <td>{{ $delivery ? $delivery->kota : "-" }}</td>
                      <td>Rp{{ $delivery ? number_format($delivery->tarif_retase, 0, ',', '.') : "-" }}</td>
                      <td>Rp{{ $delivery ? number_format($delivery->jumlah_ur, 0, ',', '.') : "-"}}</td>
                      
                      @if($i === 0)
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->tunjangan_makan, 0, ',', '.')}}</td>
                        {{-- <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->tunjangan_hari_tua, 0, ',', '.')}}</td> --}}
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->jumlah_gaji, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->potongan_bpjs, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->potongan_tabungan_hari_tua, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->potongan_kredit_kasbon, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">Rp{{number_format($salary->jumlah_bersih, 0, ',', '.')}}</td>
                        <td rowspan="{{ $deliveryCount }}">
                          <img src="{{ file_exists(public_path('storage/ttd/' . $user->nama . '.png')) ? asset('storage/ttd/' . $user->nama . '.png') : '' }}" alt="ttd">
                        </td>
                      @endif
                    </tr>
                  @endfor
                @endif
              @endforeach
              <tr class="row-total">
                <td></td>
                <td colspan="6"><strong>TOTAL</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalTunjanganMakan'], 0)}}</strong></td>
                {{-- <td>Rp.{{number_format($salary->tunjangan_hari_tua, 0, ',', '.')}}</td> --}}
                <td><strong>Rp.{{number_format($totalUsersSalary['totalJumlahRetase'], 0)}}</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalJumlahGaji'], 0)}}</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalPotonganBpjs'], 0)}}</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalPotonganHariTua'], 0)}}</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalPotonganKreditKasbon'], 0)}}</strong></td>
                <td><strong>Rp.{{number_format($totalUsersSalary['totalGeneral'], 0)}}</strong></td>
                <td></td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
</body>
</html>
