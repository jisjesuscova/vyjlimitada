<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use App\Models\Ticket;
use App\Models\Event;

class ExcelController extends Controller
{
    public function generateExcel($id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Obtén los tickets desde la base de datos (ajusta la consulta según tus necesidades)
        $tickets = Ticket::where('event_id', '=', $id)->get();

        $event = Event::find($id);

        // Recorre los tickets e inserta el código QR en cada fila
        $row = 1;
        foreach ($tickets as $ticket) {
            $qrCode = QrCode::format('png')->size(200)->generate($ticket->token);
            $imageString = base64_encode($qrCode);

            $gdImage = imagecreatefromstring(base64_decode($imageString));
            $drawing = new MemoryDrawing();
            $drawing->setName('QRCode');
            $drawing->setDescription('QR Code');
            $drawing->setImageResource($gdImage);
            $drawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
            $drawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
            $drawing->setCoordinates('A' . $row);
            $drawing->setWorksheet($sheet);

            $row++;
        }

        // Crea el objeto writer para guardar el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Preparar la respuesta como un archivo descargable
        $fileName = 'qr_evento_' . $event->event_name . '.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            $headers
        );

        return $response;
    }

    public function generateTokenExcel($id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Obtén los tickets desde la base de datos (ajusta la consulta según tus necesidades)
        $tickets = Ticket::where('event_id', '=', $id)->get();

        // Añade los encabezados a la primera fila del archivo Excel
        $sheet->setCellValue('A1', 'Token');

        // Recorre los tickets e inserta el código QR en cada fila
        $row = 2;
        foreach ($tickets as $ticket) {
            $sheet->setCellValue('A' . $row, $ticket->token);
            $row++;
        }

        // Crea el objeto writer para guardar el archivo Excel
        $writer = new Xlsx($spreadsheet);

        // Preparar la respuesta como un archivo descargable
        $event = Event::find($id);
        $fileName = 'tokens_evento_' . $event->event_name . '.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ];

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            $headers
        );

        return $response;
    }
}
