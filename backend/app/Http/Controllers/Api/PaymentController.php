<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentService
    ) {}

    public function initiate(Request $request)
    {
        $request->validate([
            'gateway' => 'required|in:esewa,khalti',
            'amount' => 'required|numeric|min:10|max:100000',
        ]);

        try {
            $result = $this->paymentService->initiatePayment(
                $request->user(),
                $request->gateway,
                $request->amount
            );

            return response()->json([
                'message' => 'Payment initiated',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function verifyEsewa(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        try {
            $result = $this->paymentService->verifyEsewaPayment(
                $request->transaction_id
            );

            return response()->json([
                'message' => 'Payment verified successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function verifyKhalti(Request $request)
    {
        $request->validate([
            'pidx' => 'required|string',
        ]);

        try {
            $result = $this->paymentService->verifyKhaltiPayment($request->pidx);

            return response()->json([
                'message' => 'Payment verified successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function history(Request $request)
    {
        $transactions = $request->user()
            ->paymentTransactions()
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($transactions);
    }
}
