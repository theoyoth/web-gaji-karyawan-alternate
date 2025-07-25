@extends('layout.main')

@section('content')
  <main class="min-h-screen flex justify-center">
    <div class="w-1/2 m-auto py-2 px-10 bg-gray-100 rounded-lg border border-black my-4">
        <a href="{{ route('users.index',['kantor' => request('kantor'),'bulan' => request('bulan'),'tahun' => request('tahun')]) }}" class="flex items-center max-w-max my-4 px-4 py-1 bg-gray-700 text-white rounded-md hover:bg-gray-800">
          <i class="fas fa-arrow-left text-lg text-gray-100 mr-1"></i> kembali
        </a>
        <fieldset class="space-x-2 border border-gray-300 rounded max-w-max p-2">
          <legend class="text-gray-400 mx-2 text-xs">tipe form</legend>
          @php
              $activeKantor = request('kantor');
          @endphp

          <a href="{{ route('user.form', ['kantor' => 'kantor 1','bulan' => request('bulan'),'tahun' => request('tahun')]) }}" id="btn-kantor-1" class="px-4 py-1 border rounded hover:shadow-md {{ $activeKantor == 'kantor 1' ? 'bg-blue-600 text-white' : 'bg-white' }}"><i class="fas fa-building text-lg mr-1"></i> Kantor 1</a>
          <a href="{{ route('user.form', ['kantor' => 'kantor 2','bulan' => request('bulan'),'tahun' => request('tahun')]) }}" id="btn-kantor-2" class="px-4 py-1 border rounded hover:shadow-md {{ $activeKantor == 'kantor 2' ? 'bg-blue-600 text-white' : 'bg-white' }}"><i class="fas fa-building text-lg mr-1"></i> Kantor 2</a>
          <a href="{{ route('user.form', ['kantor' => 'awak 1 dan awak 2','bulan' => request('bulan'),'tahun' => request('tahun')]) }}" id="btn-awak" class="px-4 py-1 border rounded hover:shadow-md {{ $activeKantor == 'awak 1 dan awak 2' ? 'bg-blue-600 text-white' : 'bg-white' }}"><i class="fas fa-users text-lg mr-1"></i> Awak 1 dan 2</a>
        </fieldset>
        <hr class="my-4 border-[1px] border-gray-200" />

          <!-- Form for kantor -->
          <div id="form-kantor-1">
            @include('user.form.create-kantor-1')
          </div>

          <div id="form-kantor-2">
            @include('user.form.create-kantor-2')
          </div>

          <!-- Form for awak -->
          <div id="form-awak" class="hidden">
            @include('user.form.create-awak12')
          </div>
            
            <!-- Include Signature Pad JS -->
            <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

            <script>
              let signaturePad;

              function initSignaturePad(formId) {
                const canvas = document.getElementById(`signature-pad-${formId}`);
                // const canvas = form.querySelector(`#signature-pad-${formId}`);
                if (!canvas) return;

                signaturePad = new SignaturePad(canvas);

                // Clear the signature pad
                const clearBtn = document.getElementById(`clear-${formId}`);
                if(clearBtn){
                  clearBtn.addEventListener('click', function () {
                    if(signaturePad){
                      signaturePad.clear();
                    }
                  });
                }
                
              }

              // Optional: Show default form on page load
              document.addEventListener('DOMContentLoaded', () => {
                const params = new URLSearchParams(window.location.search);
                let kantorQuery = params.get('kantor');
                
                let formId;

                if (kantorQuery === 'kantor 1') {
                  formId = "kantor-1"
                } 
                else if(kantorQuery === 'kantor 2'){
                  formId = "kantor-2"
                }
                else {
                  formId = "awak"
                }

                // Show only the correct form
                document.getElementById('form-kantor-1').classList.add('hidden');
                document.getElementById('form-kantor-2').classList.add('hidden');
                document.getElementById('form-awak').classList.add('hidden');

                const activeForm = document.getElementById(`form-${formId}`);
                if (activeForm) {
                  activeForm.classList.remove('hidden');
                  initSignaturePad(formId);
                }
              });
              
              document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function () {
                  const ttdInput = form.querySelector('input[name="ttd"]');
                  if (signaturePad && !signaturePad.isEmpty()) {
                    ttdInput.value = signaturePad.toDataURL();
                  } else {
                    ttdInput.value = '';
                  }
                });
              });

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

                    <button type="button" onclick="removeDeliveryRow(this)" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Hapus</button>
                  `;
                  wrapper.appendChild(row);
                  deliveryIndex++;
              }

              function removeDeliveryRow(button) {
                  button.parentElement.remove();
              }

            </script>
    </div>
  </main>
@endsection
