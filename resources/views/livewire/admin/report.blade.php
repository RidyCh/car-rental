<div>
    <div class="container p-6 mx-auto">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">Laporan Harian</h1>
            <x-button label="Unduh Excel" class="btn-success" icon="o-document-arrow-down" wire:click="exportExcel" />
        </div>


        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Transaksi Berjalan Hari Ini -->
            <div class="p-6 bg-gray-800 rounded-lg shadow-md">
                <h2 class="mb-4 text-2xl font-semibold">Transaksi Berjalan Hari Ini</h2>
                @if ($todayTransactions->isEmpty())
                    <p>Tidak ada transaksi berjalan hari ini.</p>
                @else
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Pelanggan</th>
                                <th class="text-left">Mobil</th>
                                <th class="text-left">Total Harga</th>
                                <th class="text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>{{ $transaction->car->name }}</td>
                                    <td>Rp
                                        {{ number_format($transaction->price_total, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($transaction->status == 'Booked')
                                            <x-badge value="{{ $transaction->status }}" class="badge-info" />
                                        @endif
                                        @if ($transaction->status == 'On Rent')
                                            <x-badge value="{{ $transaction->status }}" class="badge-warning" />
                                        @endif
                                        @if ($transaction->status == 'Returned')
                                            <x-badge value="{{ $transaction->status }}" class="badge-success" />
                                        @endif
                                        @if ($transaction->status == 'Cancelled')
                                            <x-badge value="{{ $transaction->status }}" class="badge-error" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Pembayaran Hari Ini -->
            <div class="p-6 bg-gray-800 rounded-lg shadow-md">
                <h2 class="mb-4 text-2xl font-semibold">Pembayaran Hari Ini</h2>
                @if ($todayPayments->isEmpty())
                    <p>Tidak ada pembayaran hari ini.</p>
                @else
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Pelanggan</th>
                                <th class="text-left">Mobil</th>
                                <th class="text-left">Jumlah Pembayaran</th>
                                <th class="text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todayPayments as $payment)
                                <tr>
                                    <td>{{ $payment->returned->transaction->user->name }}</td>
                                    <td>{{ $payment->returned->transaction->car->name }}</td>
                                    <td>Rp {{ number_format($payment->payment_amount, 2, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($payment->status == 'Paid')
                                            <x-badge value="{{ $payment->status }}" class="badge-success" />
                                        @endif
                                        @if ($payment->status == 'Unpaid')
                                            <x-badge value="{{ $payment->status }}" class="badge-warning" />
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Total Pendapatan Hari Ini -->
        <div class="p-6 mt-6 bg-gray-800 rounded-lg shadow-md">
            <h2 class="mb-4 text-2xl font-semibold">Total Pendapatan Hari Ini</h2>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 2, ',', '.') }}</p>
        </div>
    </div>
</div>
