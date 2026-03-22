<?php

namespace App\Services;

use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    public function initiateEsewaPayment(User $user, float $amount): array
    {
        $wallet = $this->walletService->getOrCreateWallet($user);

        $paymentTx = PaymentTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'gateway' => 'esewa',
            'amount' => $amount,
            'status' => 'initiated',
            'purpose' => 'wallet_topup',
        ]);

        // eSewa payment parameters
        $params = [
            'amt' => $amount,
            'pdc' => 0,
            'psc' => 0,
            'txAmt' => 0,
            'tAmt' => $amount,
            'pid' => 'EVCHARGE-' . $paymentTx->id,
            'scd' => config('services.esewa.merchant_code', 'EPAYTEST'),
            'su' => config('services.esewa.success_url', url('/api/payment/esewa/success')),
            'fu' => config('services.esewa.failure_url', url('/api/payment/esewa/failure')),
        ];

        $esewaUrl = config('services.esewa.url', 'https://uat.esewa.com.np/epay/main');

        return [
            'payment_transaction_id' => $paymentTx->id,
            'gateway' => 'esewa',
            'payment_url' => $esewaUrl,
            'params' => $params,
        ];
    }

    public function verifyEsewaPayment(string $referenceId, string $productId, float $amount): bool
    {
        $verifyUrl = config('services.esewa.verify_url', 'https://uat.esewa.com.np/epay/transrec');
        $merchantCode = config('services.esewa.merchant_code', 'EPAYTEST');

        $response = Http::asForm()->post($verifyUrl, [
            'amt' => $amount,
            'rid' => $referenceId,
            'pid' => $productId,
            'scd' => $merchantCode,
        ]);

        return str_contains($response->body(), 'Success');
    }

    public function handleEsewaSuccess(string $productId, string $referenceId, float $amount): PaymentTransaction
    {
        $txId = (int) str_replace('EVCHARGE-', '', $productId);
        $paymentTx = PaymentTransaction::findOrFail($txId);

        if ($paymentTx->status !== 'initiated') {
            throw new \Exception('Payment already processed.');
        }

        $verified = $this->verifyEsewaPayment($referenceId, $productId, $amount);

        if (! $verified) {
            $paymentTx->update([
                'status' => 'failed',
                'gateway_response' => ['error' => 'Verification failed'],
            ]);
            throw new \Exception('Payment verification failed.');
        }

        $paymentTx->update([
            'status' => 'completed',
            'gateway_transaction_id' => $referenceId,
            'gateway_response' => ['reference_id' => $referenceId, 'verified' => true],
        ]);

        $this->walletService->addFunds($paymentTx->user, $paymentTx->amount);

        return $paymentTx;
    }

    public function initiateKhaltiPayment(User $user, float $amount): array
    {
        $wallet = $this->walletService->getOrCreateWallet($user);

        $paymentTx = PaymentTransaction::create([
            'user_id' => $user->id,
            'wallet_id' => $wallet->id,
            'gateway' => 'khalti',
            'amount' => $amount,
            'status' => 'initiated',
            'purpose' => 'wallet_topup',
        ]);

        $khaltiUrl = config('services.khalti.url', 'https://a.khalti.com/api/v2/epayment/initiate/');
        $secretKey = config('services.khalti.secret_key', '');

        $response = Http::withHeaders([
            'Authorization' => 'key ' . $secretKey,
        ])->post($khaltiUrl, [
            'return_url' => config('services.khalti.return_url', url('/api/payment/khalti/verify')),
            'website_url' => config('app.url', 'http://localhost'),
            'amount' => $amount * 100, // Khalti uses paisa
            'purchase_order_id' => 'EVCHARGE-' . $paymentTx->id,
            'purchase_order_name' => 'Wallet Top-up',
        ]);

        $data = $response->json();

        if (isset($data['payment_url'])) {
            $paymentTx->update([
                'gateway_transaction_id' => $data['pidx'] ?? null,
                'gateway_response' => $data,
            ]);

            return [
                'payment_transaction_id' => $paymentTx->id,
                'gateway' => 'khalti',
                'payment_url' => $data['payment_url'],
                'pidx' => $data['pidx'] ?? null,
            ];
        }

        $paymentTx->update(['status' => 'failed', 'gateway_response' => $data]);
        throw new \Exception($data['detail'] ?? 'Failed to initiate Khalti payment.');
    }

    public function verifyKhaltiPayment(string $pidx): PaymentTransaction
    {
        $lookupUrl = config('services.khalti.lookup_url', 'https://a.khalti.com/api/v2/epayment/lookup/');
        $secretKey = config('services.khalti.secret_key', '');

        $response = Http::withHeaders([
            'Authorization' => 'key ' . $secretKey,
        ])->post($lookupUrl, ['pidx' => $pidx]);

        $data = $response->json();

        $paymentTx = PaymentTransaction::where('gateway_transaction_id', $pidx)->firstOrFail();

        if ($paymentTx->status !== 'initiated') {
            throw new \Exception('Payment already processed.');
        }

        if (($data['status'] ?? '') === 'Completed') {
            $paymentTx->update([
                'status' => 'completed',
                'gateway_response' => $data,
            ]);

            $this->walletService->addFunds($paymentTx->user, $paymentTx->amount);

            return $paymentTx;
        }

        $paymentTx->update([
            'status' => 'failed',
            'gateway_response' => $data,
        ]);

        throw new \Exception('Khalti payment not completed.');
    }
}
