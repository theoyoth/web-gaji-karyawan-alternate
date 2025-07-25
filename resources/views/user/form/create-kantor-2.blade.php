<main>
  <h1 class="text-4xl font-bold text-center">FORMULIR INPUT KANTOR</h1>
  <div class="mt-4">
      @if(session('error'))
          <div id="error-msg" class="bg-red-100 text-red-600 p-2 text-sm rounded my-2">
              {{ session('error') }}
          </div>
          <script>
              setTimeout(() => {
                  const msg = document.getElementById('error-msg');
                  if (msg) msg.style.display = 'none';
              }, 4000);
          </script>
      @endif
      <form action="{{ route('user.store') }}" method="POST">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div class="space-y-2">
                  <div>
                    <label for="nama" class="mb-1 block text-xs font-bold text-gray-800">Nama</label>
                    <select name="user_id" id="user_id" required class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-200 shadow-sm">
                        @foreach ($users as $user)
                          <option value="{{ $user->id }}">{{ $user->nama }}</option>
                        @endforeach
                    </select>
                    {{-- Checkbox --}}
                    <label class="block mt-2">
                        <input type="checkbox" id="new_user_checkbox" name="new_user_checkbox" value="1">
                        Belum terdaftar
                    </label>

                    {{-- New User Input (Hidden by Default) --}}
                    <input type="text" id="nama" name="nama" class="w-full h-10 px-2 mt-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600" style="display: none;">

                    @error('nama')
                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <div>
                    <label for="kantor" class="mb-1 block text-xs font-bold text-gray-800">Kantor</label>
                    <input type="text" id="kantor" name="kantor" value="kantor 2" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-200 shadow-sm" readonly>
                    @error('kantor')
                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  {{-- <div>
                      <label for="tempat_lahir" class="mb-1 block text-xs font-bold text-gray-800">Tempat lahir</label>
                      <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                      @error('tempat_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                      <label for="tanggal_lahir" class="mb-1 block text-xs font-bold text-gray-800">Tanggal lahir</label>
                      <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                      @error('tanggal_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                      <label for="tanggal_diangkat" class="mb-1 block text-xs font-bold text-gray-800">Tanggal diangkat</label>
                      <input type="date" id="tanggal_diangkat" name="tanggal_diangkat" value="{{ old('tanggal_diangkat') }}"  class="w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                      @error('tanggal_diangkat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div> --}}
                  <div>
                      <label for="gaji_pokok" class="mb-1 block text-xs font-bold text-gray-800">Gaji pokok</label>
                      <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                      @error('gaji_pokok')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                      <label for="hari_kerja" class="mb-1 block text-xs font-bold text-gray-800">Hari kerja</label>
                      <input type="number" id="hari_kerja" name="hari_kerja" value="{{ old('hari_kerja') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                      @error('hari_kerja')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div>
                    <label for="bulan" class="mb-1 block text-xs font-bold text-gray-800">Bulan</label>
                    <select name="bulan" value="{{ old('bulan') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
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
                      <label for="tahun" class="mb-1 block text-xs font-bold text-gray-800">Tahun</label>
                      <select name="tahun" id="tahun" required class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                          @for ($y = 2022; $y <= now()->year; $y++)
                              <option value="{{ $y }}" {{ (request('tahun') ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                          @endfor
                      </select>
                      @error('tahun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                      @enderror
                  </div>
                  <div class="flex items-center gap-10">
                    <p class="block text-sm font-bold text-gray-800">Tunjangan</p>
                    <div class="flex-1 space-y-2">
                        <div>
                            <label for="tunjangan_makan" class="mb-1 block text-xs font-bold text-gray-800">Makan</label>
                            <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                            @error('tunjangan_makan')
                              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="tunjangan_hari_tua" class="mb-1 block text-xs font-bold text-gray-800">Hari tua</label>
                            <input type="number" id="tunjangan_hari_tua" name="tunjangan_hari_tua" value="{{ old('tunjangan_hari_tua') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                            @error('tunjangan_hari_tua')
                              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                  </div>
              </div>

              <!-- Right Column -->
              <div class="space-y-4">
                <div class="flex items-center">
                  <p class="block text-sm font-bold text-gray-800 mr-10">Potongan</p>
                  <div class="flex-1 space-y-2">
                      <div>
                          <label for="potongan_bpjs" class="mb-1 block text-xs font-bold text-gray-800">BPJS</label>
                          <input type="number" id="potongan_bpjs" name="potongan_bpjs" value="{{ old('potongan_bpjs') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                          @error('potongan_bpjs')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                          @enderror
                      </div>
                      <div>
                          <label for="potongan_tabungan_hari_tua" class="mb-1 block text-xs font-bold text-gray-800">Tabungan hari tua</label>
                          <input type="number" id="potongan_tabungan_hari_tua" name="potongan_tabungan_hari_tua" value="{{ old('potongan_tabungan_hari_tua') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                          @error('potongan_tabungan_hari_tua')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                          @enderror
                      </div>
                      <div>
                          <label for="potongan_kredit_kasbon" class="mb-1 block text-xs font-bold text-gray-800">Kredit/Kasbon</label>
                          <input type="number" id="potongan_kredit_kasbon" name="potongan_kredit_kasbon" value="{{ old('potongan_kredit_kasbon') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                          @error('potongan_kredit_kasbon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                          @enderror
                      </div>
                  </div>
                </div>
                {{-- <div>
                  <label for="jumlah_gaji" class="block text-xs font-bold text-gray-800">Jumlah gaji</label>
                  <input type="number" id="jumlah_gaji" name="jumlah_gaji" value="{{ old('jumlah_gaji') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                  @error('jumlah_gaji')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                  @enderror
                </div> --}}
                {{-- TTD --}}
                <div id="form-kantor">
                  <label for="signature" class="mb-1 block text-xs font-bold text-gray-800">Tanda tangan</label>
                  <canvas id="signature-pad-kantor-2" width="200" height="100" class="bg-white border-2 shadow-sm border-gray-200 active:border-gray-600"></canvas>
                  <input type="hidden" name="ttd" id="ttd">
                  <p class="text-gray-500 text-xs mt-1">Gambar tanda tangan di atas</p>
                  <div class="flex mt-2">
                    <button type="button" id="clear-kantor" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-md"><i class="fas fa-broom text-sm mr-1"></i>Clear</button>
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
      <script>
        function updateKantorInUrl(newValue) {
          const params = new URLSearchParams(window.location.search);
          params.set('kantor', newValue);

          const newUrl = `${window.location.pathname}?${params.toString()}`;
          window.history.pushState({}, '', newUrl);
        }
        const checkbox = document.getElementById('new_user_checkbox');
        const namaInput = document.getElementById('nama');
        const userSelect = document.getElementById('user_id');

        checkbox.addEventListener('change', function () {
            if (this.checked) {
                namaInput.style.display = 'block';
                userSelect.disabled = true;
                // userSelect.value = ""; 
            } else {
                namaInput.style.display = 'none';
                userSelect.disabled = false;
            }
        });
      </script>

</div>
</main>
