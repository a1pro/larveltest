<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ProductModels;

class UserApiController extends Controller
{
    // add money wallet api
    public function addmoneywallet(Request $request){ 
       
        $validator = Validator::make($request->all(), [
             'email'      => 'required',
             'add_money'  => 'required',
        ]);
 
        if($validator->fails()){
                 return response()->json($validator->errors()->toJson(), 400);
        }
        
        
        if($request->add_money >= 3 && $request->add_money <= 100){
            
            $userexit = User::where('email',$request->email)->select('id')->count();
            if($userexit > 0){
                $exitmoney = User::where('email',$request->email)->select('id','name','wallet')->first();
                if($exitmoney->wallet > 0){
                    $total = $exitmoney->wallet +  $request->add_money;
                    
                    User::where("email",$request->email)->update(['wallet' => number_format($total, 2)]);    
                    return [
                         "code"=>201,
                         "status"=>"success",
                         "message"=>"add your money $".number_format($request->add_money, 2) .", total in wallet $".number_format($total, 2),
                    ];
                }else{
                   User::where("email",$request->email)->update(['wallet' =>number_format($request->add_money, 2)]);    
                    return [
                             "code"=>201,
                             "status"=>"success",
                             "message"=>"add your money $".number_format($request->add_money, 2).", total in wallet $".number_format($request->add_money, 2),
                        ]; 
                    
                }
            }else{
                
                return [
                         "code"=>200,
                         "status"=>"failed",
                         "message" =>"Your email id is not correct",
                    ]; 
            }
            
        }else{
            
            return [
                     "code"=>200,
                     "status"=>"failed",
                     "message" =>"add a minimum of $3.00 and a maximum of $100.00",
                ]; 
        }
        
    }
    
    //buy product api
    public function userbuyproduct(Request $request){
        $validator = Validator::make($request->all(),[
             'product_id'      => 'required',
             'email'  => 'required',
        ]);
 
        if($validator->fails()){
                 return response()->json($validator->errors()->toJson(), 400);
        }
        $product_id = ProductModels::where('id',$request->product_id)->count();
        $user_id = User::where('email',$request->email)->count();
        
        if($product_id > 0 && $user_id > 0){
            $product = ProductModels::where('id',$request->product_id)->select('id','product_name','price')->first();
            $user = User::where('email',$request->email)->select('id','name','wallet')->first();
            $total_wallet =  $user->wallet;
            $charge_amount = $product->price;
            
            if($total_wallet >= $charge_amount ){
                
                $left_total = $total_wallet - $charge_amount;
                
                User::where("email",$request->email)->update(['wallet' =>number_format($left_total, 2)]);
                
                return  [
                         "code"=>201,
                         "status"=>"success",
                         "message"=>"Your product Price $".number_format($charge_amount, 2).",Left Amount in wallet $".number_format($left_total, 2),
                        ]; 
            }else{
                return [
                 "code"=>200,
                 "status"=>"failed",
                 "message" =>"In your account balance not sufficient",
                ]; 
            }
            
        }else{
            
            return [
                 "code"=>200,
                 "status"=>"failed",
                 "message" =>"Your order not create",
                ];    
            
            
        }
        
        
        
    }
}
