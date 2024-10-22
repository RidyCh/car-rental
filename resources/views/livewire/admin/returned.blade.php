<div>
    <!-- HEADER -->
    <x-header title="Pengembalian" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Cari..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$returneds" :sort-by="$sortBy" striped with-pagination>
            @scope('cell_transaction_id', $returned)
                {{ $returned->transaction->id . ' | ' . $returned->transaction->user->name . ' | ' . $returned->transaction->car->name }}
            @endscope
            @scope('cel_return_date', $returned)
                {{ $returned->return_date->format('d F Y') }}
            @endscope
            @scope('cell_condition_car', $returned)
                {{ $returned->condition_car }}
            @endscope
            @scope('cell_price_penalty', $returned)
                Rp{{ number_format($returned->price_penalty, 2, ',', '.') }}
            @endscope
            @scope('actions', $returned)
                @if ($returned->payment)
                    @if ($returned->payment->status === 'Paid')
                        <x-badge value="Lunas" class="badge-success" />
                    @else
                        <x-button icon="s-wallet" wire:click="pay({{ $returned->id }})"
                            class="text-green-500 btn-ghost btn-sm" />
                    @endif
                @else
                    <x-button icon="s-wallet" wire:click="pay({{ $returned->id }})"
                        class="text-green-500 btn-ghost btn-sm" />
                @endif
            @endscope
        </x-table>
    </x-card>

    {{-- MODAL PAY --}}
    <x-modal wire:model="modalPay" class="backdrop-blur" title="Lakukan Pembayaran!" persistent>
        <x-form wire:submit.prevent="savePay">
            <x-input type="number" label="Total Harga" wire:model="priceTotal" step="0.01" readonly>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>
            <x-input type="number" label="Jumlah Pembayaran" wire:model="paymentForm.paymentAmount" step="0.01"
                required>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalPay = false" />
                <x-button type="submit" class="btn-success" label="Bayar" primary spinner="savePay" />
            </div>
        </x-form>
    </x-modal>
</div>
