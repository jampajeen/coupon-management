<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*
 * Home Controller
 */
Route::get('/', 'HomeController@getIndex');

/*
 * Dashboard Controller
 */
Route::get('dashboard', 'DashboardController@getIndex');

/*
 * Coupon Controller
 */
Route::get('coupon', 'CouponController@getIndex');
Route::get('coupon/addcoupon', 'CouponController@getAddCoupon');
Route::get('coupon/{id}', 'CouponController@getCouponItem');
Route::get('coupon/{id}/edit', 'CouponController@getCouponItemEdit');
Route::get('coupon/{id}/delete', 'CouponController@getDeleteCoupon');

Route::post('coupon/addcoupon', array('uses' => 'CouponController@postAddCoupon'));
Route::post('coupon/{id}/edit', array('uses' => 'CouponController@postCouponItemEdit'));
/*
 * SHop Controller
 */
Route::get('shop', 'ShopController@getIndex');
Route::get('shop/addshop', 'ShopController@getAddShop');
Route::get('shop/{id}', 'ShopController@getShopItem');
Route::get('shop/{id}/edit', 'ShopController@getShopItemEdit');
Route::get('shop/{id}/delete', 'ShopController@getDeleteShop');
Route::get('shop/overview/{id}', 'ShopController@getOverview');

Route::post('shop/addshop', array('uses' => 'ShopController@postAddShop'));
Route::post('shop/{id}/edit', array('uses' => 'ShopController@postShopItemEdit'));

Route::post('api/shop/preview/{id}', array( 'uses' => 'ShopController@postPreview'));

/*
 * Client Controller
 */
//Route::get('clients', 'ClientController@getIndex');
Route::get('clients/account', 'ClientController@getAccount');
Route::get('clients/login', 'ClientController@getLogin');
Route::get('clients/register', 'ClientController@getRegister');
//Route::get('clients/register', array('as' => 'register_with_error', 'uses' => 'ClientController@getRegisterWithError'));
Route::get('clients/logout', 'ClientController@getLogout');
Route::get('clients/changepwd', 'ClientController@getChangePwd');
Route::get('clients/editaccount', 'ClientController@getEditAccount');
Route::get('clients/forgotpwd', 'ClientController@getForgotPwd');

Route::post('clients/login', array('uses' => 'ClientController@postLogin'));
Route::post('clients/register', array('uses' => 'ClientController@postRegister'));
Route::post('clients/editaccount', array('uses' => 'ClientController@postEditAccount'));
Route::post('clients/changepwd', array('uses' => 'ClientController@postChangePwd'));
Route::post('clients/forgotpwd', array('uses' => 'ClientController@postForgotPwd'));

/*
 * Resources route
 */
Route::get('resources/upload/images/{id}', 'ResourceController@getImageUrl');
Route::get('resources/url/{id}', 'ResourceController@getRealUrl');
Route::get('resources','ResourceController@getIndex');
Route::get('resources/{id}/delete', 'ResourceController@getDeleteResource');

Route::post('resources/upload', array('uses' => 'ResourceController@postUpload'));



/*
 * Admin route
 */
Route::get('admin', 'AdminController@getAdminLogin');
Route::get('admin/logout', 'AdminController@getAdminLogout');
Route::get('admin/dashboard', 'AdminController@getAdminDashboard');

Route::get('admin/clients', 'AdminController@getAdminClient');
Route::get('admin/clients/{id}/edit', 'AdminController@getAdminEditClientAccount');
Route::get('admin/clients/{id}/delete', 'AdminController@getAdminClientAccountDelete');
Route::get('admin/clients/{id}/poi', 'AdminController@getAdminClientPoi');
Route::get('admin/clients/{id}/coupon', 'AdminController@getAdminClientCoupon');

Route::get('admin/users', 'AdminController@getAdminUser');
Route::get('admin/users/{id}/edit', 'AdminController@getAdminUserEdit');
Route::get('admin/users/{id}/delete', 'AdminController@getAdminUserDelete'); 

Route::get('admin/poi', 'AdminController@getAdminPoi');
Route::get('admin/poi/{id}/edit', 'AdminController@getAdminShopItemEdit');
Route::get('admin/poi/{id}/delete', 'AdminController@getAdminDeleteShop');


Route::get('admin/coupon', 'AdminController@getAdminCoupon');
Route::get('admin/resource', 'AdminController@getAdminResource');

Route::get('admin/categories', 'AdminController@getAdminCategories');
Route::get('admin/categories/addcat', 'AdminController@getAdminCategoriesAdd');
Route::get('admin/categories/{id}/edit', 'AdminController@getAdminCategoriesEdit');
Route::get('admin/categories/{id}/delete', 'AdminController@getAdminCategoriesDelete');


Route::post('admin', array('uses' => 'AdminController@postAdminLogin'));

Route::post('admin/clients/{id}/edit', 'AdminController@postAdminEditClientAccount');

Route::post('admin/users/{id}/edit', 'AdminController@postAdminUserEdit'); 

Route::post('admin/poi/{id}/edit', 'AdminController@postAdminShopItemEdit');


Route::post('admin/categories/{id}/edit', 'AdminController@postAdminCategoriesEdit');
Route::post('admin/categories/addcat', 'AdminController@postAdminCategoriesAdd');


/*
 * Clients
 */
Route::post('api/users/login', 'UserController@APIpostLogin'); 
Route::post('api/users/register', 'UserController@APIpostRegister'); 


/*
 * Social networking
 */

Route::get('api/social/tw/{hashtag}', 'SocialController@APIgetTwitterContentByHashTag');
Route::get('api/social/ig/{hashtag}', 'SocialController@APIgetInstagramContentByHashTag');
Route::get('api/social/igcallback','SocialController@APIgetInstagramCallback'); 