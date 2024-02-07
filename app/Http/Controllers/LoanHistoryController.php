<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanHistory;
use Illuminate\Support\Facades\Validator;

class LoanHistoryController extends Controller
{
    public function getLoanHistory()
    {
        $user = auth()->user();
        $loanHistory = $user->loanHistory;

        return response()->json(['loanHistory' => $loanHistory], 200);
    }

    public function addLoanHistory(Request $request)
    {
       $validator = Validator::make($request->all(), [
    'name' => 'required',
    'type' => 'required|in:deposit,debit', // Assuming 'type' can be deposit or debit
    'status' => 'required',
    'amount' => 'required|numeric',
    'purpose' => 'required',
    // Add other loan history fields as needed
]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $loan = new LoanHistory($request->all());

        $user->loanHistory()->save($loan);

        return response()->json(['message' => 'Loan history added successfully'], 201);
    }

    public function updateLoanHistory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required|in:deposit,debit', // Assuming 'type' can be deposit or debit
            'status' => 'required',
            'amount' => 'required|numeric',
            'purpose' => 'required',
            // Add other loan history fields as needed
        ]);
        

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = auth()->user();
        $loan = $user->loanHistory()->find($id);

        if (!$loan) {
            return response()->json(['error' => 'Loan history not found'], 404);
        }

        $loan->update($request->all());

        return response()->json(['message' => 'Loan history updated successfully'], 200);
    }

    public function deleteLoanHistory($id)
    {
        $user = auth()->user();
        $loan = $user->loanHistory()->find($id);

        if (!$loan) {
            return response()->json(['error' => 'Loan history not found'], 404);
        }

        $loan->delete();

        return response()->json(['message' => 'Loan history deleted successfully'], 200);
    }
}
