<div>
    <!-- HEADER -->
    <x-header title="Pembayaran" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Cari..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$payments" :sort-by="$sortBy" striped with-pagination>
            @scope('cell_status', $payment)
                @if ($payment->status == 'Unpaid')
                    <x-badge value="{{ $payment->status }}" class="badge-warning" />
                @endif
                @if ($payment->status == 'Paid')
                    <x-badge value="{{ $payment->status }}" class="badge-success" />
                @endif
            @endscope
            @scope('actions', $payment)
                <div class="flex gap-x-4">
                    <x-button icon="o-eye" wire:click="view({{ $payment->id }})" class="text-blue-500 btn-ghost btn-sm" />
                    <x-button icon="s-wallet" wire:click="edit({{ $payment->id }})" class="text-green-500 btn-ghost btn-sm" />
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- MODAL FORM --}}
    <x-modal wire:model="modalForm" class="backdrop-blur"
        title="Perbarui Pembayaran">
        <x-form wire:submit.prevent="save">
            <x-input type="number" label="Total Harga" wire:model="                 .price" step="0.01" readonly>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>
            <x-input type="number" label="Jumlah Pembayaran" wire:model="                   .paymentAmount" step="0.01" required>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalForm = false" />
                <x-button type="submit" class="btn-success" label="Bayar" primary
                    spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
