<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Perizinan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('perizinan.store') }}" method="POST" class="space-y-8">
                        @csrf
                        {{-- BAGIAN 1: PILIH SANTRI --}}
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">1. Pilih Santri</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="hidden"> {{-- Dropdown status dihapus --}} </div>
                                <div class="col-span-2">
                                    <x-input-label for="id_santri_select" :value="__('Cari Nama atau ID Santri')" />
                                    <select id="id_santri_select" class="block w-full mt-1" name="id_santri_dummy"></select>
                                    <input type="hidden" name="id_santri" id="id_santri" required>
                                    <x-input-error :messages="$errors->get('id_santri')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        {{-- BAGIAN DETAIL SANTRI --}}
                        <div class="space-y-3">
                             <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Detail Santri Terpilih</h3>
                             <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-sm">
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</dt>
                                    <dd id="info-nama" class="font-semibold dark:text-gray-200 sm:col-span-1">-</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">ID Santri</dt>
                                    <dd id="info-id" class="font-mono dark:text-gray-300 sm:col-span-1">-</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Pendidikan</dt>
                                    <dd id="info-pendidikan" class="dark:text-gray-300 sm:col-span-1">-</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kelas</dt>
                                    <dd id="info-kelas" class="dark:text-gray-300 sm:col-span-1">-</dd>
                                    <dt class="font-medium text-gray-500 dark:text-gray-400">Kamar</dt>
                                    <dd id="info-kamar" class="dark:text-gray-300 sm:col-span-1">-</dd>
                                </dl>
                            </div>
                        </div>

                        {{-- BAGIAN 2: DETAIL PERIZINAN --}}
                        <div class="space-y-3">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">2. Detail Perizinan</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="id_jenis_perizinan" :value="__('Jenis Perizinan (Wajib)')" />
                                    <select name="id_jenis_perizinan" id="id_jenis_perizinan" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Jenis Izin --</option>
                                        @foreach($jenisPerizinans as $jenis)
                                            <option value="{{ $jenis->id_jenis_perizinan }}" {{ old('id_jenis_perizinan') == $jenis->id_jenis_perizinan ? 'selected' : '' }}>
                                                {{ $jenis->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_jenis_perizinan')" class="mt-2" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="waktu_pergi" :value="__('Waktu Mulai Izin')" />
                                        <x-text-input id="waktu_pergi" class="block mt-1 w-full" type="datetime-local" name="waktu_pergi" :value="old('waktu_pergi')" required />
                                        <x-input-error :messages="$errors->get('waktu_pergi')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="estimasi_kembali" :value="__('Estimasi Kembali')" />
                                        <x-text-input id="estimasi_kembali" class="block mt-1 w-full" type="datetime-local" name="estimasi_kembali" :value="old('estimasi_kembali')" required />
                                        <x-input-error :messages="$errors->get('estimasi_kembali')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('perizinan.index') }}" class="text-sm text-gray-700 dark:text-gray-300 underline hover:no-underline">Batal</a>
                            <x-primary-button class="ml-4">{{ __('Ajukan Izin') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- =============================================== --}}
    {{--           BAGIAN SCRIPT DIKEMBALIKAN            --}}
    {{-- =============================================== --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                function formatSantri (santri) {
                    if (santri.loading) { return santri.text; }
                    var $container = $(`<div class='select2-result-santri'><div class='select2-result-santri__avatar'><img src='${santri.foto_url}' /></div><div class='select2-result-santri__meta'><div class='select2-result-santri__title'></div><div class='select2-result-santri__id'></div></div></div>`);
                    $container.find(".select2-result-santri__title").text(santri.nama_santri);
                    $container.find(".select2-result-santri__id").text("ID: " + santri.id_santri);
                    return $container;
                }

                function formatSantriSelection (santri) {
                    return santri.nama_santri || santri.text;
                }

                $('#id_santri_select').select2({
                    placeholder: 'Ketik nama atau ID santri...',
                    theme: 'bootstrap-5',
                    minimumInputLength: 1, 
                    ajax: {
                        url: '{{ route("perizinan.search") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function (data) {
                            return { results: $.map(data, function(item) { return { id: item.id_santri, text: item.nama_santri, ...item }; }) };
                        },
                    },
                    templateResult: formatSantri,
                    templateSelection: formatSantriSelection,
                    escapeMarkup: function (markup) { return markup; }
                });

                // Hapus event handler filter_status dan enable/disable select santri
                $('#id_santri_select').on('select2:select', function (e) {
                    var data = e.params.data;
                    $('#id_santri').val(data.id_santri);
                    $('#info-nama').text(data.nama_santri || '-');
                    $('#info-id').text(data.id_santri || '-');
                    $('#info-kelas').text(data.nama_kelas || '-');
                    $('#info-pendidikan').text(data.nama_pendidikan || '-');
                    $('#info-kamar').text(data.nama_kamar || '-');
                });
            });
        </script>
        
        <style>
            .select2-result-santri__avatar img { width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover; }
            .select2-result-santri { display: flex; align-items: center; }
            .select2-result-santri__meta { display: flex; flex-direction: column; }
            .select2-result-santri__title { font-weight: 600; color: #333; }
            .select2-result-santri__id { font-size: 0.8rem; color: #777; }
        </style>
    @endpush
</x-app-layout>