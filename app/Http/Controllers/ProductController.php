<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
        ]);

        // get current user id
        $userId = $request->user()->id;
        $product = $request->all();
        if ($userId) {
            $product = array_merge($request->all(), ['user_id' => $userId]);
        }

        return Product::create($product);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if ($product->user_id == $request->user()->id) {
            // update
            $product->update($request->all());

            return $product;
        }

        return response([
            'message' => 'forbidden to update this product',
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product->user_id == request()->user()->id) {
            // delete product
            return $product->delete();
        }

        return response([
            'message' => 'forbidden to delete this product',
        ], 403);
    }

    /**
     * Search for a name.
     *
     * @param string $name
     *
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Product::where('name', 'like', '%'.$name.'%')->get();
    }
}
