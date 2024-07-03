<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\UserResource;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('gabay-guru')->plainTextToken;

            $user['token'] = $token;

            return response()->json($user, 200);
        }

        return response()->json(['message' => 'Invalid email or password.'], Response::HTTP_UNAUTHORIZED);
    }

    public function receipt_index()
    {
        $usersResource = ReceiptResource::collection(Receipt::with([
            'sender',
            'receiver'
        ])->get());
        return json_encode($usersResource, 200);
    }


    public function receipt_show($user_id)
    {
        $usersResource = ReceiptResource::collection(Receipt::with([
            'sender',
            'receiver'
        ])->where('sender_id', $user_id)->orWhere('receiver_id', $user_id)->get());
        return json_encode($usersResource, 200);
    }

    public function register(StoreUserRequest $request)
    {
        $imageName = time() . '.' . $request->file('profile_picture')->extension();
        $request->file('profile_picture')->move(public_path('images'), $imageName);

        $user = User::create(array_merge($request->validated(), [
            'password' => bcrypt($request->input('password')),
            'profile_picture' => url('/') . '/images/' . $imageName
        ]));

        $email = $request->input('email');
        $name = $request->input('first_name') . " " . $request->input('last_name');

        // The email sending is done using the to method on the Mail facade

        // Mail::to($email)->send(new MyTestEmail($name));

        $token = $user->createToken('gabay-guru');
        $user['token'] = $token->plainTextToken;

        return response()->json($user, 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usersResource = UserResource::collection(User::with([
            'support_tickets',
            'student_tickets',
            'schedules',
            'transactions',
            'student_appointments',
            'mentor_appointments',
            'penalties',
            'user_bio'
        ])->get());
        return json_encode($usersResource, 200);
    }

    public function verified()
    {
        $usersResource = UserResource::collection(User::with([
            'support_tickets',
            'student_tickets',
            'schedules',
            'transactions',
            'student_appointments',
            'mentor_appointments',
            'penalties',
            'user_bio'
        ])->get());
        return json_encode($usersResource, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function callback()
    {
        return $_GET['code'];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $imageName = time() . '.' . $request->file('profile_picture')->extension();
        $request->file('profile_picture')->move(public_path('images'), $imageName);

        $user = User::create(array_merge($request->validated(), [
            'password' => bcrypt($request->input('password')),
            'profile_picture' => url('/') . '/images/' . $imageName
        ]));

        $email = $request->input('email');
        $name = $request->input('first_name') . " " . $request->input('last_name');

        $token = $user->createToken('gabay-guru');
        $user['token'] = $token->plainTextToken;

        $userRelationship = User::with([
            'support_tickets',
            'student_tickets',
            'schedules',
            'transactions',
            'student_appointments',
            'mentor_appointments'
        ])->find($user->id);

        $userResource = new UserResource($userRelationship);
        return json_encode($userResource, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $userRelationship = User::with([
            'support_tickets',
            'student_tickets',
            'schedules',
            'transactions',
            'student_appointments',
            'mentor_appointments',
            'penalties',
            'user_bio'
        ])->find($user->id);

        $userResource = new UserResource($userRelationship);

        return response()->json($userResource, 200);
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
    public function update(UpdateUserRequest $request, User $user)
    {
        $photoPath = $user->profile_picture;
        $password = $user->password;

        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $request->file('profile_picture')->move(public_path('images'), $imageName);
            $photoPath = url('/images/' . $imageName);
        }

        if ($request->filled('password')) {
            $password = bcrypt($request->input('password'));
        }

        $user->fill(array_merge($request->validated(), [
            'password' => $password,
            'profile_picture' => $photoPath
        ]));
        $user->save();

        $userRelationship = User::with([
            'support_tickets',
            'student_tickets',
            'schedules',
            'transactions',
            'student_appointments',
            'mentor_appointments'
        ])->find($user->id);

        $userResource = new UserResource($userRelationship);
        return json_encode($userResource, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json("User not found", 404);
        }

        $user->delete();

        return response()->json("Deleted user id: " . $id, 200);
    }
}
