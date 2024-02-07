<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    public function getCards()
    {
        $user = auth()->user();
        $cards = $user->cards;

        return response()->json(['cards' => $cards], 200);
    }

    public function addCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cardNumber' => 'required',
            'expiryDate' => 'required',
            'CVV' => 'required',
            'pin' => 'required',
            'type' => 'required|in:credit,debit', // Assuming 'type' can be credit or debit
            // Add other card fields as needed
        ]);
        

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $card = new Card($request->all());

        $user->cards()->save($card);

        return response()->json(['message' => 'Card added successfully'], 201);
    }

    public function updateCard(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cardNumber' => 'required',
            'expiryDate' => 'required',
            'CVV' => 'required',
            'pin' => 'required',
            'type' => 'required|in:credit,debit', // Assuming 'type' can be credit or debit
            // Add other card fields as needed
        ]);
        

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $card = $user->cards()->find($id);

        if (!$card) {
            return response()->json(['error' => 'Card not found'], 404);
        }

        $card->update($request->all());

        return response()->json(['message' => 'Card updated successfully'], 200);
    }

    public function deleteCard($id)
    {
        $user = auth()->user();
        $card = $user->cards()->find($id);

        if (!$card) {
            return response()->json(['error' => 'Card not found'], 404);
        }

        $card->delete();

        return response()->json(['message' => 'Card deleted successfully'], 200);
    }
}
