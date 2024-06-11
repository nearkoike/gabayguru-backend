<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketResource = TicketResource::collection(Ticket::with(['support','student'])->get());
        return json_encode( $ticketResource, 200);
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
    public function store(StoreTicketRequest $request)
    {
        DB::beginTransaction();
        try {
            $ticket = Ticket::create($request->validated());
            $ticketRelationship = Ticket::with(['support','student'])->find($ticket->id);
            $ticketResource = new TicketResource($ticketRelationship);
            DB::commit();
            return json_encode( $ticketResource, 200);
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
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->fill($request->validated());
        $ticket->save();
        
        $ticketRelationship = Ticket::with(['support','student'])->find($ticket->id);

        $ticketRelationship = new TicketResource($ticketRelationship);
        return json_encode( $ticketRelationship, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
