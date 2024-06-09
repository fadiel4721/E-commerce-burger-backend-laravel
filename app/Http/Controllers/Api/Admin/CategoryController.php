<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
            $categories = Category::with('product')->get();
    
            // Menambahkan full URL untuk gambar produk
            $categories->each(function ($category) {
                $category->product->each(function ($product) {
                    $product->image_url = url('storage/' . $product->image);
                });
            });
    
            $response['message'] = 'Berhasil Mengambil Data Category';
            $response['success'] = true;
            $response['data'] = [
                'categories' => $categories,
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
    public function store(StoreCategoryRequest $request)
    {
        $response = $this->default_response;
        try {
            $data = $request->validated();

            $category = new Category();
            $category->nama_category = $data['nama_category'];
            
            $category->save();

            $response['success'] = true;
            $response['data'] = [
                'category' => $category,
            ];

            $response['message'] = 'Category berhasil dibuat';
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
            $category = Category::find($id);

            $response['success'] = true;
            $response['message'] = "Sukses Menampilkan Data Category";
            $response['data'] = [
                'category' => $category,
            ];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, String $id)
    {
        $response = $this->default_response;

        try {
            $data = $request->validated();

            $category = Category::find($id);
            $category->nama_category = $data['nama_category'];
            $category->save();

            $response['success'] = true;
            $response['data'] = [
                'category' => $category,
            ];
            $response['message'] = 'Sukses Mengupdate Data Category';
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
            $categories = Category::find($id);

            if (!$categories) {
                throw new Exception('Category Tidak Ditemukan');
            }

            $categories->delete();

            $response['success'] = true;
            $response['message'] = 'Sukses Menghapus Data Category';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
}
