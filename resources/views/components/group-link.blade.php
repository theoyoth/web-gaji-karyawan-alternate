<div class="space-x-2">
  <a href="{{ route('users.index', ['kantor' => 'awak 1 dan awak 2','bulan' => request('bulan'), 'tahun' => request('tahun'),'page' => 1]) }}"
    class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'awak 1 dan awak 2' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
    <i class="fas fa-users text-lg mr-1"></i>
    Awak 1 dan Awak 2
  </a>
  <a href="{{ route('users.index', ['kantor' => 'kantor 1','bulan' => request('bulan'), 'tahun' => request('tahun'),'page' => 1]) }}"
    class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'kantor 1' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
    <i class="fas fa-building text-lg mr-1"></i>
    Kantor 1
  </a>
  <a href="{{ route('users.index', ['kantor' => 'kantor 2','bulan' => request('bulan'), 'tahun' => request('tahun'),'page' => 1]) }}"
    class="px-4 py-1 rounded border hover:shadow-md {{ request('kantor') == 'kantor 2' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-800' }}">
    <i class="fas fa-building text-lg mr-1"></i>
    Kantor 2
  </a>
</div>