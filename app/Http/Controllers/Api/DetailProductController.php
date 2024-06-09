<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $detailProducts = DetailProduct::with('product', 'category', 'ukuran')->get();

        // Menambahkan full URL untuk gambar produk
        $detailProducts->each(function ($detailProduct) {
            $detailProduct->product->image_url = url('storage/' . $detailProduct->product->image);
        });

        return response()->json([
            'success' => true,
            'data' => $detailProducts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(String $id)
    {
        $detailProduct = DetailProduct::with('product', 'category', 'ukuran')->find($id);

        if (!$detailProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Product not found'
            ], 404);
        }

        // Menambahkan full URL untuk gambar produk
        $detailProduct->product->image_url = url('storage/' . $detailProduct->product->image);

        return response()->json([
            'success' => true,
            'data' => $detailProduct,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_product' => 'required|exists:products,id_product',
            'id_category' => 'required|exists:categories,id_category',
            'id_ukuran' => 'required|exists:ukuran,id_ukuran',
          
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $detailProduct = new DetailProduct([
            'id_product' => $request->id_product,
            'id_category' => $request->id_category,
            'id_ukuran' => $request->id_ukuran,
           
        ]);

        $detailProduct->save();

        return response()->json([
            'success' => true,
            'data' => $detailProduct,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $detailProduct = DetailProduct::find($id);

        if (!$detailProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_product' => 'sometimes|required|exists:products,id_product',
            'id_category' => 'sometimes|required|exists:categories,id_category',
            'id_ukuran' => 'sometimes|required|exists:ukuran,id_ukuran',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $detailProduct->fill($request->all());
        $detailProduct->save();

        return response()->json([
            'success' => true,
            'data' => $detailProduct,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(String $id)
    {
        $detailProduct = DetailProduct::find($id);

        if (!$detailProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Detail Product not found'
            ], 404);
        }

        $detailProduct->delete();

        return response()->json([
            'success' => true,
            'message' => 'Detail Product deleted successfully',
        ]);
    }
}
