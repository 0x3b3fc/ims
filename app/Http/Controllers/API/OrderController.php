<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStockHistory;
use App\Models\StockHistory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $orders = Order::with('items.product')->get();
        return $this->sendResponse($orders->toArray(), 'Orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            $totalAmount = 0;
            DB::beginTransaction();

            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->qty < $item['qty']) {
                    throw new Exception('Insufficient stock for product: ' . $product->name, 400);
                }

                $totalAmount += $product->price * $item['qty'];

                // Deduct stock
                $product->qty -= $item['qty'];
                $product->save();

                // Log stock history
                ProductStockHistory::query()->create([
                    'product_id' => $product->id,
                    'change_type' => 'remove',
                    'quantity' => $item['qty'],
                ]);

            }

            $order = Order::create([
                "order_no" => 'Order-' . time() . '-' . rand(1000, 9999),
                'user_id' => $request->user_id,
                'order_date' => now(),
                'status' => 'pending',
                'total_amount' => $totalAmount,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => Product::find($item['product_id'])->price,
                ]);
            }
            DB::commit();
            return $this->sendResponse($order->load('items.product')->toArray(), 'Order placed successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $order = Order::with('items.product')->find($id);
        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }
        return $this->sendResponse($order->toArray(), 'Order retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $order->update([
            'status' => $validated['status'],
        ]);
        return $this->sendResponse($order->load('items.product')->toArray(), 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return JsonResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return $this->sendResponse([], 'Order deleted successfully.');
    }

    /**
     * Generate sales reports.
     * @param Request $request
     * @return JsonResponse
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Validate the date range if provided
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Total sales for the given time period or all time if no dates provided
        $totalSales = Order::with('items.product')->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('order_date', [$startDate, $endDate]);
        })->sum('total_amount');

        // Top-selling products
        $topSellingProducts = OrderItem::with('product')->select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('orders.order_date', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->with('product')
            ->get();

        return $this->sendResponse([
            'total_sales' => $totalSales,
            'top_selling_products' => $topSellingProducts,
        ], 'Sales report generated successfully.');
    }
}
