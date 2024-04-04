<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromView, WithTitle, ShouldAutoSize, WithStyles, ShouldQueue
{
    protected $blade_file_name;

    protected $title;

    protected $data;

    public function __construct($blade_file_name, $title, $data)
    {
        $this->blade_file_name = $blade_file_name;
        $this->title = $title;
        $this->data = $data;
    }

    public function view(): View
    {
        return view($this->blade_file_name, [
            'data' => $this->data,
        ]);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        if (app()->getLocale() == 'ar') {
            $sheet->setRightToLeft(true);
        }
        $sheet->setAutoFilter('A1:S1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }
}
