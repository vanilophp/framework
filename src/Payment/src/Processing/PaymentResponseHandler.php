<?php

declare(strict_types=1);

/**
 * Contains the PaymentResponseHandler class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-12
 *
 */

namespace Vanilo\Payment\Processing;

use Illuminate\Support\Facades\Event;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentEvent;
use Vanilo\Payment\Contracts\PaymentEventMap;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentHistoryProxy;

final class PaymentResponseHandler
{
    private Payment $payment;

    private PaymentResponse $response;

    private PaymentStatus $oldStatus;

    private PaymentStatus $newStatus;

    private DefaultEventMappingRules $eventMappingRules;

    public function __construct(Payment $payment, PaymentResponse $response, PaymentEventMap $eventMap = null)
    {
        $this->payment = $payment;
        $this->response = $response;

        $this->eventMappingRules = $eventMap ?? new DefaultEventMappingRules();

        $this->oldStatus = $payment->getStatus();
        $this->newStatus = $response->getStatus();
    }

    public function writeResponseToHistory(): void
    {
        PaymentHistoryProxy::addPaymentResponse($this->payment, $this->response, $this->oldStatus);
    }

    public function updatePayment(): void
    {
        $this->payment->status = $this->newStatus;
        $amountPaid = $this->response->getAmountPaid();
        if (null !== $amountPaid) {
            $this->payment->amount_paid += $this->response->getAmountPaid();
        }
        $this->payment->status_message = $this->response->getMessage();
        $this->payment->save();
    }

    public function fireEvents(): void
    {
        if ($this->newStatus->equals($this->oldStatus)) {
            return;
        }

        $events = $this->eventMappingRules
            ->ifCurrentStatusIs($this->response->getStatus())
            ->andOldStatusIs($this->oldStatus)
            ->thenFireEvents();

        collect($events)
            ->filter(fn (string $eventClass) => is_a($eventClass, PaymentEvent::class, true))
            ->each(fn ($event) => Event::dispatch(new $event($this->payment)));
    }
}
