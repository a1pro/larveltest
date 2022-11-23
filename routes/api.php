<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('addmoney', [UserApiController::class, 'addmoneywallet']);

Route::post('buyproduct', [UserApiController::class, 'userbuyproduct']);




/*
1.add money api

http://laraveltest.web.a1professionals.net/api/addmoney
email:rana@gmail.com
add_money:20.00

2.buy product api
http://laraveltest.web.a1professionals.net/api/buyproduct
product_id:1
email:rana@gmail.com

*/
