<?php

use Illuminate\Http\Request;
use App\Models\requestHistory;
use Illuminate\Support\Facades\Route;
use App\Models\UnitOfMeasureController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\DebtsController;
use App\Http\Controllers\FundsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SpotsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\FencesController;
use App\Http\Controllers\MoneysController;
use App\Http\Controllers\OwnersController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\DefectsController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CautionsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\ServantsController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\SafeguardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EnterprisesController;
use App\Http\Controllers\PointOfSaleController;
use App\Http\Controllers\DebtPaymentsController;
use App\Http\Controllers\DecisionTeamController;
use App\Http\Controllers\ExpendituresController;
use App\Http\Controllers\OtherEntriesController;
use App\Http\Controllers\RequestFilesController;
use App\Http\Controllers\TicketOfficeController;
use App\Http\Controllers\DepositsUsersController;
use App\Http\Controllers\DocumentTypesController;
use App\Http\Controllers\RequestServedController;
use App\Http\Controllers\DetailsRequestController;
use App\Http\Controllers\FenceTicketingController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\RequestHistoryController;
use App\Http\Controllers\SelfReferencesController;
use App\Http\Controllers\TransfertstockController;
use App\Http\Controllers\MoneyConversionController;
use App\Http\Controllers\SubDepartementsController;
use App\Http\Controllers\AffectationUsersController;
use App\Http\Controllers\PricesCategoriesController;
use App\Http\Controllers\UsersPointOfSaleController;
use App\Http\Controllers\DepositControllerController;
use App\Http\Controllers\RequestReferencesController;
use App\Http\Controllers\UsersTicketOfficeController;
use App\Http\Controllers\CustomerControllerController;
use App\Http\Controllers\ProviderControllerController;
use App\Http\Controllers\RequestapprovmentsController;
use App\Http\Controllers\ServicesControllerController;
use App\Http\Controllers\DecisionDecisionteamController;
use App\Http\Controllers\StockHistoryControllerController;
use App\Http\Controllers\UnitOfMeasureControllerController;
use App\Http\Controllers\ValidatedbydecisionteamController;
use App\Http\Controllers\AttemptactivationaccountController;
use App\Http\Controllers\DecisionChiefdepartmentsController;
use App\Http\Controllers\NbrdecisionteamValidationController;
use App\Http\Controllers\CategoriesCustomerControllerController;
use App\Http\Controllers\CategoriesServicesControllerController;
use App\Http\Controllers\ServicesadditionalfeesController;
use OpenApi\Annotations\AdditionalProperties;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/** Users getways */

//get all users and add new
Route::ApiResource('/users',UsersController::class);
Route::get('/users/enterprise/{id}',[UsersController::class,'index']);
Route::delete('/users/delete/{id}',[UsersController::class,'destroy2']);
Route::patch('/users/update/{id}',[UsersController::class,'update2']);
Route::post('/users/updatestatus',[UsersController::class,'changerStatus']);
Route::post('/users/updatepassword',[UsersController::class,'updatePassword']);
Route::get('/getuser',[UsersController::class,'getone']);
Route::post('/users/dashboard/{id}',[UsersController::class,'dashboard']);


//connection or login
Route::post('/users/login',[UsersController::class,'login']);

//get access level for validations
Route::get('/getaccess/{id}',[UsersController::class,'getuseraccess']);

/** end of users getwayrs */

Route::resource('/affectation_users', AffectationUsersController::class);
Route::put('/affectation/update',[AffectationUsersController::class,'update2']);
Route::delete('/affectation/delete/{id}',[AffectationUsersController::class,'destroy2']);
Route::resource('/attemptactivationaccunt', AttemptactivationaccountController::class);

Route::resource('/comments', CommentsController::class);
Route::get('getByid_request/{id}',[CommentsController::class,'getCommentByIdRequest']);
Route::get('reference',[AffectationUsersController::class,'reference']);

Route::resource('/decision_chiefdepartments', DecisionChiefdepartmentsController::class);
Route::get('/decision_chief/single/{id}',[DecisionChiefdepartmentsController::class,'getsingledecision']);

Route::resource('/decision_decisionteam', DecisionDecisionteamController::class);

Route::resource('/decision_team', DecisionTeamController::class);
Route::patch('/decision_team/update/{id}',[DecisionTeamController::class,'update2']);
Route::delete('/decision_team/delete/{id}',[DecisionTeamController::class,'destroy2']);

Route::resource('/departments', DepartementController::class);
Route::put('/departments/update/{id}',[DepartementController::class,'update2']);
Route::get('/departments/findbyid/{id}',[DepartementController::class,'findbyid']);
Route::get('/departments/getusers/{id}',[DepartementController::class,'findusers']);
Route::delete('/department/delete/{id}',[DepartementController::class,'destroy2']);
Route::get('/departments/subdeparts/{id}',[DepartementController::class,'findsubdeparts']);

Route::resource('/details_request', DetailsRequestController::class);
Route::get('/request_details/{requestid}',[DetailsRequestController::class,'showforarequest']);

Route::apiResource('/funds', FundsController::class);
Route::get('funds/reset/{id}',[FundsController::class,'reset']);
Route::delete('/funds/delete/{id}',[FundsController::class,'destroy2']);
Route::patch('funds/update/{funds}',[FundsController::class,'update2']);

Route::resource('/money_conversion', MoneyConversionController::class);
Route::get('/money_conversion/enterprise/{enterpriseId}', [MoneyConversionController::class,'index']);
Route::patch('/money_conversion/update/{id}',[MoneyConversionController::class,'updateMe']);
Route::delete('/money_conversion/delete/{id}',[MoneyConversionController::class,'delete']);

Route::resource('/money', MoneysController::class);
Route::get('/money/enterprise/{id}',[MoneysController::class,'index']);
Route::patch('/money/update/{id}',[MoneysController::class,'update2']);
Route::delete('/money/delete/{id}',[MoneysController::class,'destroy2']);

Route::resource('/nbrdecisionteam_validation', NbrdecisionteamValidationController::class);

Route::resource('/request_files', RequestFilesController::class);
Route::post('request_files/upload',[RequestFilesController::class,'getsfiles']);
Route::get('/request_files/single/{id}',[RequestFilesController::class,'getfilesbyrequest']);
Route::get('/requests/decisionteamall',[RequestController::class,'validatedbydecisionteamall']);

Route::resource('/request_references', RequestReferencesController::class);
Route::get('/request_references/single/{id}',[RequestReferencesController::class,'getreferencesbyrequest']);

Route::resource('/request_served', RequestServedController::class);
Route::get('/request_served_by_id_Request/{id}',[RequestServedController::class,'getrequest_servedByIdRequest']);

Route::resource('/request_history',RequestHistoryController::class);
Route::get('/request_history/byfund/{fund}',[RequestHistoryController::class,'getbyfund']);

Route::resource('/request', RequestsController::class);
Route::post('/request/validation/{userid}', [RequestsController::class,'requestvalidation']);
Route::delete('/request_delete/{id}', [RequestsController::class,'deleteRequest']);
Route::put('/request_update/{id}', [RequestsController::class,'updateRequest']);
Route::get('/requests/user/{userid}',[RequestsController::class,'byuser']);
Route::get('/requests/bydepart/{departid}',[RequestsController::class,'bydepart']);
Route::get('/requests/files/depart/{departid}',[RequestsController::class,'filesbydepart']);
Route::get('/requests/requestservedbytub/{idtub}',[RequestsController::class,'requestservedbytub']);
Route::get('/validatedbychiefdepart/{userid}',[RequestsController::class,'validatedbychiefdepart']);
Route::get('/unvalidatedbychiefdepart/{userid}',[RequestsController::class,'unvalidatedbychiefdepart']);

//Request to validate
Route::post('/tovalidatebychiefdepart',[RequestsController::class,'tovalidatebychiefdepart']);
Route::post('/tovalidatebydecisionteam',[RequestsController::class,'tovalidatebydecisionteam']);

Route::get('/validatedbydecisionteam/{userid}',[RequestsController::class,'validatedbydecisionteam']);
Route::get('/unvalidatedbydecisionteam/{userid}',[RequestsController::class,'unvalidatedbydecisionteam']);

Route::get('/tobeserved/{userid}',[RequestsController::class,'tobeserved']);
Route::get('/tobeservedall',[RequestsController::class,'tobeservedall']);

Route::get('/alreadyserved/{userid}',[RequestsController::class,'alreadyserved']);
Route::get('/alreadyservedall',[RequestsController::class,'alreadyservedall']);

Route::resource('/self_references', SelfReferencesController::class);
Route::resource('/sud_departements', SubDepartementsController::class);
Route::resource('/validatedbydecisionteam', ValidatedbydecisionteamController::class);

Route::get('getByid/{id}',[FundsController::class,'getByid']);
Route::get('getByidMony/{id}',[MoneyConversionController::class,'getByid']);
Route::delete('deleteMe/{id}',[MoneyConversionController::class,'deleteMe']);

/**
 * CERO UZISHA API
 */
//Unif of measure
Route::apiResource('unitofmeasures',UnitOfMeasureControllerController::class);
Route::get('/unitofmeasures/enterprise/{enterprise_id}',[UnitOfMeasureControllerController::class,'index']);
Route::put('/unitofmeasures/update/{id}',[UnitOfMeasureControllerController::class,'update2']);
Route::patch('/unitofmeasures/update/{id}',[UnitOfMeasureControllerController::class,'update2']);
//Owners
Route::apiResource('owners',OwnersController::class);
//Enterprises (Entreprises)
Route::apiResource('enterprises',EnterprisesController::class);
Route::get('/enterprises/getinfos/{id}',[EnterprisesController::class,'getone']);

//Point of sale (Points des ventes)
Route::apiResource('pointofsales',PointOfSaleController::class);
Route::get('pointofsales/enterprise_id/{id}',[PointOfSaleController::class,'foraspecificEse']);
Route::patch('/pointofsales/update/{id}',[PointOfSaleController::class,'update2']);
Route::delete('/pointofsales/delete/{id}',[PointOfSaleController::class,'destroy2']);
Route::post('/pointofsales/affectdeposits',[PointOfSaleController::class,'affectDeposits']);
Route::get('/pointofsales/deposits/{posid}',[PointOfSaleController::class,'getdeposits']);
Route::get('/pointofsales/agents/{posid}',[PointOfSaleController::class,'getagents']);
Route::delete('/pointofsales/deposits/delete/{affectation_id}',[PointOfSaleController::class,'deleteposit']);
Route::post('/pointofsales/user/delete',[PointOfSaleController::class,'deleteuser']);
Route::apiResource('userspointofsale',UsersPointOfSaleController::class);
//Ticket offices (Guichets)
Route::apiResource('ticketoffices',TicketOfficeController::class);
Route::apiResource('usersticketoffice',UsersTicketOfficeController::class);

//Deposits (depots) and Stock Story (approvisionnements,destockages et historiques ventes:Entrees et sorties stocks)
Route::apiResource('deposits',DepositControllerController::class);
Route::get('/deposits/enterprise/{id}',[DepositControllerController::class,'index']);
Route::put('/deposits/update/{id}',[DepositControllerController::class,'update2']);
Route::patch('/deposits/update/{id}',[DepositControllerController::class,'update2']);
Route::delete('/deposits/delete/{id}',[DepositControllerController::class,'delete2']);
Route::post('/deposits/participants',[DepositControllerController::class,'participants']);
Route::post('/deposit/addservices',[DepositControllerController::class,'addservices']);
Route::post('/deposit/users',[DepositControllerController::class,'depositForUser']);

//Deposits users
Route::apiResource('depositsusers',DepositsUsersController::class);
Route::delete('/depositsusers/delete/{id}',[DepositsUsersController::class,'deleteaffectation']);
Route::put('/depositsusers/update/{id}',[DepositsUsersController::class,'updateaffectation']);

//Stock History
Route::apiResource('stockhistory',StockHistoryControllerController::class);
Route::get('/stockhistory/enterprise/{id}',[StockHistoryControllerController::class,'index']);
Route::get('/stockhistory/serviceid/{serviceid}',[StockHistoryControllerController::class,'getbyservice']);
Route::post('/stockhistory/byuser',[StockHistoryControllerController::class,'getbyuser']);
Route::post('/stockhistory/byuser/grouped',[StockHistoryControllerController::class,'getbyusergrouped']);
Route::post('/stockhistory/expiration',[StockHistoryControllerController::class,'reportexpiration']);
Route::post('/stockhistory/fordeposit',[StockHistoryControllerController::class,'fordeposit']);
Route::post('/stockhistory/multipleservices',[StockHistoryControllerController::class,'multipleservices']);

//Transfert stock
Route::apiResource('transfertstock',TransfertstockController::class);
Route::get('/transfertstock/enterprise/{id}',[TransfertstockController::class,'index']);
Route::post('/transfertstock/validation',[TransfertstockController::class,'validation']);
Route::post('/transfertstock/cancel',[TransfertstockController::class,'canceling']);
Route::post('/transfertstock/status',[TransfertstockController::class,'statusChange']);

//request approvments
Route::apiResource('requestapprovments',RequestapprovmentsController::class);
Route::get('/requestapprovments/enterprise/{id}',[RequestapprovmentsController::class,'index']);
Route::post('/requestapprovments/validation',[RequestapprovmentsController::class,'validation']);
Route::post('/requestapprovments/cancel',[RequestapprovmentsController::class,'canceling']);

Route::apiResource('typesdocuments',DocumentTypesController::class);
Route::get('/typesdocuments/enterprise/{id}',[DocumentTypesController::class,'index']);

//Services and Articles
Route::apiResource('categoriesServices',CategoriesServicesControllerController::class);
Route::get('/categoriesServices/enterprise/{id}',[CategoriesServicesControllerController::class,'index']);
Route::put('/categoriesServices/update/{id}',[CategoriesServicesControllerController::class,'update2']);
Route::patch('/categoriesServices/update/{id}',[CategoriesServicesControllerController::class,'update2']);

Route::apiResource('services',ServicesControllerController::class);
Route::get('/services/enterprise/{enterprise_id}',[ServicesControllerController::class,'index']);
Route::post('/services/list',[ServicesControllerController::class,'services_list']);
Route::put('/services/update/{id}',[ServicesControllerController::class,'update2']);
Route::patch('/services/update/{id}',[ServicesControllerController::class,'update2']);
Route::delete('/services/delete/{id}',[ServicesControllerController::class,'destroy2']);
Route::get('/servicestosell/{userid}',[ServicesControllerController::class,'give_to_seller']);
Route::get('/services/myarticles/{userid}',[ServicesControllerController::class,'myarticles']);
Route::get('/services/depositarticles/{depositid}',[ServicesControllerController::class,'depositarticles']);
Route::get('/services/depositall/{depositid}',[ServicesControllerController::class,'depositall']);
Route::post('/services/importation',[ServicesControllerController::class,'importation']);

Route::apiResource('pricescategories',PricesCategoriesController::class);
Route::put('/pricescategories/update/{id}',[PricesCategoriesController::class,'update2']);
Route::patch('/pricescategories/update/{id}',[PricesCategoriesController::class,'update2']);
Route::delete('/pricescategories/delete/{id}',[PricesCategoriesController::class,'deletepricing']);
Route::get('/pricescategories/service/{id}',[PricesCategoriesController::class,'foraservice']);

//Customers
Route::apiResource('categoriescustomers',CategoriesCustomerControllerController::class);
Route::get('/categoriescustomers/enterprise/{enterprise_id}',[CategoriesCustomerControllerController::class,'index']);
Route::put('/categoriescustomers/update/{id}',[CategoriesCustomerControllerController::class,'update2']);
Route::patch('/categoriescustomers/update/{id}',[CategoriesCustomerControllerController::class,'update2']);
Route::delete('/categoriescustomers/delete/{id}',[CategoriesCustomerControllerController::class,'destroy2']);

Route::apiResource('customers',CustomerControllerController::class);
Route::get('/customers/enterprise/{id}',[CustomerControllerController::class,'index']);
Route::put('/customers/update/{id}',[CustomerControllerController::class,'update2']);
Route::patch('/customers/update/{id}',[CustomerControllerController::class,'update2']);
Route::delete('/customers/delete/{id}',[CustomerControllerController::class,'delete']);
Route::post('/customers/uuid',[CustomerControllerController::class,'getbyuuid']);

//PROVIDERS
Route::apiResource('providers',ProviderControllerController::class);
Route::get('/providers/enterprise/{id}',[ProviderControllerController::class,'index']);
Route::put('/providers/update/{id}',[ProviderControllerController::class,'update2']);
Route::patch('/providers/update/{id}',[ProviderControllerController::class,'update2']);
Route::delete('/providers/delete/{id}',[ProviderControllerController::class,'delete']);
Route::get('/providers/stockhistory/{id}',[ProviderControllerController::class,'stockhistory']);
Route::get('/providers/stockhistory/cash/{id}',[ProviderControllerController::class,'cashstockhistory']);
Route::get('/providers/stockhistory/debt/{id}',[ProviderControllerController::class,'debtstockhistory']);

//tables
Route::apiResource('tables',TablesController::class);
Route::get('/tables/enterprise/{id}',[TablesController::class,'index']);
Route::get('/tables/sales/{id}',[TablesController::class,'sales']);
Route::get('/tables/servants/{id}',[TablesController::class,'servants']);
Route::put('/tables/update/{id}',[TablesController::class,'update2']);
Route::patch('/tables/update/{id}',[TablesController::class,'update2']);
Route::delete('/tables/delete/{id}',[TablesController::class,'delete2']);

//servants
Route::apiResource('servants',ServantsController::class);
Route::get('/servants/enterprise/{id}',[ServantsController::class,'index']);
Route::delete('/servants/delete/{id}',[ServantsController::class,'delete']);
Route::put('/servants/update/{id}',[ServantsController::class,'update2']);
Route::patch('/servants/update/{id}',[ServantsController::class,'update2']);
Route::get('/servants/sales/{id}',[ServantsController::class,'getsales']);

//Cautions, Bonus and Points
Route::apiResource('cautions',CautionsController::class);
Route::get('/cautions/enterprise/{id}',[CautionsController::class,'index']);
Route::get('/cautions/customer/{id}',[CautionsController::class,'foracustomer']);

Route::apiResource('bonus',BonusController::class);
Route::get('/bonus/enterprise/{id}',[BonusController::class,'index']);
Route::get('/bonus/customer/{id}',[BonusController::class,'foracustomer']);

Route::apiResource('points',PointsController::class);
Route::get('/points/enterprise/{id}',[PointsController::class,'index']);
Route::get('/points/customer/{id}',[PointsController::class,'foracustomer']);
//Fences (Clotures)
Route::apiResource('fences',FencesController::class);
Route::get('/fences/enterprise/{id}',[FencesController::class,'index']);
Route::post('/fences/dataforfencing/',[FencesController::class,'dataforfencing']);
Route::delete('/fences/delete/{id}',[FencesController::class,'delete2']);
Route::get('/fences/show/{id}',[FencesController::class,'getone']);

Route::apiResource('fenceticketing',FenceTicketingController::class);
//Invoices (Factures)
Route::apiResource('invoices',InvoicesController::class);
Route::get('/invoices/enterprise/{id}',[InvoicesController::class,'index']);
Route::get('/invoices/customer/{id}',[InvoicesController::class,'foracustomer']);
Route::get('/invoices/comptecourant/{customerid}',[InvoicesController::class,'comptecourant']);
Route::post('/invoices/users/',[InvoicesController::class,'foraspecificuser']);
Route::patch('/invoices/cancel',[InvoicesController::class,'cancelling']);
Route::get('/orders/enterprise/{id}',[InvoicesController::class,'enterpriseorders']);
Route::get('/orders/users/{userid}',[InvoicesController::class,'userorders']);

Route::apiResource('invoicedetails',InvoiceDetailsController::class);

Route::apiResource('debts',DebtsController::class);
Route::get('/debts/enterprise/{enterprise_id}',[DebtsController::class,'index']);
Route::post('/debts/payment',[DebtsController::class,'payment_debt']);

Route::apiResource('payments',DebtPaymentsController::class);
//Financial mouvements
Route::apiResource('accounts',AccountsController::class);
Route::put('accounts/update/{id}',[AccountsController::class,'update2']);
Route::patch('accounts/update/{id}',[AccountsController::class,'update2']);
Route::get('accounts/enterprise/{enterprise_id}',[AccountsController::class,'index']);
Route::get('accounts/getone/{account_id}',[AccountsController::class,'showone']);
Route::delete('accounts/delete/{id}',[AccountsController::class,'delete']);

Route::apiResource('expenditures',ExpendituresController::class);
Route::post('/expenditures/doneby',[ExpendituresController::class,'doneby']);
Route::post('/expenditures/byaccount',[ExpendituresController::class,'byaccount']);
Route::post('/expenditures/delete/{id}',[ExpendituresController::class,'delete']);

Route::apiResource('otherentries',OtherEntriesController::class);
Route::get('/otherentries/enterprise/{enterpriseid}',[OtherEntriesController::class,'index']);
Route::get('/otherentries/account/{accountid}',[OtherEntriesController::class,'byaccount']);
Route::get('/otherentries/update/{id}',[OtherEntriesController::class,'update2']);
Route::get('/otherentries/delete/{id}',[OtherEntriesController::class,'delete']);
Route::get('/otherentries/doneby/{id}',[OtherEntriesController::class,'doneby']);

/**
 * Safeguards
 */
Route::apiResource('safeguards',SafeguardController::class);
Route::resource('/role',RolesController::class);
Route::delete('/role/delete/{id}',[RolesController::class,'destroy2']);
Route::post('/role/owner',[RolesController::class,'ruleForOwner']);
Route::get('/role/enterprise/{id}',[RolesController::class,'index']);
Route::get('/role/permissions/{id}',[RolesController::class,'gerpermissions']);

/**
 * Pressings
 */

 //Colors
Route::apiResource('colors',ColorsController::class);
Route::get('/colors/enterprise/{enterpriseid}',[ColorsController::class,'index']);
//Defects
Route::apiResource('defects',DefectsController::class);
Route::get('/defects/enterprise/{enterpriseid}',[DefectsController::class,'index']);
//Spots
Route::apiResource('spots',SpotsController::class);
Route::get('/spots/enterprise/{enterpriseid}',[SpotsController::class,'index']);
//Styles
Route::apiResource('styles',StylesController::class);
Route::get('/styles/enterprise/{enterpriseid}',[StylesController::class,'index']);
//materials
Route::apiResource('materials',MaterialsController::class);
Route::get('/materials/enterprise/{enterpriseid}',[MaterialsController::class,'index']);
//additional services fees
Route::apiResource('additionalfees',ServicesadditionalfeesController::class);
Route::get('/additionalfees/enterprise/{enterpriseid}',[ServicesadditionalfeesController::class,'index']);
