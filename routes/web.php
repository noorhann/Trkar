<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin/login', ['as' => 'admin.login-form', 'uses' => 'App\Http\Controllers\System\AuthController@loginForm']);
Route::post('admin/login', ['as' => 'admin.login', 'uses' => 'App\Http\Controllers\System\AuthController@login']);
Route::get('admin/logout', ['as' => 'admin.logout', 'uses' => 'App\Http\Controllers\System\AuthController@logout']);
Route::group(['prefix'     => 'admin', 'middleware' => ['isadmin']], function () {
    Route::get('', function () {
        return view('dashboard');
    })->name('admin.dashboard');

    Route::resource('category', App\Http\Controllers\System\CategoryController::class, ['as' => 'admin']);
    Route::resource('car-made', App\Http\Controllers\System\CarMadeController::class, ['as' => 'admin']);
    Route::resource('car-model', App\Http\Controllers\System\CarModelController::class, ['as' => 'admin']);
    Route::resource('car-engine', App\Http\Controllers\System\CarEngineController::class, ['as' => 'admin']);
    Route::resource('manufacturer', App\Http\Controllers\System\ManufacturerController::class, ['as' => 'admin']);
    Route::resource('year', App\Http\Controllers\System\YearController::class, ['as' => 'admin']);
    Route::resource('country', App\Http\Controllers\System\CountryController::class, ['as' => 'admin']);
    Route::resource('original-country', App\Http\Controllers\System\OriginalCountryController::class, ['as' => 'admin']);
    Route::resource('product', App\Http\Controllers\System\ProductController::class, ['as' => 'admin']);
    Route::resource('user', App\Http\Controllers\System\UserController::class, ['as' => 'admin']);
    Route::resource('permission-group', App\Http\Controllers\System\PermissionGroupController::class, ['as' => 'admin']);
    Route::resource('admins', App\Http\Controllers\System\AdminController::class, ['as' => 'admin']);
    Route::resource('store-type', App\Http\Controllers\System\StoreTypeController::class, ['as' => 'admin']);
    Route::resource('store', App\Http\Controllers\System\StoreController::class, ['as' => 'admin']);
    Route::resource('store-branch', App\Http\Controllers\System\StoreBranchController::class, ['as' => 'admin']);
    Route::resource('vendor-reject', App\Http\Controllers\System\VendorRejectController::class, ['as' => 'admin']);
    Route::resource('activity-log', App\Http\Controllers\System\ActivityLogController::class, ['as' => 'admin']);
    Route::resource('vendor-staff', App\Http\Controllers\System\VendorStaffController::class, ['as' => 'admin']);
    Route::resource('store-vendor-staff', App\Http\Controllers\System\StoreVendorStaffController::class, ['as' => 'admin']);
    Route::resource('store-audit-log', App\Http\Controllers\System\StoreAuditLogController::class, ['as' => 'admin']);
    Route::resource('store-reject-status', App\Http\Controllers\System\StoreRejectStatusController::class, ['as' => 'admin']);
    Route::resource('vendor', App\Http\Controllers\System\VendorController::class, ['as' => 'admin']);
    Route::resource('attribute', App\Http\Controllers\System\AttributeController::class, ['as' => 'admin']);
    Route::resource('tyre-type', App\Http\Controllers\System\TyreTypeController::class, ['as' => 'admin']);
    Route::resource('attribute-tyre', App\Http\Controllers\System\AttributeTyreController::class, ['as' => 'admin']);
    Route::resource('payment-method', App\Http\Controllers\System\PaymentMethodController::class, ['as' => 'admin']);
    Route::resource('shipping-company', App\Http\Controllers\System\ShippingCompanyController::class, ['as' => 'admin']);
    Route::resource('order', App\Http\Controllers\System\OrderController::class, ['as' => 'admin']);
    Route::resource('wholesale-order', App\Http\Controllers\System\WholesaleOrderController::class, ['as' => 'admin']);
    Route::resource('order-status', App\Http\Controllers\System\OrderStatusController::class, ['as' => 'admin']);
    Route::resource('attribute-oil', App\Http\Controllers\System\AttributeOilController::class, ['as' => 'admin']);
    Route::resource('city', App\Http\Controllers\System\CityController::class, ['as' => 'admin']);
    Route::resource('area', App\Http\Controllers\System\AreaController::class, ['as' => 'admin']);
    Route::resource('report', App\Http\Controllers\System\SalesReportController::class, ['as' => 'admin']);


    Route::get('getCategories/search', ['as' => 'admin.getCategories', 'uses' => ' App\Http\Controllers\System\CategoryController@getCategories']);
    Route::post('getChilds/{id}', ['as' => 'admin.getChilds', 'uses' => 'App\Http\Controllers\System\CategoryController@getChilds']);
    Route::post('getcategoryByid/{id}', ['as' => 'admin.getcategoryByid', 'uses' => 'App\Http\Controllers\System\CategoryController@getcategoryByid']);
    Route::post('getCity/{id}', ['as' => 'admin.getCity', 'uses' => 'App\Http\Controllers\System\UserController@getCity']);
    Route::post('getArea/{id}', ['as' => 'admin.getArea', 'uses' => 'App\Http\Controllers\System\UserController@getArea']);
    Route::post('searchAttributeWidth', ['as' => 'admin.searchAttributeWidth', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeWidth']);
    Route::post('searchAttributeHight', ['as' => 'admin.searchAttributeHight', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeHight']);
    Route::post('searchAttributeDiameter', ['as' => 'admin.searchAttributeDiameter', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeDiameter']);
    Route::post('searchAttributeManufacturer', ['as' => 'admin.searchAttributeManufacturer', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeManufacturer']);
    Route::post('searchAttributeSpeadRating', ['as' => 'admin.searchAttributeSpeadRating', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeSpeadRating']);
    Route::post('searchAttributeAlex', ['as' => 'admin.searchAttributeAlex', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeAlex']);
    Route::post('searchAttributeLoad', ['as' => 'admin.searchAttributeLoad', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@searchAttributeLoad']);
    Route::post('product/approved/{product}', ['as' => 'admin.product.approved', 'uses' => 'App\Http\Controllers\System\ProductController@approved']);
    Route::post('vendor/approvedBlock/{vendor}', ['as' => 'admin.vendor.approvedBlock', 'uses' => 'App\Http\Controllers\System\VendorController@approvedBlock']);
    Route::post('vendor-staff/approvedVendorStaff/{vendorStaff}', ['as' => 'admin.vendor-staff.approvedVendorStaff', 'uses' => 'App\Http\Controllers\System\VendorStaffController@approvedVendorStaff']);
    // Route::post('store-vendor-staff/approvedVendorStaff/{storeVendorStaff}', ['as' => 'admin.store-vendor-staff.approvedStoreVendorStaff', 'uses' => 'App\Http\Controllers\System\StoreVendorStaffController@approvedStoreVendorStaff']);
    Route::post('vendor/approved/{vendor}', ['as' => 'admin.vendor.approved', 'uses' => 'App\Http\Controllers\System\VendorController@approved']);
    Route::post('vendor/branch/{storeBranch}', ['as' => 'admin.branch.approved', 'uses' => 'App\Http\Controllers\System\StoreController@approved']);
    Route::post('store/type/permission', ['as' => 'store.type.permission', 'uses' => 'App\Http\Controllers\System\StoreController@typePermission']);
   // Approved
    Route::post('store-reject/approved/{storeRejectStatus}', ['as' => 'admin.store-reject.approved', 'uses' => 'App\Http\Controllers\System\StoreRejectStatusController@approvedStoreRejectStatus']);
    Route::post('category/approved/{category}', ['as' => 'admin.category.approved', 'uses' => 'App\Http\Controllers\System\CategoryController@approvedStatus']);
    Route::post('car-made/approved/{carMade}', ['as' => 'admin.car-made.approved', 'uses' => 'App\Http\Controllers\System\CarMadeController@approvedStatus']);
    Route::post('car-model/approved/{carModel}', ['as' => 'admin.car-model.approved', 'uses' => 'App\Http\Controllers\System\CarModelController@approvedStatus']);
    Route::post('car-engine/approved/{carEngine}', ['as' => 'admin.car-engine.approved', 'uses' => 'App\Http\Controllers\System\CarEngineController@approvedStatus']);
    Route::post('year/approved/{year}', ['as' => 'admin.year.approved', 'uses' => 'App\Http\Controllers\System\YearController@approvedStatus']);
    Route::post('manufacturer/approved/{manufacturer}', ['as' => 'admin.manufacturer.approved', 'uses' => 'App\Http\Controllers\System\ManufacturerController@approvedStatus']);
    Route::post('original-country/approved/{originalCountry}', ['as' => 'admin.original-country.approved', 'uses' => 'App\Http\Controllers\System\OriginalCountryController@approvedStatus']);
    Route::post('attribute/approved/{attribute}', ['as' => 'admin.attribute.approved', 'uses' => 'App\Http\Controllers\System\AttributeController@approvedStatus']);
    Route::post('attribute-tyre/approved/{attributeTyre}', ['as' => 'admin.attribute-tyre.approved', 'uses' => 'App\Http\Controllers\System\AttributeTyreController@approvedStatus']);
    Route::post('attribute-oil/approved/{attributeOil}', ['as' => 'admin.attribute-oil.approved', 'uses' => 'App\Http\Controllers\System\AttributeOilController@approvedStatus']);
    Route::post('product/approved/{product}', ['as' => 'admin.product.approved', 'uses' => 'App\Http\Controllers\System\ProductController@approvedStatus']);
    Route::post('store/approved/{store}', ['as' => 'admin.store.approved', 'uses' => 'App\Http\Controllers\System\StoreController@approvedStatus']);
    Route::post('store-branch/approved/{storeBranch}', ['as' => 'admin.store-branch.approved', 'uses' => 'App\Http\Controllers\System\StoreBranchController@approvedStatus']);
    Route::post('store-reject-status/approved/{storeRejectStatus}', ['as' => 'admin.store-reject-status.approved', 'uses' => 'App\Http\Controllers\System\StoreRejectStatusController@approvedStatus']);
    Route::post('area/approved/{area}', ['as' => 'admin.area.approved', 'uses' => 'App\Http\Controllers\System\AreaController@approvedStatus']);
    Route::post('city/approved/{city}', ['as' => 'admin.city.approved', 'uses' => 'App\Http\Controllers\System\CityController@approvedStatus']);
    Route::post('country/approved/{country}', ['as' => 'admin.country.approved', 'uses' => 'App\Http\Controllers\System\CountryController@approvedStatus']);
    Route::post('store-vendor-staff/approved/{storevendorstaff}', ['as' => 'admin.store-vendor-staff.approved', 'uses' => 'App\Http\Controllers\System\StoreVendorStaffController@approvedStatus']);


    Route::post('attribute-oil/searchAttributeManufacturer', ['as' => 'admin.attribute-oil.searchAttributeManufacturer', 'uses' => 'App\Http\Controllers\System\AttributeOilController@searchAttributeManufacturer']);
    Route::post('attribute-oil/searchAttributeSae', ['as' => 'admin.attribute-oil.searchAttributeSae', 'uses' => 'App\Http\Controllers\System\AttributeOilController@searchAttributeSae']);
    Route::post('attribute-oil/searchAttributeOem', ['as' => 'admin.attribute-oil.searchAttributeOem', 'uses' => 'App\Http\Controllers\System\AttributeOilController@searchAttributeOem']);
    Route::post('attribute-oil/searchAttributeSpecification', ['as' => 'admin.attribute-oil.searchAttributeSpecification', 'uses' => 'App\Http\Controllers\System\AttributeOilController@searchAttributeSpecification']);

});


Route::get('', function () {
    return view('site.home');
})->name('');
Route::get('/{vue_capture?}', function () {
    return view('site/home');
})->where('vue_capture', '[\/\w\.-]*');