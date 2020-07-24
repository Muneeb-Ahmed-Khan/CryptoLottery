<?php

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

Route::get('/', 'PagesController@index');
Route::get('/index', 'PagesController@index');
Route::get('/mobileIndex', 'PagesController@mobileIndex');
Route::get('/about', 'PagesController@about');
Route::get('/contact', 'PagesController@contact');
Route::get('/copyright', 'PagesController@copyright');
Route::get('/dmca', 'PagesController@dmca');
Route::get('/faq', 'PagesController@faq');
Route::get('/register', 'PagesController@register');
Route::get('/terms', 'PagesController@terms');



Route::get('/unauthorized', function($request, $guard = null)
{
    if (Auth::guard('company')->check()) {
        return redirect('/company');
    }
    elseif (Auth::guard('admin')->check()) {
        return redirect('/admin');
    }
    elseif (Auth::guard('user')->check()) {
        return redirect('/user');
    }
    else
    {
        return redirect('/');
    }
})->name('ReturnToUnauthorizedPage');


Route::namespace('Auth')->group(function(){

    /* Reset Password Routes */
    //For checking out multi-Auth Password reset : https://ysk-override.com/Multi-Auth-in-laravel-54-Reset-Password-20170205/
    Route::get('/password/reset','ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/password/email','ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    Route::get('/password/reset/{token}','ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/password/reset','ResetPasswordController@reset')->name('password.update');




    /* Verification Routes */
    Route::get('email/verify', 'VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', 'VerificationController@verify')->name('verification.verify');
    Route::get('email/resend/{role}/{email}', 'VerificationController@resend')->name('verification.resend');

    /*LOGIN / Regsiter  Routes */
    Route::get('/login', 'LoginController@showLoginForm');
    Route::post('/login', 'LoginController@LoginLogic');

    Route::post('/register', 'RegisterController@RegisterLogic');
    Route::get('/register', 'RegisterController@showRegisterForm');

    
    Route::get('/login/admin', 'LoginController@showLoginFormAdmin');

    Route::post('/register/admin', 'RegisterController@RegisterLogicAdmin');
    Route::get('/register/admin', 'RegisterController@showRegisterFormAdmin');

    /*LOGOUT  Routes */
    Route::post('/logout','LoginController@logout');
});


/*Only Company can use it. any authorized/unauthorized person that uses it will be redirected to Authenticate.php Middleware
and it will return it to the ReturnToUnauthorizedPage route and that will redirect it accoring to it's gurad */
Route::group(['middleware' => ['auth:company','verified']], function () {
    Route::get('/company', 'CompanyController@Dashboard');
    Route::post('/company','CompanyController@ManageAdmin')->name('ManageAdmin');

    Route::post('/company/{adminId}/IdleUser','CompanyController@IdleUser');
    Route::post('/company/{adminId}/deleteUser','CompanyController@DeleteUser');

    Route::get('/company/ViewIdleUsers', 'CompanyController@ViewIdleUsers');
    Route::post('/company/deleteIdleUser', 'CompanyController@DeleteIdleUsers');


    Route::get('/company/{adminId}', 'CompanyController@AdminView');
    Route::post('/company/{adminId}', 'CompanyController@UserUpload')->name('UserUpload');

    Route::get( '/download/{filename}', 'CompanyController@download');
});

Route::get('/principal', function(){
    return "Go to Principal for some reason";
});


/*Only Company can use it. any authorized/unauthorized person that uses it will be redirected to Authenticate.php Middleware
and it will return it to the ReturnToUnauthorizedPage route and that will redirect it accoring to it's gurad */
Route::group(['middleware' => ['auth:admin','verified']], function () {
    Route::get('/admin', 'AdminController@Dashboard');
    Route::post('/admin', 'AdminController@ManageForm')->name('ManageLottery');

    Route::get('/admin/users', 'AdminController@ShowUsers');
    
    Route::get('/admin/inbox', 'AdminController@Inbox');
    Route::post('/admin/inbox', 'AdminController@ManageFormsInbox')->name('ManageFormsInbox');


    Route::get('/admin/form/view/{formId}', 'AdminController@ViewForm');
    
    Route::post('/admin/deployform', 'AdminController@DeployForm');
    Route::post('/admin/exceldownloadHelper', 'AdminController@ExcelDownloadHelper');


    Route::get('/admin/form/{formId}', 'AdminController@EditForm')->name('Editorm');
    Route::post('/admin/form/{formId}', 'AdminController@UpdateForm')->name('UpdateForm');

    Route::get('/admin/condition/{formId}', 'AdminController@ConditionalLogic');

    Route::get( '/download/{filename}', 'AdminController@download');

});


/*Only User can use it. any authorized/unauthorized person that uses it will be redirected to Authenticate.php Middleware
and it will return it to the ReturnToUnauthorizedPage route and that will redirect it accoring to it's gurad */
Route::group(['middleware' => ['auth:user','verified']], function () {
    Route::get('/user', 'UserController@Dashboard');
    Route::get('/user/showforms', 'UserController@ShowForms');
    Route::get('/user/publishedforms', 'UserController@ShowPublishedForms');
    Route::get('/user/savedforms', 'UserController@ShowSavedForms');

    Route::get('/user/form/view/{formId}', 'UserController@ShowViewOfSentForm');

    Route::get('/user/form/{formId}', 'UserController@UseForm');

    Route::post('/user/form/save/{formId}', 'UserController@SaveForm');
    Route::post('/user/form/send/{formId}', 'UserController@SendForm');

    Route::get( '/download/{filename}', 'UserController@download');
});




//used to delete the cache stored when redirect with data from some function.
header('Cache-Control: no-store, private, no-cache, must-revalidate'); header('Cache-Control: pre-check=0, post-check=0, max-age=0, max-stale = 0', false);
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Expires: 0', false);
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header ('Pragma: no-cache');
