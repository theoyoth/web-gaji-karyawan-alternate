@extends('layout.main')

@section('content')
				<div class="min-h-screen">
						<img src="/image/pattern-bw.jpg" alt="building" class="fixed top-0 left-0 -z-[10] opacity-10 h-screen w-full object-cover">
            
						<div class="bg-zinc-100 rounded-lg mt-4 px-1 pt-4 min-h-screen min-w-screen backdrop-blur-md bg-white/65 border border-white/30 shadow-lg">
						<a href="{{ route('header.index') }}" class="max-w-max flex items-center px-4 py-1 bg-gray-700 text-gray-100 rounded-md hover:bg-gray-800"><i class="fas fa-arrow-left text-lg text-gray-100 mr-1"></i> kembali</a>
              <div>
										{{-- <h1 class="text-4xl font-bold text-center">DAFTAR :  GAJI KARYAWAN TRANSPORTIR AWAK 1 DAN AWAK 2</h3> --}}
										<h1 class="text-4xl font-bold text-center">DAFTAR :  GAJI KARYAWAN PT. GUNUNG SELATAN</h3>
								</div>
								<div>
										<h1 class="text-2xl font-bold text-center">BULAN : {{ $month ?? '' }} {{ $year ?? '' }}</h3>
								</div>
								@if(session('success'))
										<div id="success-msg" class="bg-green-100 text-green-800 p-2 rounded">
												{{ session('success') }}
										</div>
										<script>
										setTimeout(() => {
												const msg = document.getElementById('success-msg');
												if (msg) msg.style.display = 'none';
										}, 4000);
										</script>
								@endif
								<div class="w-full flex justify-between items-center mt-8">
                  <div class="space-x-2">
                    <a href="{{ route('users.index', ['kantor' => 'all']) }}"
                      class="px-4 py-1 border rounded hover:shadow-md {{ request('kantor') == 'all' ? 'border-b-2 border-blue-600 text-blue-600 ' : 'text-gray-800' }}">
                      <i class="fas fa-bars text-lg mr-1"></i>
                      All
                    </a>
                    <a href="{{ route('users.index', ['kantor' => 'awak 1 dan awak 2']) }}"
                      class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'awak 1 dan awak 2' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
                      <i class="fas fa-users text-lg mr-1"></i>
                      Awak 1 dan Awak 2
                    </a>
                    <a href="{{ route('users.index', ['kantor' => 'kantor 1']) }}"
                      class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'kantor 1' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
                      <i class="fas fa-building text-lg mr-1"></i>
                      Kantor 1
                    </a>
                    <a href="{{ route('users.index', ['kantor' => 'kantor 2']) }}"
                      class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'kantor 2' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
                      <i class="fas fa-building text-lg mr-1"></i>
                      Kantor 2
                    </a>
                  </div>
                  
                  <form method="GET" action="{{ route('user.search') }}" class="flex gap-2 relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500">
                      <i class="fas fa-search"></i>
                    </span>
                    <input 
                      type="text" 
                      name="q" 
                      value="{{ request('q') }}" 
                      class="w-full pl-8 pr-2 py-1 outline-none border  bg-gray-100 shadow-md rounded placeholder:text-gray-400" 
                      placeholder="e.g. Rudi"
                    />
                    <input type="hidden" name="kantor" value="{{ request('kantor') }}">
                    <button type="submit" class="px-4 py-1 text-white bg-blue-600 hover:bg-blue-700 rounded">
                      cari
                    </button>
                  </form>
								</div>
                <hr class="bg-gray-200 h-[2px] my-2" />
								<section class="flex justify-between">
                  <div class="flex gap-4 items-center">
                    <form method="GET" action="{{ route('users.filter') }}" class="flex gap-2">
                      <input type="hidden" value="{{ request('kantor') }}" name="kantor">
                      <select name="bulan" required class="outline-none px-4 py-1 shadow-md rounded bg-gray-100 border border-gray-800">
                        <option value="">- Pilih Bulan -</option>
                        @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                      </select>
                      <select name="tahun" required class="outline-none px-4 py-1 shadow-md rounded bg-gray-100 border border-gray-800">
                          <option value="">- Pilih Tahun -</option>
                          @for ($y = 2022; $y <= now()->year; $y++)
                              <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                          @endfor
                      </select>
                      <button type="submit" class="px-4 py-1 text-white bg-blue-600 hover:bg-blue-700 rounded">Filter</button>
                      {{-- Reset Filter Button --}}
                      @if(request('bulan') || request('tahun'))
                        <a href="{{ route('users.index',['kantor' => 'all']) }}" class="bg-gray-800 text-white px-4 py-1 rounded">Reset</a>
                      @endif
                    </form>
                  </div>
                  <div class="flex gap-4">
                      <a href="{{ route('user.form') }}" class="max-w-max flex items-center my-4 px-6 py-1 text-gray-100 bg-gray-800 hover:bg-gray-700 shadow-md rounded"><i class="fas fa-plus text-gray-100 text-sm mr-1"></i> Baru</a>
                      <a href="{{ route('users.print',['kantor' => 'all']) }}" class="max-w-max flex items-center my-4 px-6 py-1 text-gray-800 border-2 border-gray-800 bg-gray-100 hover:bg-gray-200 shadow-md rounded"><i class="fas fa-print text-gray-800 text-sm mr-1"></i> Cetak</a>
                  </div>
                </section>

								<div class="bg-gray-100">
										@if($users->isEmpty())
												<!-- do nothing -->
											<p class="text-red-500 py-2 bg-gray-100 indent-2">Tidak ada data karyawan yang ditemukan.</p>
										@endif
										<table class="min-w-full table-auto border-collapse text-[0.8rem]">
                      @php
                        $hasAnyDelivery = $users->filter(fn($user) => $user->salary && $user->salary->deliveries->isNotEmpty())->isNotEmpty();
                      @endphp
												<thead>
														<tr>
																<th rowspan="2" class="py-2 w-5 border border-gray-400 bg-gray-300">No.</th>
																<th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 w-[120px]">Nama</th>
																<!-- Gaji Pokok with 3 sub-columns -->
																<th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Gaji Pokok</th>

                                @if($hasAnyDelivery)
                                  <!-- hari kerja -->
                                  <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Hari Kerja</th>
                                  <!-- jumlah retase -->
                                  <th colspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Jumlah Retase</th>
                                  <!-- tarif retase -->
                                  <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300 text-center">Tarif Retase</th>
                                  <!-- jumlah ur -->
                                  <th rowspan="2" class="py-2 border border-gray-400 bg-gray-300">Jumlah UR</th>
                                @endif
                                
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
                              @if($hasAnyDelivery)
																<!-- Sub-columns jumlah retase -->
																<th class="py-2 border border-gray-400 bg-gray-300 w-[120px]"></th>
																<th class="py-2 border border-gray-400 bg-gray-300 w-[150px]"></th>
                              @endif
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
														@foreach($users as $user)
																@if($user->salary)
																		@php
                                      $salary = $user->salary;
                                      $hasDeliveries = $salary->deliveries && $salary->deliveries->count();
                                      $deliveryCount = $hasDeliveries ? $salary->deliveries->count() : 1; // use 1 to show one row
																		@endphp
																		@foreach ($hasDeliveries ? $salary->deliveries : [] as $index => $delivery)
																			<tr>
																				@if($index === 0)
																					<td rowspan="{{ $deliveryCount }}" class="text-center border border-gray-400">{{ $no++ }}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center border border-gray-400 uppercase">{{$user->nama}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center border border-gray-400">Rp{{number_format($salary->gaji_pokok, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center border border-gray-400">{{$salary->hari_kerja}}</td>
																				@endif
                                        
                                        <td class="text-center py-1 border border-gray-400">{{ $delivery ? $delivery->jumlah_retase : "-" }}</td>
                                        <td class="text-center py-1 border border-gray-400">{{ $delivery ? $delivery->kota : "-" }}</td>
                                        <td class="text-center py-1 border border-gray-400">Rp{{ $delivery ? number_format( $delivery->tarif_retase, 0, ',', '.') : "-" }}</td>
                                        <td class="text-center py-1 border border-gray-400">Rp{{ $delivery ? number_format( $delivery->jumlah_ur, 0, ',', '.') : "-"}}</td>

                                        @if($index === 0)
                                          <td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->tunjangan_makan, 0, ',', '.')}}</td>
                                        @endif
                                        
																				@if($index === 0)
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->jumlah_gaji, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_bpjs, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_tabungan_hari_tua, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->potongan_kredit_kasbon, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">Rp{{number_format($salary->jumlah_bersih, 0, ',', '.')}}</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center py-1 border border-gray-400">
																							<img src="{{ file_exists(public_path('storage/ttd/' . $user->nama . '.png')) ? asset('storage/ttd/' . $user->nama . '.png') : '' }}" alt="ttd" class="w-20 h-20 object-contain">
																					</td>
																					<td rowspan="{{ $deliveryCount }}" class="text-center border border-gray-400">
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
																@endif
														@endforeach
                            {{-- total each page pagination --}}
														{{-- <tr>
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
														</tr> --}}
                            @if ($totalUsersSalary)
                            {{-- total all users salary --}}
														{{-- <tr class="text-lg bg-gray-300 text-gray-900 font-semibold">
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
														</tr> --}}
                            @endif
												</tbody>
										</table>
										<!-- Tailwind-styled pagination -->
										<div class="mt-4 flex justify-center">
												{{ $users->links() }}
										</div>
								</div>
						</div>
				</div>
@endsection
