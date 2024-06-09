<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];

        try {
            $keranjang = Keranjang::with(['product', 'customer'])->get();

            $response['success'] = true;
            $response['message'] = 'Berhasil Mengambil Data Keranjang';
            $response['data'] = [
                'keranjang' => $keranjang,
            ];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];

        try {
            $data = $request->validate([
                'id_product' => 'required|exists:products,id_product',
                'id_customer' => 'required|exists:users,id',
                'qty' => 'required|integer|min:1',
            ]);

            // Mendapatkan harga satuan produk
            $product = Product::find($data['id_product']);
            $harga_satuan = $product->price;
            $total_harga = $data['qty'] * $harga_satuan;

            $keranjang = new Keranjang();
            $keranjang->id_product = $data['id_product'];
            $keranjang->id_customer = $data['id_customer'];
            $keranjang->qty = $data['qty'];
            $keranjang->harga_satuan = $harga_satuan;
            $keranjang->total_harga = $total_harga;
            $keranjang->save();

            $response['success'] = true;
            $response['message'] = 'Berhasil Menambahkan ke Keranjang';
            $response['data'] = $keranjang;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];

        try {
            $keranjang = Keranjang::with(['product', 'customer'])->find($id);

            if (!$keranjang) {
                throw new Exception('Keranjang tidak ditemukan');
            }

            $response['success'] = true;
            $response['message'] = 'Berhasil Menampilkan Data Keranjang';
            $response['data'] = $keranjang;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];

        try {
            $data = $request->validate([
                'qty' => 'required|integer|min:1',
            ]);

            $keranjang = Keranjang::find($id);

            if (!$keranjang) {
                throw new Exception('Keranjang tidak ditemukan');
            }

            // Update quantity and recalculate total price
            $keranjang->qty = $data['qty'];
            $keranjang->total_harga = $keranjang->qty * $keranjang->harga_satuan;
            $keranjang->save();

            $response['success'] = true;
            $response['message'] = 'Keranjang Berhasil Diupdate';
            $response['data'] = $keranjang;
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null
        ];

        try {
            $keranjang = Keranjang::find($id);

            if (!$keranjang) {
                throw new Exception('Keranjang tidak ditemukan');
            }

            $keranjang->delete();

            $response['success'] = true;
            $response['message'] = 'Keranjang Berhasil Dihapus';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }
}