<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Http\Resources\ClassResource;
use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classResource = ClassResource::collection(Classes::with(['appointment'])->get());
        return json_encode( $classResource, 200);
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
    public function store(StoreClassRequest $request)
    {
        DB::beginTransaction();
        try {
            $class = Classes::create($request->validated());
            $classRelationship = Classes::with(['appointment'])->find($class->id);
            $classResource = new ClassResource($classRelationship);
            DB::commit();
            return json_encode( $classResource, 200);
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
    public function update(UpdateClassRequest $request, Classes $class)
    {
        $class->fill($request->validated());
        $class->save();

        $classRelationship = Classes::with(['appointment'])->find($class->id);
        
        $classResource = new ClassResource($classRelationship);
        return json_encode( $classResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
