<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    //resource
    Route::apiResources(['user' => App\Http\Controllers\API\UserController::class]);
    Route::apiResources(['arena' => App\Http\Controllers\API\ArenaController::class]);
    Route::apiResources(['import' => App\Http\Controllers\API\ImportController::class]);
    Route::apiResources(['bankaccount' => App\Http\Controllers\API\BankDetailsController::class]);
    Route::apiResources(['employees' => App\Http\Controllers\API\EmployeeController::class]);
    Route::apiResources(['contactnumbers' => App\Http\Controllers\API\ContactController::class]);
    Route::apiResources(['emails' => App\Http\Controllers\API\EmailController::class]);
    Route::apiResources(['teams' => App\Http\Controllers\API\TeamController::class]);
    Route::apiResources(['groups' => App\Http\Controllers\API\OperatorGroupController::class]);
    Route::apiResources(['sidebar' => App\Http\Controllers\API\SidebarController::class]);


    //custom user api
    //get
    Route::get('profile', [App\Http\Controllers\API\UserController::class, 'profile'])->name('profile');
    Route::get('validate/{password}', [App\Http\Controllers\API\UserController::class, 'validate_user']);
    Route::get('getStaffs', [App\Http\Controllers\API\UserController::class, 'getStaffs']);
    Route::get('getAvailableSignatory/{signatory}', [App\Http\Controllers\API\UserController::class, 'getAvailableSignatory']);
    Route::get('getUsersSignatory/{signatory}', [App\Http\Controllers\API\UserController::class, 'getUsersSignatory']);
    Route::get('getUserTeam/{teamid}', [App\Http\Controllers\API\UserController::class, 'getUserTeam']);
    Route::get('assigneduserteam/{teamid}', [App\Http\Controllers\API\UserController::class, 'getAssigned']);
    Route::get('backup', [App\Http\Controllers\API\UserController::class, 'artisancall']);
    Route::get('roles', [App\Http\Controllers\API\UserController::class, 'roles']);
    Route::get('getPermission/{id}', [App\Http\Controllers\API\UserController::class, 'getpermission']);
    Route::post('deletePermission', [App\Http\Controllers\API\UserController::class, 'DeletePermissions']);
    //put
    Route::put('updateUserTeam/{id}', [App\Http\Controllers\API\UserController::class, 'updateUserTeam']);
    Route::put('updateAssignedTeam/{id}', [App\Http\Controllers\API\UserController::class, 'updateAssignedTeam']);
    Route::put('updateSignatory', [App\Http\Controllers\API\UserController::class, 'updateSignatory']);
    Route::put('profile', [App\Http\Controllers\API\UserController::class, 'updateProfile'])->name('updateProfile');
    Route::put('updateSelectedUserToTeam/{team}', [App\Http\Controllers\API\UserController::class, 'updateSelectedUserToTeam']);


    //custom BankDetails api
    //get
    Route::get('arenaId', [App\Http\Controllers\API\BankDetailsController::class, 'show_arena'])->name('arenaId');
    Route::get('filterBank/{id}', [App\Http\Controllers\API\BankDetailsController::class, 'FilterBank']);
    Route::get('Companybanks', [App\Http\Controllers\API\BankDetailsController::class, 'Companybank']);
    Route::get('arenaSelectedBank/{id}', [App\Http\Controllers\API\BankDetailsController::class, 'arenaSelectedBank']);
    //post
    Route::post('bankStore', [App\Http\Controllers\API\BankDetailsController::class, 'bankStore']);



    //custome Import api
    //get
    Route::get('importwithstatus', [App\Http\Controllers\API\ImportController::class, 'withstatus']);
    Route::get('depositeandreflenish', [App\Http\Controllers\API\ImportController::class, 'depositedata']);
    Route::get('importDateRange/{from}/{to}', [App\Http\Controllers\API\ImportController::class, 'importDateRange']);
    Route::get('depositReplenishDateRange/{from}/{to}', [App\Http\Controllers\API\ImportController::class, 'depositReplenishDateRange']);
    Route::get('searchSoa', [App\Http\Controllers\API\ImportController::class, 'searchSoa']);
    Route::get('filterNoArena', [App\Http\Controllers\API\ImportController::class, 'filterNoArena']);
    Route::get('filterSite', [App\Http\Controllers\API\ImportController::class, 'filterSite']);
    Route::get('summaryReport', [App\Http\Controllers\API\ImportController::class, 'summaryReport']);


    Route::get('count', [App\Http\Controllers\API\ImportController::class, 'countdata']);
    Route::get('updateBank/{id}/{bank_id}', [App\Http\Controllers\API\ImportController::class, 'updatebankaccount']);
    Route::get('selectedbank/{id}', [App\Http\Controllers\API\ImportController::class, 'bankaccountfilter']);
    Route::get('convertToExcel/{group}/{data}', [App\Http\Controllers\API\ImportController::class, 'ConvertToExcel']);
    //post
    Route::post('clearfilterbydate', [App\Http\Controllers\API\ImportController::class, 'Clearfilterbydate']);

    //put
    Route::put('arenaStatus', [App\Http\Controllers\API\ImportController::class, 'arenastatus']);

    //cusom Delete Soa
    Route::delete('deleteOneSoa/{id}', [App\Http\Controllers\API\ImportController::class, 'deleteSpecificSoa']);


    //custom Arena api
    //post
    Route::post('importArena', [App\Http\Controllers\API\ArenaController::class, 'importArena']);
    Route::post('clearArena', [App\Http\Controllers\API\ArenaController::class, 'clearArena']);
    //get
    Route::get('getEmails/{areaCode}', [App\Http\Controllers\API\ArenaController::class, 'getEmails']);
    Route::get('getContacts/{areaCode}', [App\Http\Controllers\API\ArenaController::class, 'getContacts']);
    Route::get('emailDelete/{id}', [App\Http\Controllers\API\ArenaController::class, 'deleteEmail']);
    Route::get('getArenaNoTeam', [App\Http\Controllers\API\ArenaController::class, 'getArenaNoTeam']);
    Route::get('getArenaTeam/{team}', [App\Http\Controllers\API\ArenaController::class, 'getArenaTeam']);
    Route::get('arenaToExcel', [App\Http\Controllers\API\ArenaController::class, 'masterListExcel']);

    //put
    Route::put('updateArenaTeam/{areaCode}', [App\Http\Controllers\API\ArenaController::class, 'updateArenaTeam']);
    Route::put('updateSelectedArenaToTeam/{team}', [App\Http\Controllers\API\ArenaController::class, 'updateSelectedArenaToTeam']);
    Route::put('updateBankSelection/{id}', [App\Http\Controllers\API\ArenaController::class, 'updateBankSelection']);
    //delete
    Route::delete('deleteContact/{id}', [App\Http\Controllers\API\ArenaController::class, 'deleteContact']);



    //custom Employee api
    //get
    Route::get('deletePosition/{id}', [App\Http\Controllers\API\EmployeeController::class, 'deletepostionNow']);
    Route::get('getposition', [App\Http\Controllers\API\EmployeeController::class, 'getpostionNow']);
    //put
    Route::put('updateposition/{id}', [App\Http\Controllers\API\EmployeeController::class, 'updatepostionNowwith']);
    Route::put('selected/{id}', [App\Http\Controllers\API\EmployeeController::class, 'selectedbyUser']);
    //post
    Route::post('saveposition', [App\Http\Controllers\API\EmployeeController::class, 'savepostionNow']);



    //custom Team api
    //get
    Route::get('deleteTeam/{id}',[App\Http\Controllers\API\TeamController::class,'destroy']);
    //post
    Route::post('updateTeam/{id}',[App\Http\Controllers\API\TeamController::class,'update']);



    //custom actvity
    //get
    Route::get('indexActivity', [App\Http\Controllers\API\ActivitylogsController::class, 'AcitivityIndex']);
    Route::get('Logsindex', [App\Http\Controllers\API\ActivitylogsController::class, 'logsIndex']);
    //post
    Route::post('activitybank', [App\Http\Controllers\API\ActivitylogsController::class, 'BankAccount']);



    //custom sending email
    //post
    Route::post('sendEmail',[App\Http\Controllers\API\EmailController::class,'SendEmail']);
    Route::post('sendMultiEmail',[App\Http\Controllers\API\EmailController::class,'MultisendEmail']);
    Route::post('sendZipEmail',[App\Http\Controllers\API\EmailController::class,'sendZipEmail']);
    Route::post('SaveCCEmail',[App\Http\Controllers\API\EmailController::class,'StoreCCEmail']);
    Route::get('isUsed/{id}',[App\Http\Controllers\API\EmailController::class,'isUsedUpdate']);
    Route::get('getActiveCc',[App\Http\Controllers\API\EmailController::class,'getActiveCC']);


    //custom group
    Route::get('getselectedgroup/{id}',[App\Http\Controllers\API\OperatorGroupController::class,'getselectedGroup']);
    Route::get('deletegroupsselectedarena/{id}',[App\Http\Controllers\API\OperatorGroupController::class,'Deletegroupsselectedarena']);
    Route::post('addtogroup',[App\Http\Controllers\API\OperatorGroupController::class,'Addtogroup']);
    Route::post('fetchSoaByOperatorGroup', [App\Http\Controllers\API\OperatorGroupController::class, 'fetchSoaByOperatorGroup']);
    Route::get('groupSoaSummaryReport', [App\Http\Controllers\API\OperatorGroupController::class, 'groupSoaSummaryReport']);
    Route::post('addArenaSearch', [App\Http\Controllers\API\OperatorGroupController::class, 'AddArenaSearch']);




    //search centralized

    Route::post('centralizeSearch', [App\Http\Controllers\API\ImportController::class, 'searchCentralize']);