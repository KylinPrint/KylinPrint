<?php

namespace App\Admin\Actions\Exports;

use Dcat\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SolutionMatchExport implements FromCollection, WithHeadings
{

    use Exportable;

    public function __construct($array,$file)
    {
        $this->data = $array;

        $this->headings = array_keys($array);

        $this->file = $file;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings():array
    {
        return $this->headings;
    }

    public function export()
    {
        $this->download($this->file)->prepare(request())->send();
        exit;
    }
}