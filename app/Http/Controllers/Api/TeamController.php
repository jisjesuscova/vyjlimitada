<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\PutTeamRequest;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with('user:id,name')
             ->select('id', 'user_id', 'team_name')
             ->orderByDesc('id')
             ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $teams
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
    public function store(StoreTeamRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol_id' => '2'
        ]);

        $team = Team::create([
            'team_name' => $request->team_name,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'data' => $team
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        $team->team_name = $request->team_name;
        $team->save();

        return response()->json([
            'success' => true,
            'data' => $team
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();

        $user = User::find($team->user_id);
        $user->delete();

        return response()->json([
            'success' => true,
            'data' => $team
        ], 200);
    }
}
