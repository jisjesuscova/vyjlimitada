<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use PDF;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PdfController extends Controller
{
    public function generatePdf($id)
    {
        $ticket = Ticket::join('events', 'tickets.event_id', '=', 'events.id')
                  ->where('tickets.id', $id)
                  ->select('tickets.*', 'events.*')
                  ->first();

        $title = $ticket->event_name;
        
        $event_date = date('m-d-Y', strtotime($ticket->event_date));

        $ticket_number = $ticket->id;

        $qrCode = QrCode::size(200)
                  ->generate($ticket->token);

        $pdf = PDF::loadView('pdfs.ticket', compact('title', 'event_date', 'ticket_number', 'qrCode'));

        return $pdf->download();
    }

    public function massivePdf()
    {
        $tickets = Ticket::all();

        $qrCodes = [];
        foreach ($tickets as $ticket) {
            $qrCode = QrCode::format('png')->size(200)->generate($ticket->token);
            $qrCodes[] = [
                'name' => 'qr_' . $ticket->id . '.png',
                'data' => $qrCode
            ];
        }

        $zipFileName = 'qrs.zip';
        $zipFilePath = public_path($zipFileName);

        $zip = new ZipArchive;
        $zip->open($zipFilePath, ZipArchive::CREATE);
        foreach ($qrCodes as $qrCode) {
            $zip->addFromString('qr/' . $qrCode['name'], $qrCode['data']);
        }
        $zip->close();

        return response()->download($zipFilePath)->deleteFileAfterSend();
    }

}
