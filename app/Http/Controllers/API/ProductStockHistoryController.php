<?php

namespace App\Http\Controllers\API;

use App\Models\ProductStockHistory;
use Illuminate\Http\JsonResponse;

class ProductStockHistoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index()
    {
        $stockHistories = ProductStockHistory::with('product')->get();
        return $this->sendResponse($stockHistories->toArray(), 'Stock histories retrieved successfully.');
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $stockHistories = ProductStockHistory::where('product_id', $id)->with('product')->get();
        return $this->sendResponse($stockHistories->toArray(), 'Stock histories retrieved successfully.');
    }
}
