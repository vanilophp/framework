<?php
/**
 * Contains the OrderController class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-17
 *
 */


namespace Vanilo\Framework\Http\Controllers;

use Konekt\AppShell\Http\Controllers\BaseController;
use Vanilo\Framework\Contracts\Requests\UpdateOrder;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Models\OrderProxy;

class OrderController extends BaseController
{
    public function index()
    {
        return view('vanilo::order.index', [
            'orders' => OrderProxy::all()
        ]);
    }

    public function show(Order $order)
    {
        return view('vanilo::order.show', ['order' => $order]);
    }

    public function update(Order $order, UpdateOrder $request)
    {
        try {
            $order->update($request->all());

            flash()->success(__('Order :no has been updated', ['no' => $order->number]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('vanilo.order.show', $order));
    }

    public function destroy(Order $order)
    {
        try {
            $number = $order->getNumber();
            $order->delete();

            flash()->warning(__('Order :no has been deleted', ['no' => $number]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('vanilo.order.index'));
    }

}
