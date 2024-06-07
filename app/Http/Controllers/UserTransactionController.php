<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserTransactionRequest;
use App\Http\Resources\UserTransactionResource;
use App\Models\User;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTransactionResource = UserTransactionResource::collection(UserTransaction::with('user')->get());
        return json_encode( $userTransactionResource, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserTransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::find($request->user_id);
            $old_balance = $user->wallet;
            $new_balance = $request->amount + $user->wallet;
            $userTransaction = UserTransaction::create(array_merge($request->validated(), [
                'old_balance' => $old_balance,
                'new_balance' => $new_balance
            ]));
            $user->wallet = $new_balance;
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode( $e, 400);
        }
        $userTransactionResource = new UserTransactionResource($userTransaction);
        return json_encode( $userTransactionResource, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserTransaction $userTransaction)
    {
        if (!$userTransaction) {
            return response()->json("User Transaction not found", 404);
        }
        
        $userTransaction->delete();
    
        return response()->json("Deleted user transaction id: " .  $userTransaction->id, 200);
    }
}
