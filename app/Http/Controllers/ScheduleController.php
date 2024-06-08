<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduleResource = ScheduleResource::collection(Schedule::with(['user'])->get());
        return json_encode( $scheduleResource, 200);
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
    public function store(StoreScheduleRequest $request)
    {
        // add validation for available schedule
        DB::beginTransaction();
        try {
            $schedule = Schedule::create($request->validated());
            $scheduleResource = new ScheduleResource($schedule);
            DB::commit();
            return json_encode( $scheduleResource, 200);
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
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $schedule->fill($request->validated());
        $schedule->save();
        
        $scheduleResource = new ScheduleResource($schedule);
        return json_encode( $scheduleResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
