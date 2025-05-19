<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Salary;
use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  public function index(Request $request){
    $kantor = $request->input('kantor');

    if($kantor === 'all'){
      // query all users no matter where they are coming from
      $query = User::with('salary.deliveries');
    }
    else{
      // Build query (do NOT call get() here)
      $query = User::where('kantor', $kantor)
              ->with('salary.deliveries');
    }

    // Clone for total calculation
    $allUsers = (clone $query)->get();

    // Paginate properly
    $usersPaginate = $query->paginate(15)->appends($request->only(['kantor']));

    // Calculate total for all data
    $totalUsersSalary = $allUsers->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => ($d->jumlah_retase ?? 0) * ($d->tarif_retase ?? 0))),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    // Calculate total for current page
    $pageTotals = $usersPaginate->getCollection()->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => ($d->jumlah_retase ?? 0) * ($d->tarif_retase ?? 0))),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    return view('user.index', [
        'users' => $usersPaginate,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
    ]);
  }
  
	public function formUser(){
		return view('user.form.index');
	}
  
	// public function create(){
	// 	return view('user.create-kantor');
	// }

	// public function createAwak12(){
	// 	return view('user.create-awak12');
	// }

	public function store(Request $request){
		$request->validate([
			'nama' => 'required|max:255',
			'kantor' => 'required|max:255',
			'tempat_lahir' => 'nullable|string',
			'tanggal_lahir' => 'nullable|date',
			'tanggal_diangkat' => 'nullable|date',

			'gaji_pokok' => 'required|numeric',
			'hari_kerja' => 'required|numeric',
			'bulan' => 'required',
			'tahun' => 'required|digits:4|integer|min:2010|max:'. date('Y'),

			'tunjangan_makan' => 'nullable|numeric',
			'tunjangan_hari_tua' => 'nullable|numeric',

			'potongan_bpjs' => 'required|numeric',
			'potongan_tabungan_hari_tua' => 'required|numeric',
			'potongan_kredit_kasbon' => 'required|numeric',

			'ttd' => 'nullable|string',
		]);

		// store image in storage folder
		// Clean the base64 signature
		$imageData = $request->input('ttd');
		$image = str_replace('data:image/png;base64,', '', $imageData);
		$image = str_replace(' ', '+', $image);

		// Create unique filename
		$fileName = Str::title($request->input('nama')) . '.png';

		// Store file in storage/app/public/signatures
		Storage::disk('public')->put('ttd/' . $fileName, base64_decode($image));

		// calculated early the jumlah_gaji
		$jumlah_gaji = $request->gaji_pokok
		+ ($request->tunjangan_makan ?? 0)
		+ ($request->tunjangan_hari_tua ?? 0);

		// create new instance for user,salary,delivery
		$user = new User();
		$salary = new Salary();


		// input data
		$user->nama = Str::title($request->input('nama'));
		$user->kantor = $request->input('kantor');
		$user->tempat_lahir = Str::title($request->input('tempat_lahir')) ?: null;
		$user->tanggal_lahir = $request->input('tanggal_lahir') ?: null;
		$user->tanggal_diangkat = $request->input('tanggal_diangkat') ?: null;

		$user->save();

		$salary->user_id = $user->id;
		$salary->gaji_pokok = $request->input('gaji_pokok');
		$salary->hari_kerja = $request->input('hari_kerja');
		$salary->bulan = $request->input('bulan');
		$salary->tahun = $request->input('tahun');
		$salary->tunjangan_makan = $request->input('tunjangan_makan');
		$salary->tunjangan_hari_tua = $request->input('tunjangan_hari_tua') ?? 0;

		$salary->jumlah_gaji = $jumlah_gaji;

		$salary->potongan_bpjs = $request->input('potongan_bpjs');
		$salary->potongan_tabungan_hari_tua = $request->input('potongan_tabungan_hari_tua');
		$salary->potongan_kredit_kasbon = $request->input('potongan_kredit_kasbon');

		$salary->ttd = $fileName;

		$salary->save();
		$salary->refresh();

		$allUsersKantor = User::where('kantor', $request->input('kantor'))->get()->count();
		$lastPageKantor = ceil($allUsersKantor / 15);

    return redirect()->route('users.index',['kantor' => $request->input('kantor'), 'page'=>$lastPageKantor])->with('success', 'user saved successfully!');
	}    

	public function storeAwak12(Request $request){
		$request->validate([
			'nama' => 'required|max:255',
			'kantor' => 'required|max:255',

			'gaji_pokok' => 'required|numeric',
			'hari_kerja' => 'required|numeric',
			'bulan' => 'required',
			'tahun' => 'required|digits:4|integer|min:2010|max:'. date('Y'),

			'tunjangan_makan' => 'nullable|numeric',

			'potongan_bpjs' => 'required|numeric',
			'potongan_tabungan_hari_tua' => 'required|numeric',
			'potongan_kredit_kasbon' => 'required|numeric',
			// delivery validation array
			'deliveries' => 'required|array|min:1',
			'deliveries.*.kota' => 'required|string',
			'deliveries.*.jumlah_retase' => 'required|numeric',
			'deliveries.*.tarif_retase' => 'required|numeric',

			'ttd' => 'nullable|string',

		]);

		// store image in storage folder
		// Clean the base64 signature
		$imageData = $request->input('ttd');
		$image = str_replace('data:image/png;base64,', '', $imageData);
		$image = str_replace(' ', '+', $image);

		// Create unique filename
		$fileName = Str::title($request->input('nama')) . '.png';

		// Store file in storage/app/public/signatures
		Storage::disk('public')->put('ttd/' . $fileName, base64_decode($image));

		// create new instance for user,salary,delivery
		$user = new User();
		$salary = new Salary();

		// input data
		$user->nama = Str::title($request->input('nama'));
		$user->kantor = $request->input('kantor');

		$user->save();

		$salary->user_id = $user->id;
		$salary->gaji_pokok = $request->input('gaji_pokok');
		$salary->hari_kerja = $request->input('hari_kerja');
		$salary->bulan = $request->input('bulan');
		$salary->tahun = $request->input('tahun');
		$salary->tunjangan_makan = $request->input('tunjangan_makan');
		$salary->tunjangan_hari_tua = $request->input('tunjangan_hari_tua') ?: 0;

		$salary->jumlah_gaji = 0;

		$salary->potongan_bpjs = $request->input('potongan_bpjs');
		$salary->potongan_tabungan_hari_tua = $request->input('potongan_tabungan_hari_tua');
		$salary->potongan_kredit_kasbon = $request->input('potongan_kredit_kasbon');
		$salary->ttd = $fileName;

		$salary->save();
		$salary->refresh();

		// 4. Save multiple deliveries
		foreach ($request->input('deliveries', []) as $inputDelivery) {
			$delivery = new Delivery();

			$delivery->salary_id = $salary->id;
			$delivery->kota = $inputDelivery['kota'];
			$delivery->jumlah_retase = $inputDelivery['jumlah_retase'];
			$delivery->tarif_retase = $inputDelivery['tarif_retase'];

			$delivery->save();
		}

		 // Reload deliveries relation to access them
		$salary->load('deliveries');

		// Calculate total gaji
		$salary->jumlah_gaji = $salary->gaji_pokok
			+ $salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase)
			+ ($salary->tunjangan_makan ?? 0)
			+ ($salary->tunjangan_hari_tua ?? 0);

		$salary->save(); // Save the updated jumlah_gaji

		$allUsers = User::where('kantor', "awak 1 dan awak 2")->get()->count();
		$lastPage = ceil($allUsers / 15);

		return redirect()->route('users.index',['kantor' => 'awak 1 dan awak 2','page' => $lastPage])->with('success', 'user saved successfully!');
	}

	public function destroy($id){
		$user = User::with('salary')->findOrFail($id);

		// Loop through each salary associated with the user
		if ($user->salary) {
			$fileName = Str::title($user->nama) . '.png'; // using capital first letter user's name for the signature
			$path = 'ttd/' . $fileName;

			if (Storage::disk('public')->exists($path)) {
				// Delete the file
				Storage::disk('public')->delete($path);
			}

			// Optionally delete the salary record if needed
			$user->salary->delete();
		}

		// Delete the user
		$user->delete();

		return redirect()->back()->with('success', 'User deleted successfully.');
	}

	public function editPageAwak12(User $user){
		// dd($user->salaries);
		return view('user.edit.awak12', compact('user'));
	}
	public function editPageKantor(User $user){
		// dd($user->salaries);
		return view('user.edit.kantor', compact('user'));
	}

	public function updateAwak12(Request $request, $userId){
		$user = User::with('salary.deliveries')->findOrFail($userId);

		// Validate user data
		$user->update($request->only(['nama', 'kantor']));

		// Update salary
		$salary = $user->salary;
		$salary->gaji_pokok = $request->input('gaji_pokok', $salary->gaji_pokok);
		$salary->bulan = $request->input('bulan', $salary->bulan);
		$salary->tahun = $request->input('tahun', $salary->tahun);
		$salary->hari_kerja = $request->input('hari_kerja', $salary->hari_kerja);
		$salary->tunjangan_makan = $request->input('tunjangan_makan', $salary->tunjangan_makan);
		$salary->potongan_bpjs = $request->input('potongan_bpjs', $salary->potongan_bpjs);
		$salary->potongan_tabungan_hari_tua = $request->input('potongan_tabungan_hari_tua', $salary->potongan_tabungan_hari_tua);
		$salary->potongan_kredit_kasbon = $request->input('potongan_kredit_kasbon', $salary->potongan_kredit_kasbon);

		if ($request->input('delete_ttd') === '1') {
			// Delete old signature file
			if ($salary->ttd && Storage::disk('public')->exists('ttd/' . $salary->ttd)) {
				Storage::disk('public')->delete('ttd/' . $salary->ttd);
			}
			$salary->ttd = ' '; // Clear from DB
		} elseif($request->filled('ttd')) {
			$oldSignature = $salary->ttd;
			$imageData = $request->input('ttd');

			// Clean and decode base64 image
			$imageData = str_replace('data:image/png;base64,', '', $imageData);
			$imageData = str_replace(' ', '+', $imageData);
			$fileName = Str::title($user->nama) . '.png';

			// Delete old signature file if different
			if ($oldSignature && Storage::disk('public')->exists('ttd/' . $oldSignature)) {
				Storage::disk('public')->delete('ttd/' . $oldSignature);
			}

			// Save new signature
			Storage::disk('public')->put('ttd/' . $fileName, base64_decode($imageData));
			
			// Update the database with the new signature file name
			$salary->ttd = $fileName;
			
		}

		// Reload deliveries relation to access them
		$salary->load('deliveries');

		// Calculate total gaji
		$salary->jumlah_gaji = $salary->gaji_pokok
			+ $salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase)
			+ ($salary->tunjangan_makan ?? 0)
			+ ($salary->tunjangan_hari_tua ?? 0);

		$salary->save();

		// Delete old deliveries
		$salary->deliveries()->delete();

		// Add new deliveries
		if ($request->has('deliveries')) {
			foreach ($request->input('deliveries',[]) as $deliveryData) {
				$salary->deliveries()->create([
					'kota' => $deliveryData['kota'],
					'jumlah_retase' => $deliveryData['jumlah_retase'],
					'tarif_retase' => $deliveryData['tarif_retase'],
				]);
			}
		}

    $allUsers = User::where('kantor', "awak 1 dan awak 2")->get()->count();
		$lastPage = ceil($allUsers / 15);

		return redirect()->route('users.index',['kantor' => 'awak 1 dan awak 2','page'=>$lastPage])->with('success', 'User updated successfully!');
	}

	public function updateKantor(Request $request, $userId){
	  $user = User::with('salary')->findOrFail($userId);

	  // Validate user data
	  $user->update($request->only(['nama', 'kantor']));

	  // Update salary
	  $salary = $user->salary;

	  $salary->gaji_pokok = $request->input('gaji_pokok', $salary->gaji_pokok);
	  $salary->bulan = $request->input('bulan', $salary->bulan);
	  $salary->tahun = $request->input('tahun', $salary->tahun);
	  $salary->hari_kerja = $request->input('hari_kerja', $salary->hari_kerja);
	  $salary->tunjangan_makan = $request->input('tunjangan_makan', $salary->tunjangan_makan);
	  $salary->potongan_bpjs = $request->input('potongan_bpjs', $salary->potongan_bpjs);
	  $salary->potongan_tabungan_hari_tua = $request->input('potongan_tabungan_hari_tua', $salary->potongan_tabungan_hari_tua);
	  $salary->potongan_kredit_kasbon = $request->input('potongan_kredit_kasbon', $salary->potongan_kredit_kasbon);

	  if ($request->input('delete_ttd') === '1') {
		// Delete old signature file
		if ($salary->ttd && Storage::disk('public')->exists('ttd/' . $salary->ttd)) {
			Storage::disk('public')->delete('ttd/' . $salary->ttd);
      }
      $salary->ttd = ' '; // Clear from DB
		} elseif($request->filled('ttd')) {
		  $oldSignature = $salary->ttd;
		  $imageData = $request->input('ttd');

		  // Clean and decode base64 image
		  $imageData = str_replace('data:image/png;base64,', '', $request->input('ttd'));
		  $imageData = str_replace(' ', '+', $imageData);
		  $fileName = Str::title($user->nama) . '.png';

		  // Delete old signature file if different
		  if ($oldSignature && Storage::disk('public')->exists('ttd/' . $oldSignature)) {
			  Storage::disk('public')->delete('ttd/' . $oldSignature);
		  }

		  // Save new signature
		  Storage::disk('public')->put('ttd/' . $fileName, base64_decode($imageData));

		  $salary->ttd = $fileName;
	  }

	  // Calculate jumlah gaji
	  $salary->jumlah_gaji = $salary->gaji_pokok
		  + ($salary->tunjangan_makan ?? 0)
		  + ($salary->tunjangan_hari_tua ?? 0);

	  $salary->save();

    $allUsersKantor = User::where('kantor', $request->input('kantor'))->get()->count();
    $lastPageKantor = ceil($allUsersKantor / 15);

    return redirect()->route('users.index',['kantor' => $request->input('kantor'), 'page'=>$lastPageKantor])->with('success', 'user updated successfully!');
	}

  public function searchUserAwak12(Request $request){
    $search = $request->input('search'); // Get search term from the request

    $users = User::where('kantor', 'awak 1 dan awak 2') // Filter by kantor (if needed)
                  ->when($search, function ($query, $search) {
                    // If there's a search term, filter by the user's name
                    return $query->where('nama', 'like', '%' . $search . '%');
                  })->with('salary');

    // Clone for total calculation (all data)
    $allUsers = (clone $users)->get();

    // Paginate data
    $usersPaginate = $users->paginate(15)->appends($request->only(['search']));  // Include search in pagination links

    // Calculate totals
    $totalUsersSalary = $allUsers->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalJumlahRetase' => $totalValue['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalJumlahRetase' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);
    
    // Calculate paginate users total
    $pageTotals = $usersPaginate->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalJumlahRetase' => $totalValue['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalJumlahRetase' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);

    // Return view with the necessary data
    // return view('place.awak12', [
    //   'users' => $usersPaginate,
    //   'pageTotals' => $pageTotals,
    //   'totalUsersSalary' => $totalUsersSalary,
    // ]);
  }

  public function searchUserKantor(Request $request){
    $search = $request->input('search'); // Get search term from the request
    $kantor = $request->input('kantor');

    $users = User::where('kantor', $kantor) // Filter by kantor (if needed)
                  ->when($search, function ($query, $search) {
                    // If there's a search term, filter by the user's name
                    return $query->where('nama', 'like', '%' . $search . '%');
                  })->with('salary');

    // Clone for total calculation (all data)
    $allUsers = (clone $users)->get();

    // Paginate data
    $usersPaginate = $users->paginate(15)->appends($request->only(['search']));  // Include search in pagination links

    // Calculate totals
    $totalUsersSalary = $allUsers->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);
    
    // Calculate paginate users total
    $pageTotals = $usersPaginate->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);

    // Return view with the necessary data
    if($kantor == 'kantor 1'){
      return view('place.kantor1', [
        'users' => $usersPaginate,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
      ]);
    }
    elseif($kantor == 'kantor 2'){
      return view('place.kantor2', [
        'users' => $usersPaginate,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
      ]);
    }
  }

  public function filterUsers(Request $request){
    $month = $request->input('bulan');
    $year = $request->input('tahun');
    $kantor = $request->input('kantor');

    if($kantor === 'all'){
      $query = User::whereHas('salary', function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          })
          ->with(['salary' => function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          }]);
    } else {
      // Build query (do NOT call get() here)
      $query = User::where('kantor', $kantor)
          ->whereHas('salary', function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          })
          ->with(['salary' => function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          }]);
    }

    // Clone for total calculation
    $allUsers = (clone $query)->get();

    // Paginate properly
    $usersPaginate = $query->paginate(15)->appends($request->only(['kantor','bulan','tahun']));

    // Calculate total for all data
    $totalUsersSalary = $allUsers->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    // Calculate total for current page
    $pageTotals = $usersPaginate->getCollection()->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    return view('user.index', [
        'users' => $usersPaginate,
        'month' => $month,
        'year' => $year,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
    ]);
  }

   public function searchUser(Request $request){
    $search = $request->input('q'); // Get search term from the request
    $kantor = $request->input('kantor');

    if($kantor === 'all'){
      $users = User::when($search, function ($query, $search) {
                      // If there's a search term, filter by the user's name
                      return $query->where('nama', 'like', '%' . $search . '%');
                    })->with('salary');
    }
    else{
      $users = User::where('kantor', $kantor) // Filter by kantor (if needed)
                    ->when($search, function ($query, $search) {
                      // If there's a search term, filter by the user's name
                      return $query->where('nama', 'like', '%' . $search . '%');
                    })->with('salary');
    }

    // Clone for total calculation (all data)
    $allUsers = (clone $users)->get();

    // Paginate data
    $usersPaginate = $users->paginate(15)->appends($request->only(['search']));  // Include search in pagination links

    // Calculate totals
    $totalUsersSalary = $allUsers->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalJumlahRetase' => $totalValue['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalJumlahRetase' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);
    
    // Calculate paginate users total
    $pageTotals = $usersPaginate->reduce(function ($totalValue, $user) {
      $salary = $user->salary;

      return [
        'totalJumlahGaji' => $totalValue['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
        'totalJumlahRetase' => $totalValue['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
        'totalTunjanganMakan' => $totalValue['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
        'totalPotonganBpjs' => $totalValue['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
        'totalPotonganHariTua' => $totalValue['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
        'totalPotonganKreditKasbon' => $totalValue['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
        'totalGeneral' => $totalValue['totalGeneral'] + ($salary->jumlah_gaji - ($salary->potongan_bpjs + $salary->potongan_hari_tua + $salary->potongan_kredit_kasbon) ?? 0),
      ];
    }, ['totalJumlahGaji' => 0, 'totalJumlahRetase' => 0, 'totalTunjanganMakan' => 0, 'totalPotonganBpjs' => 0, 'totalPotonganHariTua' => 0, 'totalPotonganKreditKasbon' => 0, 'totalGeneral' => 0]);

    return view('user.index', [
      'users' => $usersPaginate,
      'pageTotals' => $pageTotals,
      'totalUsersSalary' => $totalUsersSalary,
    ]);
  }

  public function printData(Request $request){
    $kantor = $request->input('kantor');

    if($kantor === 'all'){
      // query all users no matter where they are coming from
      $query = User::with('salary.deliveries');
    }
    else{
      // Build query (do NOT call get() here)
      $query = User::where('kantor', $kantor)
              ->with('salary.deliveries');
    }

    // Clone for total calculation
    $allUsers = (clone $query)->get();

    // Paginate properly
    $usersPaginate = $query->paginate(15)->appends($request->only(['kantor']));

    // Calculate total for all data
    $totalUsersSalary = $allUsers->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    // Calculate total for current page
    $pageTotals = $usersPaginate->getCollection()->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    return view('user.print', [
        'users' => $allUsers,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
    ]);
  }

   public function filterUsersPrint(Request $request){
    $month = $request->input('bulan');
    $year = $request->input('tahun');
    $kantor = $request->input('kantor');

    if($kantor === 'all'){
      $query = User::whereHas('salary', function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          })
          ->with(['salary' => function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          }]);
    } else {
      // Build query (do NOT call get() here)
      $query = User::where('kantor', $kantor)
          ->whereHas('salary', function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          })
          ->with(['salary' => function ($query) use ($month, $year) {
              if ($month && $year) {
                  $query->where('bulan', $month)
                        ->where('tahun', $year);
              }
          }]);
    }

    // Clone for total calculation
    $allUsers = (clone $query)->get();

    // Paginate properly
    $usersPaginate = $query->paginate(15)->appends($request->only(['kantor','bulan','tahun']));

    // Calculate total for all data
    $totalUsersSalary = $allUsers->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    // Calculate total for current page
    $pageTotals = $usersPaginate->getCollection()->reduce(function ($total, $user) {
        $salary = $user->salary;

        return [
            'totalJumlahGaji' => $total['totalJumlahGaji'] + ($salary->jumlah_gaji ?? 0),
            'totalTunjanganMakan' => $total['totalTunjanganMakan'] + ($salary->tunjangan_makan ?? 0),
            'totalJumlahRetase' => $total['totalJumlahRetase'] + ($salary->deliveries->sum(fn($d) => $d->jumlah_retase * $d->tarif_retase) ?? 0),
            'totalPotonganBpjs' => $total['totalPotonganBpjs'] + ($salary->potongan_bpjs ?? 0),
            'totalPotonganHariTua' => $total['totalPotonganHariTua'] + ($salary->potongan_hari_tua ?? 0),
            'totalPotonganKreditKasbon' => $total['totalPotonganKreditKasbon'] + ($salary->potongan_kredit_kasbon ?? 0),
            'totalGeneral' => $total['totalGeneral'] + (($salary->jumlah_gaji ?? 0) - (($salary->potongan_bpjs ?? 0) + ($salary->potongan_hari_tua ?? 0) + ($salary->potongan_kredit_kasbon ?? 0))),
        ];
    }, [
        'totalJumlahGaji' => 0,
        'totalTunjanganMakan' => 0,
        'totalJumlahRetase' => 0,
        'totalPotonganBpjs' => 0,
        'totalPotonganHariTua' => 0,
        'totalPotonganKreditKasbon' => 0,
        'totalGeneral' => 0,
    ]);

    return view('user.print', [
        'users' => $usersPaginate,
        'month' => $month,
        'year' => $year,
        'pageTotals' => $pageTotals,
        'totalUsersSalary' => $totalUsersSalary,
    ]);
  }
}
