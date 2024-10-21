<div>
    <!-- HEADER -->
    <x-header title="Transaksi" subtitle="Click to show detail transaction!" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-select :options="$statusOptions" option-value="value" option-name="name" wire:model="filterStatus"
                placeholder="Semua" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$transactions" :sort-by="$sortBy" striped
            @row-click="$wire.showDetail($event.detail.id)" with-pagination>
            @scope('cell_nik', $transaction)
                {{ $transaction->user->name }}
            @endscope
            @scope('cell_car_id', $transaction)
                {{ $transaction->car->name }}
            @endscope
            @scope('cell_rental_date', $transaction)
                {{ $transaction->rental_date->format('d F Y') }}
            @endscope
            @scope('cell_pick_up_at', $transaction)
                {{ $transaction->pick_up_at->format('H:m') }}
            @endscope
            @scope('cell_duration', $transaction)
                {{ $transaction->duration }} hari
            @endscope
            @scope('cell_driver', $transaction)
                {{ $transaction->driver ? 'Dengan Supir' : 'Tanpa Supir' }}
            @endscope
            @scope('cell_price_total', $transaction)
                Rp{{ number_format($transaction->price_total, 2, ',', '.') }}
            @endscope
            @scope('cell_dp', $transaction)
                Rp{{ number_format($transaction->dp, 2, ',', '.') }}
            @endscope
            @scope('cell_price_final', $transaction)
                Rp{{ number_format($transaction->price_final, 2, ',', '.') }}
            @endscope
            @scope('cell_status', $transaction)
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
            @endscope
            @scope('actions', $transaction)
                <div class="flex gap-x-4">
                    @if ($transaction->status == 'Booked')
                        <x-button icon="s-arrow-up-circle" wire:click="$set('modalTake', {{ $transaction->id }})"
                            class="text-yellow-500 btn-ghost btn-sm" />
                        <x-button icon="s-x-circle" wire:click="$set('modalCancel', {{ $transaction->id }})"
                            class="text-red-500 btn-ghost btn-sm" />
                    @endif
                    @if ($transaction->status == 'On Rent')
                        <x-button icon="s-arrow-uturn-left" wire:click="return({{ $transaction->id }})"
                            class="text-green-500 btn-ghost btn-sm" />
                    @endif
                    @if ($transaction->status == 'Returned')
                        <x-button icon="s-wallet" wire:click="pay({{ $transaction->id }})"
                            class="text-green-500 btn-ghost btn-sm" />
                    @endif
                </div>
            @endscope
        </x-table>
    </x-card>

    {{-- MODAL DETAIL --}}
    <x-modal wire:model="modalDetail" title="Detail Transaksi">
        {{-- <div>{{ $form->user->name }}</div>
        <div>{{ $form->car->name }}</div>
        <div>{{ $form->rentalDate->format('d F Y') }}</div>
        <div>{{ $form->pickUpAt->format('H:i') }}</div>
        <div>
            @if ($form->transaction->returned)
                {{ $form->transaction->returned->condition_car }}
            @else
                -
            @endif
        </div> --}}
    </x-modal>

    {{-- MODAL RENT --}}
    <x-modal wire:model="modalTake" title="Ambil sekarang" class="backdrop-blur" persistent>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Tidak" @click="$wire.modalTake = false" />
            <x-button negative label="Ambil" class="btn-warning" wire:click="take" spinner />
        </div>
    </x-modal>

    {{-- MODAL CANCEL --}}
    <x-modal wire:model="modalCancel" title="Yakin ingin membatalkan bookingan ini?" class="backdrop-blur" persistent>
        <div class="flex justify-end gap-x-4">
            <x-button flat label="Tidak" @click="$wire.modalCancel = false" />
            <x-button negative label="Batalkan" class="btn-error" wire:click="cancel" spinner />
        </div>
    </x-modal>

    {{-- MODAL RETURN --}}
    <x-modal wire:model="modalReturn" class="backdrop-blur" title="Cek Kondisi Mobil dan Pengembalian" persistent>
        <x-form wire:submit="saveReturn">
            <x-textarea label="Kondisi Mobil" wire:model="returnForm.conditionCar" />
            <x-input type="number" label="Denda" wire:model="returnForm.pricePenalty" step="0.01">
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalReturn = false" />
                <x-button type="submit" class="btn-success" label="Kembalikan" primary spinner="saveReturn" />
            </div>
        </x-form>
    </x-modal>

    {{-- MODAL PAY --}}
    <x-modal wire:model="modalPay" class="backdrop-blur" title="Lakukan Pembayaran!" persistent>
        <x-form wire:submit="savePay">
            {{-- <x-select label="ID Pengembalian" wire:model="form.returnedId" :options="$returneds" option-label="id"
                option-value="id" required placeholder="Pilih ID Pengembalian" /> --}}
            <x-input type="number" label="Jumlah Pembayaran" wire:model="form.paymentAmount" step="0.01" required>
                <x-slot:prefix>Rp</x-slot:prefix>
            </x-input>

            <div class="flex justify-end gap-x-4">
                <x-button label="Batal" flat @click="$wire.modalPay = false" />
                <x-button type="submit" class="btn-success" label="Bayar" primary spinner="save" />
            </div>
        </x-form>
    </x-modal>
</div>
