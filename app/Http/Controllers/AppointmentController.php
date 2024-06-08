<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use App\Models\UserTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointmentsResource = AppointmentResource::collection(Appointment::with(['mentor','student'])->get());
        return json_encode( $appointmentsResource, 200);
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
    public function store(StoreAppointmentRequest $request)
    {
        $student = User::find($request->student_id);
        $mentor = User::find($request->mentor_id);
        if($student->wallet < $mentor->rate) {
            return response()->json("Insufficient balance, please reload your e-wallet", 400);
        }

        // add validation for available schedule
        DB::beginTransaction();
        try {
            $appointment = Appointment::create(array_merge($request->validated(), [
                'status' => "PENDING"
            ]));
            $appointmentResource = new AppointmentResource($appointment);
            DB::commit();
            return json_encode( $appointmentResource, 200);
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
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        DB::beginTransaction();

        if($appointment->status == Constants::APPOINTMENT_PENDING && $request->status == Constants::APPOINTMENT_APPROVED) {
            // Create Class
        } else if($appointment->status == Constants::APPOINTMENT_APPROVED && $request->status == Constants::APPOINTMENT_DONE) {
            // Continue Payment
            $student = User::find($appointment->student_id);
            $mentor = User::find($appointment->mentor_id);
            $old_student_balance = $student->wallet;
            $new_student_balance = -$appointment->amount + $student->wallet;
            $old_mentor_balance = $mentor->wallet;
            $new_mentor_balance = $appointment->amount + $mentor->wallet;

            UserTransaction::insert([
                [
                    'user_id' => $appointment->student_id,
                    'amount' => -$appointment->amount,
                    'description' => Constants::APPOINTMENT_DONE_STUDENT,
                    'old_balance' => $old_student_balance,
                    'new_balance' => $new_student_balance,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ],
                [
                    'user_id' => $appointment->mentor_id,
                    'amount' => $appointment->amount,
                    'description' => Constants::APPOINTMENT_DONE_MENTOR,
                    'old_balance' => $old_mentor_balance,
                    'new_balance' => $new_mentor_balance,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ]
            ]);
            $student->wallet = $new_student_balance;
            $student->save();
            $mentor->wallet = $new_mentor_balance;
            $mentor->save();

        } else if($appointment->status == Constants::APPOINTMENT_APPROVED && $request->status == Constants::APPOINTMENT_FAILED) {
            // Void Payment
        } else {
            return json_encode( "Something went wrong. If issue persists, contact administrator", 400);
        }
        $appointment->status = $request->status;
        $appointment->save();
        DB::commit();
        $appointmentResource = new AppointmentResource($appointment);
        return json_encode( $appointmentResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
