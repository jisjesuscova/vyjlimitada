<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BranchOffice;
use App\Models\Cashier;
use App\Models\Collection;
use App\Models\Dte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\StoreDteRequest;
use DB;
use DateTime;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = Collection::from('collections as c')
        ->selectRaw('c.id, branch_offices.id as branch_office_id, cashiers.id as cashier_id, branch_offices.branch_office, cashiers.cashier, c.cash_amount, c.card_amount, c.created_at, c.updated_at')
        ->leftJoin('branch_offices', 'branch_offices.id', '=', 'c.branch_office_id')
        ->leftJoin('cashiers', 'cashiers.id', '=', 'c.cashier_id')
        ->orderBy('c.created_at', 'desc')
        ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $collections
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branch_offices = BranchOffice::all();
        
        foreach($branch_offices as $branch_office) 
        {
            $cashiers = Cashier::where('branch_office_id', $branch_office->id)->get();

            $date = date('Y-m-d', strtotime('-5 days', strtotime(date('Y-m-d'))));

            foreach($cashiers as $cashier) {
                
                $dtes = Dte::from('dtes as c')
                        ->selectRaw('SUM(c.cash_amount) as cash_amount, SUM(c.card_amount) as card_amount, COUNT(*) as quantity, DATE(c.created_at) as date')
                        ->groupBy(DB::raw('DATE(c.created_at)'))
                        ->whereDate('created_at', '>=', $date)
                        ->where('c.branch_office_id', $branch_office->id)
                        ->where('c.cashier_id', $cashier->id)
                        ->get();

                foreach($dtes as $dte) {
                    $collection_qty = Collection::where('branch_office_id', $branch_office->id)->where('cashier_id', $cashier->id)->whereRaw('DATE(created_at) = "'. $dte->date .'"')->count();

                    if($collection_qty > 0) {
                        $collection = Collection::where('branch_office_id', $branch_office->id)->where('cashier_id', $cashier->id)->whereRaw('DATE(created_at) = "'. $dte->date .'"')->first();
                        $cash_amount = $collection->cash_amount;
                        $card_amount = $collection->card_amount;
                        $ticket_number = $collection->quantity;
                        $old_amount = $collection->cash_amount + $collection->card_amount;
                        $new_amount = $dte->cash_amount + $dte->card_amount;
                        $collection->cash_amount = $dte->cash_amount;
                        $collection->card_amount = $dte->card_amount;
                        $collection->quantity = $dte->quantity;
                        $tz = 'America/Santiago';
                        $timestamp = time();
                        $dt = new DateTime("now", new \DateTimeZone($tz));
                        $dt->setTimestamp($timestamp);
                        $datetime = $dt->format('Y-m-d H:i:s');
                        $collection->updated_at = $datetime;
                        
                        if($old_amount < $new_amount) {
                            $collection->save();
                        }
                    } else {
                        $collection = new Collection;
                        $cash_amount = $collection->cash_amount;
                        $card_amount = $collection->card_amount;
                        $ticket_number = $collection->quantity;
                        $collection->branch_office_id = $branch_office->id;
                        $collection->cashier_id = $cashier->id;
                        $collection->cash_amount = $dte->cash_amount;
                        $collection->card_amount = $dte->card_amount;
                        $collection->quantity = $dte->quantity;
                        $collection->created_at = $dte->date .' 00:00:00';
                        $tz = 'America/Santiago';
                        $timestamp = time();
                        $dt = new DateTime("now", new \DateTimeZone($tz)); //first argument "must" be a string
                        $dt->setTimestamp($timestamp); //adjust the object to correct timestamp
                        $datetime = $dt->format('Y-m-d H:i:s');

                        $collection->updated_at = $datetime;
                        $collection->save();
                    }
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BranchOffice $dte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dte $dte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dte $dte)
    {
        //
    }
}
