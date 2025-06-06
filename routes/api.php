<?php

use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\NewController;
use App\Http\Controllers\API\ZohoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::get('brands', 'BrandController@allBrands');
    Route::get('cars/latest', 'CarController@latest');
    Route::get('cars', 'CarController@all');
    Route::get('all_cars', 'CarController@all_cars');

    Route::get('car-details/{id}', 'CarController@show');

    Route::get('cars-basedOn-model', 'CarController@carsBasedOnModel');
    Route::get('models/{carModel}', 'CarController@modelCars');
    Route::get('news', 'NewController@all');
    Route::get('news/highlighted', 'NewController@highlighted');
    Route::get('news/{news}', 'NewController@singleNew');
    Route::get('offers', 'OfferController@all');
    Route::get('offers/{offer}', 'OfferController@singleOffer');
    Route::get('faqs', 'FaqController@all');
    Route::get('services', 'ServiceController@all');
    Route::get('general', 'GeneralController@index');
    Route::post('contact-us', 'ContactUsController@store');
    Route::get('branches', 'BranchController@all');
    Route::get('cities', 'CityController@all');
    Route::get('careers', 'CareerController@all');
    Route::post('careers/{career}/apply', 'CareerController@apply');
    Route::post('individuals-orders', 'OrderController@storeIndividualOrder');
    Route::post('corporates-orders', 'OrderController@storeCorporatesOrder');
    Route::post('unavailable-car', 'OrderController@unavailableCar');
    Route::post('offer-order/{offer}', 'OrderController@OfferOrder');

    Route::get('purchase-form-info', 'OrderController@purchaseInfo');
    Route::get('cars-filter-data', 'OrderController@filterData');
    Route::get('time-slots', 'ScheduleController@timeSlots');
    Route::get('make-appointment-form-data', 'AppointmentController@formData');
    Route::get('unavailable-dates', 'AppointmentController@unavailableDates');

    Route::post('make-appointment', 'AppointmentController@store');
    Route::get('banks', 'BankController');
    Route::get('getCampaignDatafromCookies', [CampaignController::class,'getCampaignData']);
    Route::post('url', [NewController::class,'geturl']);

    Route::get('/zoho/auth', [ZohoController::class, 'redirectToZoho']);
    Route::get('/zoho/callback', [ZohoController::class, 'handleZohoCallback']);

    Route::get('/slider', function(){
        // dd(view('home.slider')->render() . file_get_contents( public_path('js/home/jQuery.js')));
        return [
            'jquery' => asset('js/home/jQuery.js'),
            '$' => "var $ = jQuery.noConflict();",
            'head' => file_get_contents("https://slider.rotanacarshowroom.com/RevSliderEmbedderheadIncludes.php"),
            'slider' => file_get_contents("https://slider.rotanacarshowroom.com/RevSliderEmbedderputRevSlider.php?slide=rotana_en")
        ];
    });
});
