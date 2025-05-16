<main>
  <table class="min-w-full table-auto border-collapse text-[0.8rem]">
    @php
       $awakData = $users->filter(fn($user) => $user->salary && $user->salary->deliveries->isNotEmpty());
    @endphp
      <thead>
        <tr>
          <th rowspan="2" class="py-2 w-5 border border-gray-400 bg-gray-300">No.</th>
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 w-[120px]">Nama</th>
          <!-- Gaji Pokok with 3 sub-columns -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Gaji Pokok</th>

          <!-- hari kerja -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Hari Kerja</th>
          <!-- jumlah retase -->
          <th colspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Jumlah Retase</th>
          <!-- tarif retase -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Tarif Retase</th>
          <!-- jumlah ur -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300">Jumlah UR</th>
          
          <!-- Tunjangan -->
          <th class="py-2 border border-gray-400 bg-gray-300">Tunjangan</th>
          <!-- Jumlah Kotor -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300">Jumlah Gaji</th>
          <!-- Potongan with 3 sub-columns -->
          <th colspan="3" class="py-2 border border-gray-400 bg-gray-300 text-center">Potongan</th>
          <!-- Jumlah Bersih -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300">Jumlah Bersih</th>
          <!-- TTD -->
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 w-[50px]">TTD</th>
          <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 w-[50px]"></th>
        </tr>
        <tr>
          <!-- Sub-columns jumlah retase -->
          <th class="py-2 border border-gray-400 bg-gray-300 w-[120px]"></th>
          <th class="py-2 border border-gray-400 bg-gray-300 w-[150px]"></th>
          <!-- Sub-columns for tunjangan -->
          <th class="py-2 border border-gray-400 bg-gray-300 w-[120px]">Makan</th>
          <!-- Sub-columns for Potongan -->
          <th class="py-2 border border-gray-400 bg-gray-300 w-[120px]">BPJS</th>
          <th class="py-2 border border-gray-400 bg-gray-300 w-[120px]">Tabungan hari tua</th>
          <th class="py-2 border border-gray-400 bg-gray-300">Kredit/kasbon</th>
        </tr>
      </thead>
      <tbody>
          @php $no = 1; @endphp
          @foreach($awakData as $user)
            @php
              $salary = $user->salary;
              $deliveryCountRowspan = $salary->deliveries->count(); // use 1 to show one row
            @endphp
            @foreach ($salary->deliveries as $index => $delivery)
              <tr>
                @if($index === 0)
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center border border-gray-400">{{ $no++ }}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center border border-gray-400 uppercase">{{$user->nama}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center border border-gray-400">Rp{{number_format($salary->gaji_pokok, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center border border-gray-400">{{$salary->hari_kerja}}</td>
                @endif
                
                <td class="text-center py-1 border border-gray-400">{{ $delivery ? $delivery->jumlah_retase : "-" }}</td>
                <td class="text-center py-1 border border-gray-400">{{ $delivery ? $delivery->kota : "-" }}</td>
                <td class="text-center py-1 border border-gray-400">Rp{{ $delivery ? number_format( $delivery->tarif_retase, 0, ',', '.') : "-" }}</td>
                <td class="text-center py-1 border border-gray-400">Rp{{ $delivery ? number_format( $delivery->jumlah_ur, 0, ',', '.') : "-"}}</td>

                @if($index === 0)
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->tunjangan_makan, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->jumlah_gaji, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_bpjs, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_tabungan_hari_tua, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_kredit_kasbon, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->jumlah_bersih, 0, ',', '.')}}</td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center py-1 border border-gray-400">
                      <img src="{{ file_exists(public_path('storage/ttd/' . $user->nama . '.png')) ? asset('storage/ttd/' . $user->nama . '.png') : '' }}" alt="ttd" class="w-20 h-20 object-contain">
                  </td>
                  <td rowspan="{{ $deliveryCountRowspan }}" class="text-center border border-gray-400">
                    <div class="flex flex-col gap-1 items-center">
                      <a href="{{ route('edit.awak12', ['user' => $user->id, 'page' => request()->get('page', 1)]) }}" class="bg-blue-500 hover:bg-blue-600 rounded py-1 px-2"><i class="fa fa-edit text-white text-xs"></i></a>
                      <form action="{{ route('user.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 py-1 px-2 rounded">
                          <i class="fas fa-trash text-white text-xs"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                @endif
              </tr>
            @endforeach
          @endforeach
          {{-- total each page pagination --}}
          <tr>
            <td class="text-center border border-gray-500"></td>
            <td colspan="2" class="border-b border-gray-500"><strong>TOTAL PER HALAMAN</strong></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-gray-500"></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalTunjanganMakan'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalJumlahRetase'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalJumlahGaji'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalPotonganBpjs'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalPotonganHariTua'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalPotonganKreditKasbon'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($pageTotals['totalGeneral'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"></td>
            <td class="text-center border border-gray-500"></td>
          </tr>
          @if ($totalUsersSalary)
          {{-- total all users salary --}}
          <tr class="text-lg bg-gray-300 text-gray-900 font-semibold">
            <td class="text-center border border-gray-500"></td>
            <td colspan="2" class="border-b border-gray-500"><strong>TOTAL SEMUA</strong></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-b-gray-500"></td>
            <td class="text-center border-b border-gray-500"></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalTunjanganMakan'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalJumlahRetase'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalJumlahGaji'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalPotonganBpjs'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalPotonganHariTua'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalPotonganKreditKasbon'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"><strong>Rp{{ number_format($totalUsersSalary['totalGeneral'], 0) }}</strong></td>
            <td class="text-center border border-gray-500"></td>
            <td class="text-center border border-gray-500"></td>
          </tr>
          @endif
      </tbody>
  </table>
  <!-- Tailwind-styled pagination -->
  <div class="mt-4 flex justify-center">
      {{ $users->links() }}
  </div>
</main>