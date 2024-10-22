<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\PaymentForm;
use App\Models\Returned as ModelsReturned;
use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Returned extends Component
{
    use WithPagination, WithoutUrlPagination, Toast;

    public PaymentForm $paymentForm;

    public $search = '';
    public bool $modalPay = false;
    public array $sortBy = ['column' => 'return_date', 'direction' => 'desc'];

    public $selectedReturnedId;
    public $priceTotal;

    public function pay($id)
    {
        $this->selectedReturnedId = $id;
        $returned = ModelsReturned::findOrFail($id);
        $transaction = $returned->transaction;

        $this->priceTotal = $transaction->price_final + $returned->price_penalty;

        $this->modalPay = true;
    }

    public function savePay()
    {
        $this->validate([
            'paymentForm.paymentAmount' => 'required|numeric|min:' . $this->priceTotal,
        ]);

        try {
            $payment = $this->paymentForm->store($this->selectedReturnedId);
            $this->modalPay = false;
            $this->dispatch('refreshReturned');

            if ($payment->status === 'Paid') {
                $this->success('Pembayaran lunas berhasil dilakukan!');
            } else {
                $remainingAmount = $this->priceTotal - $payment->payment_amount;
                $this->info('Pembayaran sebagian berhasil dilakukan. Sisa pembayaran: Rp ' . number_format($remainingAmount, 2, ',', '.'));
            }
        } catch (\Exception $e) {
            $this->modalPay = false;
            $this->error('Terjadi kesalahan saat melakukan pembayaran: ' . $e->getMessage());
        }
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'transaction_id', 'label' => 'Transaksi'],
            ['key' => 'return_date', 'label' => 'Tanggal Kembali'],
            ['key' => 'condition_car', 'label' => 'Kondisi Mobil'],
            ['key' => 'price_penalty', 'label' => 'Denda'],
        ];
    }

    public function returneds(): LengthAwarePaginator
    {
        return ModelsReturned::query()
            ->with(['transaction', 'transaction.user', 'transaction.car'])
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('transaction', function ($q) {
                    $q->where('id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('car', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Returned')]
    public function render()
    {
        return view('livewire.admin.returned', [
            'returneds' => $this->returneds(),
            'headers' => $this->headers(),
        ]);
    }
}