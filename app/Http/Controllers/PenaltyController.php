<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenaltyRequest;
use App\Http\Requests\UpdatePenaltyRequest;
use App\Http\Resources\PenaltyResource;
use App\Models\Penalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penaltyResource = PenaltyResource::collection(Penalty::with(['user'])->get());
        return json_encode( $penaltyResource, 200);
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
    public function store(StorePenaltyRequest $request)
    {
        DB::beginTransaction();
        try {
            $penalty = Penalty::create(array_merge($request->validated(), [
                'status' => 1
            ]));
            $penaltyRelationship = Penalty::with(['user'])->find($penalty->id);

            $penaltyResource = new PenaltyResource($penaltyRelationship);
            
            DB::commit();
            return json_encode( $penaltyResource, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode( $e, 400);
        }
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
    public function update(UpdatePenaltyRequest $request, Penalty $penalty)
    {
        $penalty->fill($request->validated());
        $penalty->save();
        
        $penaltyRelationship = Penalty::with(['user'])->find($penalty->id);

        $penaltyResource = new PenaltyResource($penaltyRelationship);
        return json_encode( $penaltyResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
