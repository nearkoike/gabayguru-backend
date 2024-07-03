<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserPaymentDetailsRequest;
use App\Http\Requests\UpdateUserPaymentDetailsRequest;
use App\Http\Resources\UserPaymentDetailsResource;
use App\Models\UserPaymentDetail;
use Illuminate\Http\Request;

class UserPaymentDetailsController extends Controller
{
    public function index()
    {
        $usersResource = UserPaymentDetailsResource::collection(UserPaymentDetail::with([
            'user'
        ])->get());
        return json_encode($usersResource, 200);
    }
    public function store(StoreUserPaymentDetailsRequest $request)
    {
        $userBio = UserPaymentDetail::create($request->validated());
        $userBioRelationship = UserPaymentDetail::with([
            'user'
        ])->find($userBio->id);

        $userBioResource = new UserPaymentDetailsResource($userBioRelationship);
        return json_encode($userBioResource, 200);
    }

    public function update(UpdateUserPaymentDetailsRequest $request, UserPaymentDetail $ticket)
    {
        $ticket->fill($request->validated());
        $ticket->save();

        $ticketRelationship = UserPaymentDetail::with(['user'])->find($ticket->id);

        $ticketRelationship = new UserPaymentDetailsResource($ticketRelationship);
        return json_encode($ticketRelationship, 200);
    }
}
