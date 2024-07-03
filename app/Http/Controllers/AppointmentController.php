<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\ClassResource;
use App\Models\Appointment;
use App\Models\Classes;
use App\Models\Receipt;
use App\Models\User;
use App\Models\UserTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointmentsResource = AppointmentResource::collection(Appointment::with(['mentor', 'student', 'class'])->get());
        return json_encode($appointmentsResource, 200);
    }

    public function search_class($appointment_id)
    {
        $classResource = new ClassResource(Classes::with(['appointment'])->where('appointment_id', $appointment_id)->firstOrFail());
        return json_encode($classResource, 200);
    }

    public function cacheAppointment($appointment_id)
    {
        Cache::put('last_appointment', $appointment_id, now()->addMinutes(1));
        return json_encode("Success", 200);
    }
    public function getCacheAppointment()
    {
        $last_appointment_id = Cache::get('last_appointment');
        return json_encode($last_appointment_id, 200);
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
        if ($student->wallet < $mentor->rate) {
            return response()->json("Insufficient balance, please reload your e-wallet", 400);
        }
        $scheduleExists = $mentor->mentor_appointments->where('date', Carbon::parse($request->date))->first();
        if ($scheduleExists) {
            return response()->json("Schedule already taken", 400);
        }
        $matched = 0;
        foreach ($mentor->schedules as $schedule) {
            $requestDate = Carbon::parse($request->date);
            $dayName = $requestDate->dayName;
            if ($dayName == $schedule->day) {
                $time = $requestDate->toTimeString();
                $parseTime = Carbon::parse($time);
                $from = Carbon::parse($schedule->from);
                $to = Carbon::parse($schedule->to);

                $hit = Carbon::createFromTimeString($parseTime);
                $start = Carbon::createFromTimeString($from);
                $end = Carbon::createFromTimeString($to);

                if ($hit->between($start, $end)) {
                    $matched++;
                }
            }
        }
        if ($matched == 0) {
            return json_encode("Invalid Schedule");
        }

        // add validation for available schedule
        DB::beginTransaction();
        try {
            $appointment = Appointment::create(array_merge($request->validated(), [
                'status' => "PENDING"
            ]));
            $appointmentRelationship = Appointment::with(['mentor', 'student'])->find($appointment->id);
            $appointmentResource = new AppointmentResource($appointmentRelationship);
            DB::commit();
            return json_encode($appointmentResource, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return json_encode($e, 400);
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

        if ($appointment->status == Constants::APPOINTMENT_PENDING && $request->status == Constants::APPOINTMENT_APPROVED) {
            // Create Class
            $student = User::find($appointment->student_id);
            $old_student_balance = $student->wallet;
            $new_student_balance = -$appointment->amount + $student->wallet;

            UserTransaction::insert([
                [
                    'user_id' => $appointment->student_id,
                    'amount' => -$appointment->amount,
                    'description' => Constants::APPOINTMENT_APPROVED_STUDENT,
                    'old_balance' => $old_student_balance,
                    'new_balance' => $new_student_balance,
                    'reference_number' => 'AUTO_DEDUCT_FROM_SYSTEM',
                    'screenshot' => null,
                    'sender_name' => null,
                    'account_name' => null,
                    'account_number' => null,
                    'status' => 1,
                    'processed' => 1,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ],
            ]);

            $student->wallet = $new_student_balance;
            $student->save();
            Classes::insert([
                'appointment_id' => $appointment->id,
                'name' => $appointment->mentor->first_name . " and " . $appointment->student->first_name . " Class",
                'class_id' => "PLACEHOLDER",
                'start_time' => date('Y-m-d H:i:s'),
                'end_time' => date('Y-m-d H:i:s'),
                'end_time' => date('Y-m-d H:i:s'),
                'duration' => "1 Hour",
                'status' => Constants::APPOINTMENT_APPROVED,
                'created_at' =>  date('Y-m-d H:i:s'),
                'updated_at' =>  date('Y-m-d H:i:s')
            ]);
        } else if ($appointment->status == Constants::APPOINTMENT_APPROVED && $request->status == Constants::APPOINTMENT_DONE) {
            // Continue Payment
            $mentor = User::find($appointment->mentor_id);
            $service_charge = $appointment->amount * .1;
            $old_mentor_balance = $mentor->wallet;
            $new_mentor_balance = ($appointment->amount - $service_charge) + $mentor->wallet;

            UserTransaction::insert([
                [
                    'user_id' => $appointment->mentor_id,
                    'amount' => $appointment->amount - $service_charge,
                    'service_charge' => $service_charge,
                    'description' => Constants::APPOINTMENT_DONE_MENTOR,
                    'old_balance' => $old_mentor_balance,
                    'new_balance' => $new_mentor_balance,
                    'reference_number' => 'AUTO_INCREASE_FROM_SYSTEM',
                    'screenshot' => null,
                    'sender_name' => null,
                    'account_name' => null,
                    'account_number' => null,
                    'status' => 1,
                    'processed' => 1,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ]
            ]);

            Receipt::insert([
                [
                    'sender_id' => $appointment->student_id,
                    'sender_name' => $appointment->student->first_name . " " . $appointment->student->last_name,
                    'receiver_id' => $appointment->mentor_id,
                    'receiver_name' => $appointment->mentor->first_name . " " . $appointment->mentor->last_name,
                    'amount' => $appointment->amount - $service_charge,
                    'service_charge' => $service_charge,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ]
            ]);

            $mentor->wallet = $new_mentor_balance;
            $mentor->save();
        } else if ($appointment->status == Constants::APPOINTMENT_APPROVED && $request->status == Constants::APPOINTMENT_FAILED) {
            // Void Payment

            $student = User::find($appointment->student_id);
            $old_student_balance = $student->wallet;
            $new_student_balance = $appointment->amount + $student->wallet;

            UserTransaction::insert([
                [
                    'user_id' => $appointment->student_id,
                    'amount' => -$appointment->amount,
                    'description' => Constants::APPOINTMENT_FAILED_STUDENT,
                    'old_balance' => $old_student_balance,
                    'new_balance' => $new_student_balance,
                    'reference_number' => 'AUTO_DEDUCT_FROM_SYSTEM',
                    'screenshot' => null,
                    'sender_name' => null,
                    'account_name' => null,
                    'account_number' => null,
                    'status' => 1,
                    'processed' => 1,
                    'created_at' =>  date('Y-m-d H:i:s'),
                    'updated_at' =>  date('Y-m-d H:i:s')
                ],
            ]);

            $student->wallet = $new_student_balance;
            $student->save();
        } else {
            return json_encode("Something went wrong. If issue persists, contact administrator", 400);
        }
        $appointment->status = $request->status;
        $appointment->save();
        DB::commit();
        $appointmentRelationship = Appointment::with(['mentor', 'student', 'class'])->find($appointment->id);
        $appointmentResource = new AppointmentResource($appointmentRelationship);
        return json_encode($appointmentResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
