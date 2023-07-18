<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ControlEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreControlEventRequest;
use DB;

class ControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
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
    public function store(StoreControlEventRequest $request)
    {
        $control = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => '3'
        ]);

        $control_event = ControlEvent::create([
            'control_id' => $control->id,
            'event_id' => $request->event_id,
            'status_id' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $control
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $controls = DB::table('control_events')
                    ->join('users', 'control_events.control_id', '=', 'users.id')
                    ->join('events', 'control_events.event_id', '=', 'events.id')
                    ->where('events.organizator_id', '=', $id)
                    ->select('control_events.id', 'control_events.control_id', 'control_events.status_id', 'users.name', 'events.event_name')
                    ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $controls
        ], 200);
        
        echo $id;
    }

    /**
     * Show if the control user has the resources to accept the ticket.
     */
    public function status(Request $request)
    {
        $id = $request->segment(4);

        $control_event = ControlEvent::where('control_id', '=', $id)->where('status_id', '=', 1)->count();

        if ($control_event == 0) {
            return response()->json([
                'success' => true,
                'data' => 0
            ], 200);
        } else {
            return response()->json([
                'success' => true,
                'data' => 1
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Control $control)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        $control_event = ControlEvent::where('control_id', '=', $id)->first();
        $control_event->status_id = $request->status_id;
        $control_event->update();

        return response()->json([
            'success' => true,
            'data' => $control_event
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        $control_events = ControlEvent::where('control_id', '=', $id)->get();

        foreach ($control_events as $control_event) {
            $control_event = ControlEvent::find($control_event->id);
            $control_event->delete();
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
}
