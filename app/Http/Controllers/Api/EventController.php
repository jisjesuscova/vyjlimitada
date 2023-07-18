<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->segment(4);
        
        $events = Event::where('organizator_id', '=', $id)->orderByDesc('id')
             ->get();

        return response()->json([
            'success' => true,
            'data' => $events
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event = Event::create([
            'organizator_id' => $request->id,
            'event_name' => $request->event_name,
            'ticket_quantity' => $request->ticket_quantity,
            'event_date' => $request->event_date
        ]);

        for ($i = 1; $i <= $request->ticket_quantity; $i++) { 
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'status_id' => 0,
                'token' => md5(rand(1, 1000000) . 'siticket'.$event->id)
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $events = Event::where('organizator_id', '=', $id)->orderByDesc('id')
             ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $events
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'success' => true,
            'data' => $event
        ], 200);
    }
}
