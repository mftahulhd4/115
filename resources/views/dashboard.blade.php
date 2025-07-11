<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Selamat Datang Kembali, {{ Auth::user()->name }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($semuaStatus as $status)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-500">
                                    <i class="fas fa-user-tag fa-2x"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $status->nama_status }}</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $status->santris_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <div class="mt-8 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-sm p-6">
                <h5 class="font-bold uppercase text-gray-600 dark:text-gray-300 mb-4 text-center">Visualisasi Komposisi Santri</h5>
                <div class="w-full md:w-3/4 lg:w-1/2 mx-auto">
                    <canvas id="santriPieChart"></canvas>
                </div>
            </div> --}}

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);
            const ctx = document.getElementById('santriPieChart');

            if (chartData.data.some(d => d > 0)) {
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Jumlah Santri',
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(75, 192, 192, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(255, 159, 64, 0.8)'
                            ],
                            borderColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                            borderWidth: 2,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    // [PERUBAHAN] Warna font diatur menjadi putih (#FFFFFF) secara permanen.
                                    color: '#FFFFFF'
                                }
                            }
                        }
                    }
                });
            } else {
                ctx.parentElement.innerHTML = '<p class="text-center text-gray-500 dark:text-gray-400">Tidak ada data santri untuk ditampilkan dalam grafik.</p>';
            }
        });
    </script> --}}
</x-app-layout>