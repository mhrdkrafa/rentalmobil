<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request, PaymentService $paymentService)
    {
        Log::info('Midtrans Webhook Received: ', $request->all());

        $success = $paymentService->handleWebhook($request->all());

        if ($success) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
    }
}
