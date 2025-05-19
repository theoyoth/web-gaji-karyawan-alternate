@extends('layout.main')

@section('content')
<div class="container-fluid px-4">
    <main class="min-h-screen flex justify-center items-center">
        <div class="w-1/2 m-auto py-2 px-10 bg-gray-100 rounded-lg border border-black my-4">
            <a href="{{ route('users.index',['kantor' => 'all']) }}" class="flex items-center max-w-max my-4 px-4 py-1 bg-gray-700 text-white rounded-md hover:bg-gray-800">
              <i class="fas fa-arrow-left text-lg text-gray-100 mr-1"></i> kembali
            </a>
            <h1 class="text-3xl font-bold text-center">EDIT KANTOR 1 & KANTOR 2</h1>
            <div class="mt-8">
                <form action="{{ route('update.kantor', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                      @php
                        $salary = $user->salary;
                      @endphp
                      @if($user->salary)
                      {{-- send hidden page pagination number to backend --}}
                      <input type="hidden" name="page" value="{{ request('page') }}">

                        <!-- Left Column -->
                        <div class="space-y-2">
                          <div>
                              <label for="nama" class="mb-1 block text-xs font-bold text-gray-800">Nama</label>
                              <input type="text" id="nama" name="nama" value="{{ old('nama',$user->nama) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                              @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div>
                              <label for="kantor" class="mb-1 block text-xs font-bold text-gray-800">Kantor</label>
                              <select name="kantor" id="kantor" required class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                  @foreach (['kantor 1','kantor 2'] as $kan)
                                      <option value="{{ $kan }}" {{ $user->kantor == $kan ? 'selected' : '' }}>{{ $kan }}</option>
                                  @endforeach
                              </select>
                              @error('kantor')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div>
                              <label for="gaji_pokok" class="mb-1 block text-xs font-bold text-gray-800">Gaji pokok</label>
                              <input type="number" id="gaji_pokok" name="gaji_pokok" value="{{ old('gaji_pokok',$salary->gaji_pokok) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                              @error('gaji_pokok')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div>
                              <label for="hari_kerja" class="mb-1 block text-xs font-bold text-gray-800">Hari kerja</label>
                              <input type="number" id="hari_kerja" name="hari_kerja" value="{{ old('hari_kerja',$salary->hari_kerja) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                              @error('hari_kerja')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div>
                              <label for="bulan" class="mb-1 block text-xs font-bold text-gray-800">Bulan</label>
                              <select name="bulan" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                  <option value="Januari" {{ old('bulan', $salary->bulan) == 'Januari' ? 'selected' : '' }}>Januari</option>
                                  <option value="Februari" {{ old('bulan', $salary->bulan) == 'Februari' ? 'selected' : '' }}>Februari</option>
                                  <option value="Maret" {{ old('bulan', $salary->bulan) == 'Maret' ? 'selected' : '' }}>Maret</option>
                                  <option value="April" {{ old('bulan', $salary->bulan) == 'April' ? 'selected' : '' }}>April</option>
                                  <option value="Mei" {{ old('bulan', $salary->bulan) == 'Mei' ? 'selected' : '' }}>Mei</option>
                                  <option value="Juni" {{ old('bulan', $salary->bulan) == 'Juni' ? 'selected' : '' }}>Juni</option>
                                  <option value="Juli" {{ old('bulan', $salary->bulan) == 'Juli' ? 'selected' : '' }}>Juli</option>
                                  <option value="Agustus" {{ old('bulan', $salary->bulan) == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                  <option value="September" {{ old('bulan', $salary->bulan) == 'September' ? 'selected' : '' }}>September</option>
                                  <option value="Oktober" {{ old('bulan', $salary->bulan) == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                  <option value="November" {{ old('bulan', $salary->bulan) == 'November' ? 'selected' : '' }}>November</option>
                                  <option value="Desember" {{ old('bulan', $salary->bulan) == 'Desember' ? 'selected' : '' }}>Desember</option>
                              </select>
                              @error('bulan')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div>
                              <label for="tahun" class="mb-1 block text-xs font-bold text-gray-800">Tahun</label>
                              <select name="tahun" id="tahun" required class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                  @for ($y = 2022; $y <= now()->year; $y++)
                                      <option value="{{ $y }}" {{ $salary->tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                  @endfor
                              </select>
                              @error('tahun')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                              @enderror
                          </div>
                          <div class="flex items-center gap-10">
                            <p class="block text-sm font-bold text-gray-700 mr-10">Tunjangan</p>
                            <div class="flex-1">
                                <div class="mt-2">
                                    <label for="tunjangan_makan" class="mb-1 block text-xs font-bold text-gray-800">Makan</label>
                                    <input type="number" id="tunjangan_makan" name="tunjangan_makan" value="{{ old('tunjangan_makan',$salary->tunjangan_makan) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                    @error('tunjangan_makan')
                                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-2">
                                    <label for="tunjangan_hari_tua" class="mb-1 block text-xs font-bold text-gray-800">Hari tua</label>
                                    <input type="number" id="tunjangan_hari_tua" name="tunjangan_hari_tua" value="{{ old('tunjangan_hari_tua') }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                    @error('tunjangan_hari_tua')
                                      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                          </div>

                        </div>
                        {{-- right column --}}
                        {{-- TTD --}}
                        <div>
                          <div class="flex items-center">
                            <p class="block text-sm font-bold text-gray-700 mr-10">Potongan</p>
                            <div class="flex-1">
                              <div class="mt-2">
                                  <label for="potongan_bpjs" class="mb-1 block text-xs font-bold text-gray-800">BPJS</label>
                                  <input type="number" id="potongan_bpjs" name="potongan_bpjs" value="{{ old('potongan_bpjs',$salary->potongan_bpjs) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                  @error('potongan_bpjs')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                  @enderror
                              </div>
                              <div class="mt-2">
                                  <label for="potongan_tabungan_hari_tua" class="mb-1 block text-xs font-bold text-gray-800">Tabungan hari tua</label>
                                  <input type="number" id="potongan_tabungan_hari_tua" name="potongan_tabungan_hari_tua" value="{{ old('potongan_tabungan_hari_tua',$salary->potongan_tabungan_hari_tua) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                  @error('potongan_tabungan_hari_tua')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                  @enderror
                              </div>
                              <div class="mt-2">
                                <label for="potongan_kredit_kasbon" class="mb-1 block text-xs font-bold text-gray-800">Kredit/Kasbon</label>
                                <input type="number" id="potongan_kredit_kasbon" name="potongan_kredit_kasbon" value="{{ old('potongan_kredit_kasbon',$salary->potongan_kredit_kasbon) }}" class="w-full h-10 px-2 rounded-md border-2 border-gray-200 outline-none shadow-sm focus:border-gray-600">
                                @error('potongan_kredit_kasbon')
                                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                              </div>
                            </div>
                          </div>
                          {{-- TTD --}}
                          <input type="hidden" name="delete_ttd" id="delete_ttd" value="0">
                          <div class="mt-4">
                              <label for="signature" class="mb-1 block text-xs font-bold text-gray-800">Tanda tangan</label>
                              <canvas id="signature-pad" width="200" height="100" class="bg-white border-2 shadow-sm border-gray-200 active:border-gray-600" data-image="{{ $salary->ttd ? asset('storage/ttd/' . $salary->ttd) : '' }}"></canvas>
                              <input type="hidden" name="ttd" id="ttd" value="{{ old('ttd', $salary->ttd ?? '') }}">
                              <p class="text-gray-500 text-xs mt-1">Gambar tanda tangan di atas</p>
                              <div class="flex mt-2">
                                <button type="button" disabled id="clear" class="mr-4 bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-md"><i class="fas fa-broom text-sm mr-1"></i>Clear</button>
                              </div>
                          </div>
                        </div>
                      @endif
                    </div>
                    <div class="w-full my-6">
                        <button type="submit" value="submit_data" class="w-full bg-green-600 text-white font-semibold py-2 px-6 rounded hover:bg-green-700 focus:outline-none">
                          <i class="fas fa-paper-plane text-lg mr-1"></i>
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
                  const ttdInput = document.getElementById('ttd');
                  const clearBtn = document.getElementById('clear');
                  
                  // Disable clear button by default
                  function disableClearButton() {
                    clearBtn.disabled = true;
                    clearBtn.classList.add('opacity-50', 'cursor-not-allowed');
                  }

                  function enableClearButton() {
                    clearBtn.disabled = false;
                    clearBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                  }

                  // Load existing signature if available
                  const existingImage = canvas.dataset.image;
                  if (existingImage) {
                    const img = new Image();
                    img.src = existingImage;
                    img.onload = () => {
                      const ctx = canvas.getContext('2d');
                      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    }
                    enableClearButton(); 
                  } else {
                    disableClearButton();
                  }

                  // Watch user drawing and disable clear
                  signaturePad.onBegin = () => {
                    if (signaturePad.isEmpty()) return;
                    disabledClearButton();
                  };

                  // Watch user drawing and enable clear
                  signaturePad.onEnd = () => {
                    if (!signaturePad.isEmpty()) {
                      enableClearButton();
                    }
                  };

                  // Submit form: set ttd input to base64 image
                  document.querySelector('form').addEventListener('submit', function () {
                    if (!signaturePad.isEmpty()) {
                      ttdInput.value = signaturePad.toDataURL('image/png');
                    } else {
                      ttdInput.value = '';
                    }
                  });

                  // Clear signature and delete signature
                  const deleteTtdInput = document.getElementById('delete_ttd');
                  clearBtn.addEventListener('click', function () {
                    signaturePad.clear();
                    ttdInput.value = '';
                    deleteTtdInput.value = '1';
                    disableClearButton();
                  });

                </script>
        </div>
    </main>
@endsection
