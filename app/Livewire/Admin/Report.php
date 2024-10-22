<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use App\Exports\DailyReportExport;
use Maatwebsite\Excel\Facades\Excel;

class Report extends Component
{
    public $todayTransactions = [];
    public $todayPayments;
    public $totalRevenue;

    #[Title('Report')]
    #[Layout('layouts.admin')]
    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $today = Carbon::today();

        $this->todayTransactions = Transaction::whereDate('rental_date', $today)
            ->whereIn('status', ['On Rent', 'Returned', 'Booking'])
            ->with(['user', 'car'])
            ->get();

        $this->todayPayments = Payment::whereHas('returned', function ($query) use ($today) {
            $query->whereDate('return_date', $today);
        })->with('returned.transaction.user', 'returned.transaction.car')->get();

        $this->totalRevenue = $this->todayPayments->sum('payment_amount');
    }

    public function exportExcel()
    {
        return Excel::download(new DailyReportExport($this->todayTransactions, $this->todayPayments, $this->totalRevenue), 'laporan_harian_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.admin.report');
    }
}