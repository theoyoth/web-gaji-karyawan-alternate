<main>
  <h1 class="text-2xl font-bold text-center">FORMULIR INPUT TRANSPORTIR</h1>
  <h1 class="text-2xl font-bold text-center">AWAK 1 & AWAK 2</h1>
  <div class="mt-4">
    <form action="{{ route('user.storeAwak12') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Column -->
        <div class="space-y-2">
          <div>
            <label for="user_id" class="mb-1 block text-xs font-bold text-gray-700">Nama</label>
            <select name="user_id" id="user_id" required class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                @foreach ($users as $user)
                  <option value="{{ $user->id }}">{{ $user->nama }}</option>
                @endforeach
            </select>
            {{-- <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm"> --}}
            @error('nama')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
              <label for="kantor" class="mb-1 block text-xs font-bold text-gray-700">Kantor</label>
              <input type="text" id="kantor" name="kantor" value="awak 1 dan awak 2" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm" readonly>
              @error('kantor')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              @error('kantor')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label for="gaji_pokok" class="mb-1 block text-xs font-bold text-gray-700">Gaji pokok</label>
              <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
              @error('gaji_pokok')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label for="hari_kerja" class="mb-1 block text-xs font-bold text-gray-700">Hari kerja</label>
              <input type="number" id="hari_kerja" name="hari_kerja" value="{{ old('hari_kerja') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
              @error('hari_kerja')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
          </div>
          <div>
              <label for="bulan" class="mb-1 block text-xs font-bold text-gray-700">Bulan</label>
              <select name="bulan" value="{{ old('bulan') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                  <option value="Januari" {{ request('bulan') === 'Januari' ? 'selected' : '' }}>Januari</option>
                  <option value="Februari" {{ request('bulan') === 'Februari' ? 'selected' : '' }}>Februari</option>
                  <option value="Maret" {{ request('bulan') === 'Maret' ? 'selected' : '' }}>Maret</option>
                  <option value="April" {{ request('bulan') === 'April' ? 'selected' : '' }}>April</option>
                  <option value="Mei" {{ request('bulan') === 'Mei' ? 'selected' : '' }}>Mei</option>
                  <option value="Juni" {{ request('bulan') === 'Juni' ? 'selected' : '' }}>Juni</option>
                  <option value="Juli" {{ request('bulan') === 'Juli' ? 'selected' : '' }}>Juli</option>
                  <option value="Agustus" {{ request('bulan') === 'Agustus' ? 'selected' : '' }}>Agustus</option>
                  <option value="September" {{ request('bulan') === 'September' ? 'selected' : '' }}>September</option>
                  <option value="Oktober" {{ request('bulan') === 'Oktober' ? 'selected' : '' }}>Oktober</option>
                  <option value="November" {{ request('bulan') === 'November' ? 'selected' : '' }}>November</option>
                  <option value="Desember" {{ request('bulan') === 'Desember' ? 'selected' : '' }}>Desember</option>
              </select>
              @error('bulan')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
          </div>
            <div>
              <label for="tahun" class="mb-1 block text-xs font-bold text-gray-700">Tahun</label>
              <select name="tahun" id="tahun" required class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                  @for ($y = 2022; $y <= now()->year; $y++)
                      <option value="{{ $y }}" {{ (request('tahun') ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
              </select>
              @error('tahun')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
            <div class="flex items-center gap-10">
              <p class="block text-sm font-bold text-gray-700">Tunjangan</p>
              <div class="flex-1">
                <div>
                    <label for="tunjangan_makan" class="mb-1 block text-xs font-bold text-gray-700">Makan</label>
                    <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                    @error('tunjangan_makan')
                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div>
                    <label for="tunjangan_hari_tua" class="mb-1 block text-xs font-bold text-gray-700">Hari tua</label>
                    <input type="number" id="tunjangan_hari_tua" name="tunjangan_hari_tua" value="{{ old('tunjangan_hari_tua') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                    @error('tunjangan_hari_tua')
                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}
              </div>
            </div>
            <div class="flex items-center">
              <p class="block text-sm font-bold text-gray-700 mr-10">Potongan</p>
              <div class="flex-1 space-y-2">
                  <div>
                      <label for="potongan_bpjs" class="mb-1 block text-xs font-bold text-gray-700">BPJS</label>
                      <input type="number" id="potongan_bpjs" name="potongan_bpjs" value="{{ old('potongan_bpjs') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                      @error('potongan_bpjs')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                      <label for="potongan_tabungan_hari_tua" class="mb-1 block text-xs font-bold text-gray-700">Tabungan hari tua</label>
                      <input type="number" id="potongan_tabungan_hari_tua" name="potongan_tabungan_hari_tua" value="{{ old('potongan_tabungan_hari_tua') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                      @error('potongan_tabungan_hari_tua')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                      <label for="potongan_kredit_kasbon" class="mb-1 block text-xs font-bold text-gray-700">Kredit/Kasbon</label>
                      <input type="number" id="potongan_kredit_kasbon" name="potongan_kredit_kasbon" value="{{ old('potongan_kredit_kasbon') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                      @error('potongan_kredit_kasbon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
              </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="space-y-2">
          <div>
            <h2 class="text-lg font-bold mb-2">Input Retase</h2>
            <div id="delivery-wrapper">
              <div class="flex flex-col gap-1 pb-2 border-b-2 border-gray-300">
                <div>
                  <input type="text" name="deliveries[0][kota]" value="{{ $delivery['kota'] ?? '' }}" placeholder="kota" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                </div>
                <div>
                  <input type="number" name="deliveries[0][jumlah_retase]" value="{{ $delivery['jumlah_retase'] ?? '' }}" placeholder="jumlah retase" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                </div>
                <div>
                  <input type="number" name="deliveries[0][tarif_retase]" value="{{ $delivery['tarif_retase'] ?? '' }}" placeholder="tarif retase" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none focus:border-gray-600 shadow-sm">
                </div>
              </div>
            </div>
            <button type="button" onclick="addDeliveryRow()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded mt-4">+ Tambah Pengiriman</button>
          </div>

          {{-- TTD --}}
          <div id="form-awak">
            <label for="signature" class="mb-1 block text-xs font-bold text-gray-700">Tanda tangan</label>
            <canvas id="signature-pad-awak" width="200" height="100" class="bg-white border-2 shadow-sm border-gray-200 active:border-gray-600"></canvas>
            <input type="hidden" name="ttd" id="ttd">
            <p class="text-gray-500 text-xs mt-1">Gambar tanda tangan di atas</p>
            <div class="flex mt-2">
              <button type="button" id="clear-awak" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-md"><i class="fas fa-broom text-sm mr-1"></i>Clear</button>
            </div>
          </div>
        </div>
      </div>
      <div class="w-full my-6">
        <button type="submit" value="submit_data" class="w-full bg-green-600 text-white font-semibold py-2 px-6 rounded hover:bg-green-700 focus:outline-none">
          <i class="fas fa-paper-plane text-lg mr-1"></i>
          submit
        </button>
      </div>
    </form>
  </div>
</main>
