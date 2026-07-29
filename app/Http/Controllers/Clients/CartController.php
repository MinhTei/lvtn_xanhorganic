<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\ClientCart;
use App\Services\ClientWishlist;
use Illuminate\Http\Request;
use Validator;


class CartController extends Controller
{
    public function index()
    {
        $cartItems = ClientCart::items();
        $subtotal = ClientCart::subtotal($cartItems);

        return view('clients.pages.cart', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request){
        $validator = Validator($request->all(), [
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
        ],[
            //'quantity.min'=>'Số lượng tối thiểu là 1',
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'Lỗi dữ liệu đầu vào',
                'errors'=>$validator->errors()
            ],422);
        }
    
        $productId = (int) $request->product_id;
        $quantity = (int) $request->quantity;
        $result = ClientCart::add($productId, $quantity);

        return response()->json($result,$result['success']?200:422);

    }

    public function update (Request $request , int $productId){
        //validate
        $validator = Validator($request->all(),[
            'quantity'=>'required|min:1|integer',

        ],[
            'quantity.min'=>'Số lượng tối thiểu là 1!',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=> false,
                'message'=>'Lỗi dữ liệu',
                'errors'=>$validator->errors()
            ],422);
        }

        //giao việc
        $quantity =(int) $request->quantity;
        //$result = ClientCart::update($productId,$quantity);
        // $result = [
        //     'success'=> false,
        //     'message'=>' Đã tới Controller, ID là '. $productId . ' và số lượng là '. $quantity
        // ];
        $result = ClientCart::update($productId,$quantity);

        if(!$result['success']){
            return response()->json($result,422);
        }
        return $this->index();
    }

    public function destroy (int $productId){
        ClientCart::remove($productId);
        return $this -> index();
    }

}
