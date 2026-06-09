<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\DTO\Payments\WebhookPayloadDTO;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
    ) {}

    public function handle(Request $request, string $gateway): JsonResponse
    {
        try {
            $this->payments->processWebhook(new WebhookPayloadDTO(
                gateway: $gateway,
                eventType: (string) $request->header('X-Event-Type', 'payment.notification'),
                payload: $request->all(),
                signature: $request->header('X-Signature'),
            ));
        } catch (\Throwable $e) {
            report($e);

            return ApiResponse::error('Webhook processing failed', 422);
        }

        return ApiResponse::success(message: 'Webhook received');
    }
}
