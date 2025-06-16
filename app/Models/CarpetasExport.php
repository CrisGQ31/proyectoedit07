<?php
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CarpetasExport implements FromView
{
    protected $carpetas;

    public function __construct($carpetas)
    {
        $this->carpetas = $carpetas;
    }

    public function view(): View
    {
        return view('reportes.carpetas_excel', [
            'carpetas' => $this->carpetas
        ]);
    }
}
