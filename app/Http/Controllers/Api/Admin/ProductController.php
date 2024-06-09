<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Ukuran;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->default_response;

        try {
            $products = Product::all();

            $response['success'] = true;
            $response['message'] = 'Berhasil Mengambil Data Products';
            $response['data'] = [
                'products' => $products,
            ];
        } catch (Exception $e) {
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
    public function store(StoreProductRequest $request)
    {
        $response = $this->default_response;
    
        try {
            $data = $request->validated();
    
            // Penyimpanan gambar
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->storeAs('project-images', $file->hashName(), 'public');
            }
    
            // Periksa apakah id_category valid
            if (!Category::where('id_category', $data['id_category'])->exists()) {
                throw new Exception("ID category tidak valid.");
            }
    
            // Periksa apakah id_ukuran valid
            if (!Ukuran::where('id_ukuran', $data['id_ukuran'])->exists()) {
                throw new Exception("ID ukuran tidak valid.");
            }
    
            // Membuat produk baru
            $product = new Product();
            $product->nama_product = $data['nama_product'];
            $product->description = $data['description'];
            $product->stock = $data['stock'];
            $product->price = $data['price'];
            $product->image = $path ?? null;
            $product->id_category = $data['id_category'];
            $product->id_ukuran = $data['id_ukuran'];
            $product->save();
    
            // Menyiapkan respon dengan relasi category dan ukuran
            $product->load('category', 'ukuran');
    
            $response['message'] = 'Produk berhasil dibuat';
            $response['success'] = true;
            $response['data'] = [
                'product' => $product
            ];
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
            $product = Product::with(['category', 'ukuran'])->find($id);

            $response['success'] = true;
            $response['message'] = 'Berhasil Menampilkan Data Product';
            $response['data'] = [
                'product' => $product,
                // 'product' => $product->with('category')->find($product->id),
            ];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, String $id)
    {
        $response = $this->default_response;

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->storeAs('project-images', $file->hashName(), 'public');
            }

            $product = Product::find($id);
            $product->nama_product = $data['nama_product'];
            $product->description = $data['description'];
            $product->stock = $data['stock'];
            $product->price = $data['price'];
            if ($request->hasFile('image')) {
                if ($product->image) Storage::disk('public')->delete($product->image);
                $product->image = $path ?? null;
            }
            $product->load('category', 'ukuran');
            $product->save();

            $response['success'] = true;
            $response['message'] = 'Product Berhasil Diupdate';
            $response['data'] = [
                'product' => $product->with('category')->find($product->id_product),
                'product' => $product->with('ukuran')->find($product->id_product),
            ];
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
        Log::info('Attempting to delete product with ID: ' . $id); // Log ID produk
    
        $response = $this->default_response;
    
        try {
            // Cek apakah produk ada dengan ID yang diberikan
            $product = Product::find($id);
    
            Log::info('Product found: ' . ($product ? 'Yes' : 'No')); // Log apakah produk ditemukan
    
            if (!$product) {
                throw new Exception('Product not found');
            }
    
            // Jika produk memiliki gambar, hapus gambar dari penyimpanan
            if ($product->image) {
                Log::info('Deleting product image: ' . $product->image); // Log gambar yang dihapus
                Storage::disk('public')->delete($product->image);
            }
    
            // Hapus produk dari database
            $product->delete();
    
            // Set respons sukses
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully';
        } catch (Exception $e) {
            // Set respons jika ada kesalahan
            $response['message'] = $e->getMessage();
            Log::error('Error deleting product: ' . $e->getMessage()); // Log kesalahan
        }
    
        // Kembalikan respons JSON
        return response()->json($response);
    }
    
}
