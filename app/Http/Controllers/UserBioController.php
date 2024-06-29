<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserBioRequest;
use App\Http\Requests\UpdateUserBioRequest;
use App\Http\Resources\UserBioResource;
use App\Models\UserBio;
use Illuminate\Http\Request;

class UserBioController extends Controller
{
    public function index()
    {
        $usersResource = UserBioResource::collection(UserBio::with([
            'user'
        ])->get());
        return json_encode($usersResource, 200);
    }

    public function show(UserBio $userBio)
    {
        $userRelationship = UserBio::with([
            'user'
        ])->find($userBio->id);

        $userResource = new UserBioResource($userRelationship);

        return response()->json($userResource, 200);
    }

    public function store(StoreUserBioRequest $request)
    {
        $resumeName = 'resume-' .  time() . '.' . $request->file('resume')->extension();
        $request->file('resume')->move(public_path('files'), $resumeName);

        $portfolioName = 'portfolio-' .  time() . '.' . $request->file('portfolio')->extension();
        $request->file('portfolio')->move(public_path('files'), $portfolioName);

        $userBio = UserBio::create(array_merge($request->validated(), [
            'resume' => url('/') . '/files/' . $resumeName,
            'portfolio' => url('/') . '/files/' . $portfolioName,
        ]));
        $userBioRelationship = UserBio::with([
            'user'
        ])->find($userBio->id);

        $userBioResource = new UserBioResource($userBioRelationship);
        return json_encode($userBioResource, 200);
    }



    public function update(UpdateUserBioRequest $request)
    {
        $resumeName = 'resume-' .  time() . '.' . $request->file('resume')->extension();
        $request->file('resume')->move(public_path('files'), $resumeName);

        $portfolioName = 'portfolio-' .  time() . '.' . $request->file('portfolio')->extension();
        $request->file('portfolio')->move(public_path('files'), $portfolioName);

        $userBio = UserBio::create(array_merge($request->validated(), [
            'resume' => url('/') . '/files/' . $resumeName,
            'portfolio' => url('/') . '/files/' . $portfolioName,
        ]));
        $userBioRelationship = UserBio::with([
            'user'
        ])->find($userBio->id);

        $userBioResource = new UserBioResource($userBioRelationship);
        return json_encode($userBioResource, 200);
    }

    public function destroy($id)
    {
        $user = UserBio::find($id);

        if (!$user) {
            return response()->json("User Bio not found", 404);
        }

        $user->delete();

        return response()->json("Deleted user bio id: " . $id, 200);
    }
}
