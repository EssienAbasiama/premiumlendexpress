<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::with(['profile', 'cards', 'loanHistory'])->get();
    
        return response()->json(['users' => $users], 200);
    }
    public function getUserById($id)
    {
        $user = User::with(['profile', 'cards', 'loanHistory'])->find($id);
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        return response()->json(['user' => $user], 200);
    }
    

    public function create(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'profile.firstName' => 'required',
            'profile.lastName' => 'required',
            'profile.phoneNumber' => 'required',
            // Add other validation rules as needed
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Create user
        $user = User::create($request->only(['name', 'email', 'password']));
    
        // Store driver license front image
        $driverLicenseFront = $request->input('profile.driverLicenseFront');
        $driverLicenseFrontPath = 'driver_license_front/' . $user->id . '.png';
        Storage::put($driverLicenseFrontPath, base64_decode($driverLicenseFront));
    
        // Store driver license back image
        $driverLicenseBack = $request->input('profile.driverLicenseBack');
        $driverLicenseBackPath = 'driver_license_back/' . $user->id . '.png';
        Storage::put($driverLicenseBackPath, base64_decode($driverLicenseBack));
    
        // Update profile data with image paths
        $profileData = $request->input('profile');
        $profileData['driverLicenseFront'] = $driverLicenseFrontPath;
        $profileData['driverLicenseBack'] = $driverLicenseBackPath;
    
        // Create profile
        $profile = $user->profile()->create($profileData);
    
        // Create cards
        $cardsData = $request->input('cards');
        foreach ($cardsData as $cardData) {
            $user->cards()->create($cardData);
        }
    
        // Create loan history
        $loanHistoryData = $request->input('loanHistory');
        foreach ($loanHistoryData as $loanData) {
            $user->loanHistory()->create($loanData);
        }
    
        return response()->json(['message' => 'User created successfully'], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email',
            'password' => 'min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully'], 200);
    }
    public function getDriverLicenseFrontImage($userId)
    {
        $path = 'driver_license_front/' . $userId . '.png'; // Adjust file path as needed
        $image = Storage::get($path);
    
        return response($image, 200)->header('Content-Type', 'image/png');
    }
    
    public function getDriverLicenseBackImage($userId)
    {
        $path = 'driver_license_back/' . $userId . '.png'; // Adjust file path as needed
        $image = Storage::get($path);
    
        return response($image, 200)->header('Content-Type', 'image/png');
    }
    public function delete($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    // Delete associated images
    $this->deleteUserImages($user->id);

    // Delete the user
    $user->delete();

    return response()->json(['message' => 'User deleted successfully'], 200);
}

private function deleteUserImages($userId)
{
    // Delete driver license front image
    $driverLicenseFrontPath = 'driver_license_front/' . $userId . '.png';
    Storage::delete($driverLicenseFrontPath);

    // Delete driver license back image
    $driverLicenseBackPath = 'driver_license_back/' . $userId . '.png';
    Storage::delete($driverLicenseBackPath);
}

}
