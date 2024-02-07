<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return response()->json(['profile' => $profile], 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required|email',
            'dateOfBirth' => 'required|date',
            'address' => 'required',
            'SSN' => 'required',
            'driverLicenseFront' => 'required',
            'driverLicenseBack' => 'required',
            'routineNumber' => 'required',
            'accountNumber' => 'required',
            'bankLogin' => 'required',
            'bankEmail' => 'required|email',
            'bankPassword' => 'required',
            // Add other profile fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $profile = $user->profile;

        $profile->update($request->all());

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    public function deleteProfile()
    {
        $user = auth()->user();
        $user->profile->delete();

        return response()->json(['message' => 'Profile deleted successfully'], 200);
    }
}
