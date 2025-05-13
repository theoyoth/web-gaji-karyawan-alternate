@extends('layout.main')

@section('content')
<div class="container-fluid px-4">
    <main class="min-h-screen flex justify-center items-center">
        <div class="w-1/2 m-auto py-2 px-10 bg-gray-100 rounded-lg border border-black my-4">
            <a href="{{ route('awak12.index') }}" class="inline-block my-4 px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
              <- kembali
            </a>
            <h1 class="text-2xl font-bold text-center">FORMULIR INPUT TRANSPORTIR</h1>
            <h1 class="text-2xl font-bold text-center">AWAK 1 & AWAK 2</h1>
            <div class="mt-8">
                <form action="{{ route('user.storeAwak12') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                @error('nama')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kantor" class="block text-sm font-medium text-gray-700">Kantor</label>
                                <select name="kantor" id="kantor" required class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                    @foreach (['awak 1 dan awak 2'] as $kan)
                                        <option value="{{ $kan }}" {{ request('kan') ? 'selected' : '' }}>{{ $kan }}</option>
                                    @endforeach
                                </select>
                                @error('kantor')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="gaji_pokok" class="block text-sm font-medium text-gray-700">Gaji pokok</label>
                                <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                @error('gaji_pokok')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="hari_kerja" class="block text-sm font-medium text-gray-700">Hari kerja</label>
                                <input type="number" id="hari_kerja" name="hari_kerja" value="{{ old('hari_kerja') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                @error('hari_kerja')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                                <select name="bulan" value="{{ old('bulan') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm focus:border-blue-500">
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                                @error('bulan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                                <select name="tahun" id="tahun" required class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                    @for ($y = 2020; $y <= now()->year; $y++)
                                        <option value="{{ $y }}" {{ (request('tahun') ?? now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-10">
                              <p class="block text-sm font-medium text-gray-700">Tunjangan</p>
                              <div class="flex-1">
                                  <div class="mt-2">
                                      <label for="tunjangan_makan" class="block text-sm font-medium text-gray-700">Makan</label>
                                      <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      @error('tunjangan_makan')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                      @enderror
                                  </div>
                                  {{-- <div class="mt-2">
                                      <label for="tunjangan_hari_tua" class="block text-sm font-medium text-gray-700">Hari tua</label>
                                      <input type="number" id="tunjangan_hari_tua" name="tunjangan_hari_tua" value="{{ old('tunjangan_hari_tua') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      @error('tunjangan_hari_tua')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                      @enderror
                                  </div> --}}
                              </div>
                            </div>
                            <div class="flex items-center">
                              <p class="block text-sm font-medium text-gray-700 mr-10">Potongan</p>
                              <div class="flex-1">
                                  <div class="mt-2">
                                      <label for="potongan_bpjs" class="block text-sm font-medium text-gray-700">BPJS</label>
                                      <input type="number" id="potongan_bpjs" name="potongan_bpjs" value="{{ old('potongan_bpjs') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      @error('potongan_bpjs')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                      @enderror
                                  </div>
                                  <div class="mt-2">
                                      <label for="potongan_tabungan_hari_tua" class="block text-sm font-medium text-gray-700">Tabungan hari tua</label>
                                      <input type="number" id="potongan_tabungan_hari_tua" name="potongan_tabungan_hari_tua" value="{{ old('potongan_tabungan_hari_tua') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      @error('potongan_tabungan_hari_tua')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                      @enderror
                                  </div>
                                  <div class="mt-2">
                                      <label for="potongan_kredit_kasbon" class="block text-sm font-medium text-gray-700">Kredit/Kasbon</label>
                                      <input type="number" id="potongan_kredit_kasbon" name="potongan_kredit_kasbon" value="{{ old('potongan_kredit_kasbon') }}" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      @error('potongan_kredit_kasbon')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                      @enderror
                                  </div>
                              </div>
                          </div>

                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <div class="">
                              <h2 class="text-lg font-bold mb-2">Input Retase</h2>
                              <div id="delivery-wrapper">
                                  <div class="flex flex-col gap-1 pb-2 border-b border-gray-500">
                                      <div>
                                        <input type="text" name="deliveries[0][kota]" value="{{ $delivery['kota'] ?? '' }}" placeholder="kota" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      </div>
                                      <div>
                                        <input type="number" name="deliveries[0][jumlah_retase]" value="{{ $delivery['jumlah_retase'] ?? '' }}" placeholder="jumlah retase" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      </div>
                                      <div>
                                        <input type="number" name="deliveries[0][tarif_retase]" value="{{ $delivery['tarif_retase'] ?? '' }}" placeholder="tarif retase" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                                      </div>
                                  </div>
                              </div>
                              <button type="button" onclick="addDeliveryRow()" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded mt-4">+ Tambah Pengiriman</button>
                            </div>

                            {{-- TTD --}}
                            <div class="mt-4">
                                <label for="signature" class="block text-sm font-medium text-gray-700">Tanda tangan</label>
                                <canvas id="signature-pad" width="200" height="100" style="border: 1px solid #000;"></canvas>
                                <input type="hidden" name="ttd" id="ttd">
                                <p class="text-gray-500 text-xs mt-1">Gambar tanda tangan di atas</p>
                                <div class="flex mt-2">
                                    <button type="button" id="clear" class="mr-4 bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-md">Clear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full my-6">
                        <button type="submit" value="submit_data" class="w-full bg-green-600 text-white font-semibold py-2 px-6 rounded hover:bg-green-700 focus:outline-none">
                            submit
                        </button>
                    </div>
                </form>
                <!-- Include Signature Pad JS -->
                <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

                <script>
                  // input signature
                    const canvas = document.getElementById('signature-pad');
                    const signaturePad = new SignaturePad(canvas);

                    document.querySelector('form').addEventListener('submit', function (e) {
                      if (!signaturePad.isEmpty()) {
                        document.getElementById('ttd').value = signaturePad.toDataURL();
                      } else {
                        document.getElementById('ttd').value = '';
                      }

                    });

                    // Clear the signature pad
                    document.getElementById('clear').addEventListener('click', function () {
                        signaturePad.clear();
                    });

                    // setting for multiple input retase
                    let deliveryIndex = 1;

                    function addDeliveryRow() {
                        const wrapper = document.getElementById('delivery-wrapper');
                        const row = document.createElement('div');
                        row.className = 'delivery-row flex flex-col gap-1 pb-2 border-b border-gray-500';
                        row.innerHTML = `
                            <input type="text" name="deliveries[${deliveryIndex}][kota]" placeholder="Kota" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                            <input type="number" name="deliveries[${deliveryIndex}][jumlah_retase]" placeholder="Jumlah retase" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">
                            <input type="number" name="deliveries[${deliveryIndex}][tarif_retase]" placeholder="Tarif retase" class="mt-1 outline-1 w-full h-10 px-2 rounded-md border-2 border-gray-300 shadow-sm">

                            <button type="button" onclick="removeDeliveryRow(this)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Hapus</button>
                        `;
                        wrapper.appendChild(row);
                        deliveryIndex++;
                    }

                    function removeDeliveryRow(button) {
                        button.parentElement.remove();
                    }

                    // add shortcut to submit
                    document.addEventListener('keydown', function (event) {
                      if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                        event.preventDefault(); // prevent browser's default save dialog

                        const form = document.querySelector('form');
                        if (form) {
                          form.submit();
                        }
                      }
                      if (event.ctrlKey && event.shiftKey && event.key.toLowerCase() === 'a') {
                        event.preventDefault(); // prevent browser's default save dialog
                        addDeliveryRow();
                      }
                    });
                </script>
        </div>
    </main>
@endsection
