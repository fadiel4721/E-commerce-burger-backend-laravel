<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = $this->default_response;

        $checkout = Checkout::where('id_customer', $request->user()->id)
            ->with('keranjang.product')
            ->get();
        $response['message'] = 'Berhasil Mengambil Data Checkout';
        $response['success'] = true;
        $response['data'] = [
            'checkout' => $checkout,
        ];
        return response()->json($response);
    }

    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $response = $this->default_response;
        // dd($request->all());
        //validasi input
        $request->validate([
            'id_keranjang' => 'required|exists:keranjang,id_keranjang',
            'metode_bayar' => 'required|in:Transfer,M-Banking,DANA,Gopay,QRIS',
            'metode_kirim' => 'required|in:COD,JNE,SICEPAT,JNT,POS,WAHANA',
            'alamat' => 'required',
            'id_customer'=> 'required|exists:users,id',
        ]);
        $biaya_kirim = [
            'COD'=> 0,
            'JNE'=> 25000,
            'SICEPAT'=> 30000,
            'JNT'=> 15000,
            'POS'=> 40000,
            'WAHANA'=> 35000
        ];
        DB::beginTransaction();
        //get data keranjang
        $keranjangsQuery = Keranjang::where('id_customer', auth()->user()->id)
            ->whereNull('id_checkout')
            ->whereIn('id_keranjang', $request->id_keranjang)
            ->with('product');

            $keranjangs = $keranjangsQuery->get();
        //validasi stock
    if ($keranjangs->count() == 0) {
        $response['success'] = false;
        $response['message'] = 'Stok Habis';
        return response()->json($response, 404);
    }
    //validasi stok
    //hitung total harga produk
    $total_pembayaran = 0;
        foreach ($keranjangs as $keranjang)
        {
            if($keranjang->product->stock < $keranjang->qty){
                $response['success'] = false;
                $response['message'] = 'Stok produk tidak cukup, yang dipesan' . ($keranjang->qty) . ', stok tersedia:'.($keranjang->product->stock). '.(' . $keranjang->product->name . ')';
                return response()->json($response, 404);
            }
            //total_pembayaran = $total_harga_product + $keranjang->total_harga;
            $total_pembayaran += $keranjang->total_harga;
        }
        //kurangin stok yang ada di product
        foreach ($keranjangs as $keranjang)
        {
            // $keranjang->product->stock -= $keranjang->qty;
            // $keranjang->product->save();
            $keranjang->product ->decrement('stock', $keranjang->qty);
        }

        //simpan data checkout
        $chekout = new Checkout();
        $chekout->id_customer = auth()->user()->id;
        $chekout->total_pembayaran = $total_pembayaran;
        $chekout->biaya_kirim = $biaya_kirim [$request->metode_kirim];
        $chekout->metode_bayar = $request->metode_bayar;
        $chekout->metode_kirim = $request->metode_kirim;
        $chekout->alamat = $request->alamat;
        $chekout->save();

        //update data keranjang
        $keranjangsQuery->update([
            'id_checkout' => $chekout->id_checkout
        ]);
        
        DB::commit();
        $response['success'] = true;
        $response['data']= $chekout;
        $response['message'] = 'Pesanan berhasil di checkout';
        return response()->json($response, 200);
        // dd($keranjangs->toArray());
        //hitung total harga product
        
        //kurangin stok yang ada di produk

        //simpan data checkout
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
