<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carpeta;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportarController extends Controller
{
    public function generarReporte(Request $request)
    {
        $tipo = $request->input('tipoReporte');

        $carpetas = Carpeta::with(['solicitante', 'materia', 'Juicio'])
            ->where('activo', 1)
            ->get();

        if ($tipo === 'pdf') {
            $pdf = PDF::loadView('reportes.carpetas_pdf', compact('carpetas'));
            return $pdf->stream('reporte_carpetas.pdf');
        }

        if ($tipo === 'html') {
            return view('reportes.carpetas_html', compact('carpetas'));
        }

        if ($tipo === 'excel') {
            return $this->exportarExcelAlternativo($carpetas);
        }

        abort(404);
    }

    private function exportarExcelAlternativo($carpetas)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Solicitante', 'Materia', 'Tipo de Juicio', 'Síntesis'];
        $columnas = ['A', 'B', 'C', 'D', 'E'];

        // Encabezados
        foreach ($headers as $i => $header) {
            $col = $columnas[$i];
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4CAF50']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Cuerpo de datos
        $row = 2;
        foreach ($carpetas as $c) {
            $sheet->setCellValue("A{$row}", $c->idcarpeta);
            $sheet->setCellValue("B{$row}", optional($c->solicitante)->nombre . ' ' . optional($c->solicitante)->apellidopaterno . ' ' . optional($c->solicitante)->apellidomaterno);
            $sheet->setCellValue("C{$row}", optional($c->materia)->tipomateria);
            $sheet->setCellValue("D{$row}", optional($c->Juicio)->tipo);
            $sheet->setCellValue("E{$row}", $c->sintesis);

            foreach ($columnas as $col) {
                $sheet->getStyle($col . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP],
                ]);
            }

            $row++;
        }

        // Descargar
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="carpetas_reporte.xlsx"');
        $writer->save('php://output');
        exit;
    }
}
