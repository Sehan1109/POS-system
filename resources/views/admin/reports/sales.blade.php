<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">📊 Sales Report</h2>
    </x-slot>

    <div class="py-8" x-data="dailySalesModal()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                        class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg shadow">Apply</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRevenue, 2) }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-green-500">
                    <p class="text-sm text-gray-500 mb-1">Total Transactions</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCount }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <h3
                    class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                    Daily Breakdown
                </h3>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($sales as $row)
                            <tr @click="openModal('{{ $row->date }}', '{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}')"
                                class="hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer transition duration-150">

                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $row->count }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600">
                                    ${{ number_format($row->revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-400">No sales in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="isOpen" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity"
            @keydown.escape.window="isOpen = false">

            <div @click.away="isOpen = false"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">

                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white"
                        x-text="`Sales Details - ${selectedDateFormatted}`"></h3>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 flex-1 overflow-y-auto">
                    <div x-show="isLoading" class="text-center text-gray-500 py-4">Loading details...</div>

                    <div x-show="!isLoading">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                                    <th class="pb-2">Time/Invoice</th>
                                    <th class="pb-2">Customer</th>
                                    <th class="pb-2 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in dailyDetails" :key="item.id">
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2" x-text="item.invoice_number"></td>
                                        <td class="py-2" x-text="item.customer_name"></td>
                                        <td class="py-2 text-right text-indigo-600" x-text="'$' + item.amount"></td>
                                    </tr>
                                </template>
                                <tr x-show="dailyDetails.length === 0">
                                    <td colspan="3" class="py-4 text-center text-gray-500">No detailed records found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
                    <button @click="isOpen = false"
                        class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm">Close</button>
                    <a :href="`{{ $downloadDailyUrl }}?date=${selectedDate}`" target="_blank"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function dailySalesModal() {
            return {
                isOpen: false,
                isLoading: false,
                selectedDate: '',
                selectedDateFormatted: '',
                dailyDetails: [],

                openModal(date, formattedDate) {
                    this.selectedDate = date;
                    this.selectedDateFormatted = formattedDate;
                    this.isOpen = true;
                    this.isLoading = true;
                    this.dailyDetails = [];

                    // Fetch data from backend for this specific date
                    fetch(`{{ $dailyDetailsUrl }}?date=${date}`)
                        .then(response => response.json())
                        .then(data => {
                            this.dailyDetails = data;
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching details:', error);
                            this.isLoading = false;
                        });
                }
            }
        }
    </script>
</x-app-layout>