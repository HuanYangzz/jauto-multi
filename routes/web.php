<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'tenancy.enforce'], function () {
	Auth::routes();
	Route::get('/logout', 'Auth\LoginController@logout');
	Route::get('/', 'HomeController@index');

	Route::get('/adminlist', 'AdminController@index');
	Route::post('/admin/{user}/reset_password', 'AdminController@reset_password');
	Route::post('/admin/{user}/check_password', 'AdminController@check_password');
	Route::post('/admin/{user}/deactivate', 'AdminController@deactivate');
	Route::post('/admin/{user}/reactivate', 'AdminController@reactivate');
	Route::get('/profile', 'AdminController@profile');
	Route::post('/update_profile', 'AdminController@update_profile');
	Route::post('/update_password/{user?}', 'AdminController@update_password');
	Route::post('/check_password/{user?}', 'AdminController@check_password');
	
	Route::post('/upload_attachment/{type_name}/{attr_name}/{id}','AttachmentController@upload_attachment');
	Route::post('/upload_file/{type_name}/{attr_name}/{id}','AttachmentController@upload_file');
	Route::get('/remove_pdf/{id}', 'AttachmentController@remove_pdf');

	Route::get('/general/check_zip/{zip}','AdminController@check_zip');
	Route::get('/general/get_notifications','AdminController@get_notification');
	Route::post('/general/read_noti/{id}','AdminController@read_notification');

	Route::get('/company/profile','CompanyController@company_profile');
	Route::post('/company/update','CompanyController@update_company');
	Route::get('/company/branches','CompanyController@branch_list');
	Route::get('/company/branch/{code}','CompanyController@branch');
	Route::post('/company/branch/update','CompanyController@update_branch');
	Route::post('/company/update_pic','CompanyController@update_pic');
	Route::get('/company/brokers','CompanyController@broker_list');
	Route::get('/company/broker/{code}','CompanyController@broker');
	Route::get('/company/employees','CompanyController@employee_list');
	Route::get('/company/employee/{code}','CompanyController@employee');
	Route::get('/company/salesmen','CompanyController@salesman_list');
	Route::get('/company/salesman/{code}','CompanyController@salesman');
	Route::post('/company/employee/update','CompanyController@update_employee');
	Route::post('/company/employee/deactivate','CompanyController@deactivate_employee');
	Route::get('/company/check_user/{type}/{id}','CompanyController@check_user');
	Route::post('/company/get_upline','CompanyController@get_upline');
	Route::get('/company/users','CompanyController@user_list');
	Route::get('/company/user/{code}','CompanyController@user');
	Route::post('/company/user/update','CompanyController@update_user');
	Route::post('/company/user/deactivate','CompanyController@deactivate_user');
	Route::get('/presales/events','PresalesController@event_list');
	Route::get('/presales/event/{code}','PresalesController@event');
	Route::post('/presales/event/update','PresalesController@update_event');
	Route::post('/presales/event/deactivate','PresalesController@deactivate_event');
	Route::get('/presales/prospect/{customer_code?}/{code?}','PresalesController@prospect');
	Route::get('/presales/check_contact/{contact}','PresalesController@check_contact');
	Route::get('/presales/check_name/{name}','PresalesController@check_name');
	Route::post('/presales/new_prospect','PresalesController@new_prospect');
	Route::post('/presales/update_prospect_detail','PresalesController@update_prospect_detail');
	Route::post('/presales/update_prospect_history','PresalesController@update_prospect_history');
	Route::get('/presales/review','PresalesController@review');
	Route::get('/presales/prospect/remove_detail/{hid}/{id}','PresalesController@remove_prospect_detail');
	Route::get('/presales/prospect_book','PresalesController@prospect_book');
	Route::get('/presales/prospect_book/{code}','PresalesController@prospect_book_detail');
	Route::post('/presales/prospect/active_check','PresalesController@prospect_active_check');
	Route::post('/presales/prospect/duplicate_check','PresalesController@prospect_duplicate_check');
	Route::post('/presales/prospect/merge','PresalesController@prospect_merge');
	Route::post('/presales/prospect/merge_data','PresalesController@prospect_merge_data');
	Route::post('/presales/prospect/save','PresalesController@save_prospect');
	Route::get('/inventory/getvariantdetail/{id}','InventoryController@get_variant_detail');
	Route::get('/inventory/getvariant/{id?}','InventoryController@get_variant');
	Route::get('/inventory/get_model_individual/{brand}','InventoryController@get_model_individual');
	Route::get('/inventory/get_model_commercial/{brand}','InventoryController@get_model_commercial');
	Route::get('/inventory/get_model/{brand}','InventoryController@get_vehicle_model');
	Route::get('/inventory/vehicle_list','InventoryController@vehicle_listing');
	Route::get('/inventory/vehicle_stock','InventoryController@vehicle_stock');
	Route::get('/inventory/stock_list','InventoryController@stock_listing');
	Route::post('/inventory/stock/update_status','InventoryController@update_stock_status');
	Route::post('/inventory/stock/edit_price','InventoryController@edit_stock_price');
	Route::post('/inventory/stock/edit_cost','InventoryController@edit_stock_cost');
	Route::post('/inventory/stock/edit_min_price','InventoryController@edit_stock_min_price');
	Route::get('/inventory/non_stock_list','InventoryController@non_stock_listing');
	Route::post('/inventory/nonstock/update_status','InventoryController@update_nonstock_status');
	Route::post('/inventory/nonstock/edit_price','InventoryController@edit_nonstock_price');
	Route::post('/inventory/nonstock/edit_cost','InventoryController@edit_nonstock_cost');
	Route::post('/inventory/nonstock/edit_min_price','InventoryController@edit_nonstock_min_price');
	Route::get('/inventory/fee_list','InventoryController@fee_listing');
	Route::get('/inventory/fee/{type}','InventoryController@fee');
	Route::post('/inventory/update_fee','InventoryController@update_fee');
	Route::post('/inventory/fee_group/update_status','InventoryController@update_fee_group');
	Route::post('/inventory/vehicle/update_status','InventoryController@update_vehicle_status');
	Route::post('/inventory/vehicle/edit_price','InventoryController@edit_vehicle_price');
	Route::post('/inventory/vehicle/edit_cost','InventoryController@edit_vehicle_cost');
	Route::post('/inventory/vehicle/edit_min_price','InventoryController@edit_vehicle_min_price');
	Route::get('/inventory/stock_distribution','InventoryController@stock_distribution');
	Route::get('/inventory/stock_transfer/{code}','InventoryController@get_stock_transfer');
	Route::post('/inventory/confirm_transfer_out','InventoryController@confirm_transfer_out');
	Route::post('/inventory/transfer_stock','InventoryController@transfer_stock');
	Route::get('/inventory/download_stock_transfer/{type}/{code}','InventoryController@download_stock_transfer');
	Route::get('/inventory/getstock/{id}','InventoryController@get_stock');
	Route::get('/sales/customer_book','SalesController@customer_book');
	Route::get('/sales/customer_book/{code}','SalesController@customer_book_detail');
	Route::post('/sales/customer/save','SalesController@save_customer_data');
	Route::get('/sales/vso/{code?}','SalesController@vso_form');
	Route::get('/sales/review','SalesController@review');
	Route::get('/sales/check_identity/{identity}','SalesController@check_identity');
});
