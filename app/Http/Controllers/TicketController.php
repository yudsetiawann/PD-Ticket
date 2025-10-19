<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat tiket miliknya
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Generate PDF dan tampilkan di browser
        $pdf = Pdf::loadView('pdf.eticket', compact('order'));
        return $pdf->stream('e-ticket-' . $order->ticket_code . '.pdf');
    }
}
