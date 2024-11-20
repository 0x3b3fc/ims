<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->name}%");
        }

        if ($request->filled('sku')) {
            $query->where('sku', $request->sku);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();
        return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'category_id' => 'required',
        ]);
        $data['sku'] = Str::uuid();
        $product = Product::create($data);
        return $this->sendResponse($product->load('category')->toArray(), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = Product::find($id)->load('category');
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        return $this->sendResponse($product->toArray(), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'category_id' => 'required',
        ]);
        $product->update($data);
        return $this->sendResponse($product->load('category')->toArray(), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse([], 'Product deleted successfully.');
    }

    /**
     * Search for a name.
     * @param $name
     * @return JsonResponse
     */
    public function search($name)
    {
        $products = Product::where('name', 'like', "%$name%")->get();
        return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
    }

}
