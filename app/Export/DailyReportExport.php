<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $todayTransactions;
    protected $todayPayments;
    protected $totalRevenue;

    public function __construct($todayTransactions, $todayPayments, $totalRevenue)
    {
        $this->todayTransactions = $todayTransactions;
        $this->todayPayments = $todayPayments;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        $data = collect();

        // Transaksi Berjalan
        $data->push(['Transaksi Berjalan Hari Ini']);
        $data->push(['Pelanggan', 'Mobil', 'Total Harga']);
        foreach ($this->todayTransactions as $transaction) {
            $data->push([
                $transaction->user->name,
                $transaction->car->name,
                $transaction->price_total
            ]);
        }

        $data->push([]);  // Empty row for separation

        // Pembayaran
        $data->push(['Pembayaran Hari Ini']);
        $data->push(['Pelanggan', 'Mobil', 'Jumlah Pembayaran']);
        foreach ($this->todayPayments as $payment) {
            $data->push([
                $payment->returned->transaction->user->name,
                $payment->returned->transaction->car->name,
                $payment->payment_amount
            ]);
        }

        $data->push([]);  // Empty row for separation

        // Total Pendapatan
        $data->push(['Total Pendapatan Hari Ini', $this->totalRevenue]);

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Laporan Harian'],
            []  // Empty row after title
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 16]],
            'A'  => ['font' => ['bold' => true]],
        ];
    }
}