<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
        
        $product = Product::all();
        return response([ 'product' => 
            ProductResource::collection($product), 
            'message' => 'Successful'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     $data = $request->all();

     $validator = Validator::make($data, [
        'name' => 'required|max:50',
        'age' => 'required|max:50',
        'job' => 'required|max:50',
        'salary' => 'required|max:50'
    ]);

     if($validator->fails()){
        return response(['error' => $validator->errors(), 
            'Validation Error']);
    }

    $product = Product::create($data);

    return response([ 'product' => new 
        ProductResource($product), 
        'message' => 'Success'], 200);
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
       return response([ 'product' => new 
        ProductResource($product), 'message' => 'Success'], 200);
   }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
       $product->update($request->all());

       return response([ 'product' => new 
        ProductResource($product), 'message' => 'Success'], 200);
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
       $product->delete();

       return response(['message' => 'Employee deleted']);
   }
}


?>