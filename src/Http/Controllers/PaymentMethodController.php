<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethodController class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Contracts\Requests\CreatePaymentMethod;
use Vanilo\Payment\Contracts\PaymentMethod;
use Vanilo\Payment\Models\PaymentMethodProxy;
use Vanilo\Payment\PaymentGateways;

class PaymentMethodController extends BaseController
{
    public function index()
    {
        return view('vanilo::payment-method.index', [
            'paymentMethods' => PaymentMethodProxy::all()
        ]);
    }

    public function create()
    {
        return view('vanilo::payment-method.create', [
            'paymentMethod' => app(PaymentMethod::class),
            'gateways' => PaymentGateways::choices(),
        ]);
    }

    public function store(CreatePaymentMethod $request)
    {
        try {
            $guardedAttributes = app(PaymentMethodProxy::modelClass())->getGuarded();
            $paymentMethod = PaymentMethodProxy::create($request->except($guardedAttributes));
            flash()->success(__(':name has been created', ['name' => $paymentMethod->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.payment-method.index'));
    }

    public function show(PaymentMethod $paymentMethod)
    {dd($paymentMethod);
        return view('vanilo::payment-method.show', ['paymentMethod' => $paymentMethod]);
    }
}
