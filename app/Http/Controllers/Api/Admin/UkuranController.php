<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUkuranRequest;
use App\Http\Requests\UpdateUkuranRequest;
use App\Models\Ukuran;
use Exception;
use Illuminate\Http\Request;

class UkuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = [
            'success' => false,
            'message' => '',
            'data' => null,
        ];
    
        try {
            // Ambil semua kategori dengan produk terkait
            $ukuran = Ukuran::with('product')->get();
    
            // Menambahkan full URL untuk gambar produk
            $ukuran->each(function ($ukuran) {
                $ukuran->product->each(function ($product) {
                    $product->image_url = url('storage/' . $product->image);
                });
            });
    
            $response['message'] = 'Berhasil Mengambil Data Ukuran';
            $response['success'] = true;
            $response['data'] = [
                'ukuran' => $ukuran,
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
    
        return response()->json($response);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUkuranRequest $request)
    {
        $response = $this->default_response;
        try {
            $data = $request->validated();

            $ukuran = new Ukuran();
            $ukuran->ukuran = $data['ukuran'];
            $ukuran->save();

            $response['message'] = 'Ukuran berhasil dibuat';
            $response['success'] = true;
            $response['data'] = [
                'ukuran' => $ukuran,
            ];

            $response['message'] = 'Ukuran berhasil dibuat';
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
        $response = $this->default_response;

        try {
            $ukuran = Ukuran::find($id);

            $response['success'] = true;
            $response['message'] = "Sukses Mengambil Data Ukuran";
            $response['data'] = [
                'ukuran' => $ukuran,
            ];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ukuran $ukuran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUkuranRequest $request, String $id)
    {
        $response = $this->default_response;

        try {
            $data = $request->validated();

            $ukuran = Ukuran::find($id);
            $ukuran->ukuran = $data['ukuran'];
            $ukuran->save();

            $response['success'] = true;
            $response['data'] = [
                'ukuran' => $ukuran,
            ];
            $response['message'] = 'Sukses Mengupdate Data Ukuran';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $response = $this->default_response;

        try {
            $ukuran = Ukuran::find($id);

            if (!$ukuran) {
                throw new Exception('Ukuran tidak tersedia');
            }

            $ukuran->delete();

            $response['success'] = true;
            $response['message'] = 'Sukses Menghapus Data Ukuran';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
}