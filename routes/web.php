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
//include_once('api.php');



Route::get('/status', 'AdminController@status');

//User
Route::post('/app/login', 'UserController@login');
Route::post('/app/editProfile', 'UserController@editProfile');
Route::post('/app/updateAppToken', 'UserController@updateAppToken');
Route::post('/app/updateLanguage', 'UserController@updateLanguage');
Route::post('/app/registration', 'UserController@addUser');
Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    Auth::check();
    return redirect("/login");
});

Route::get('/test1')->middleware('test');

Route::get('/app/showUser', 'UserController@showUser');
Route::get('/app/dashboard', 'AdminController@dashboard');
Route::post('/app/deleteUser', 'UserController@deleteUser');
Route::post('/app/editUser', 'UserController@editUser');
Route::post('/app/changePassword', 'UserController@changePassword');
Route::post('/app/createJournalist', 'UserController@createJournalist');

Route::get('/app/showUserApi', 'UserController@showUserApi');

//Privacy
Route::get('/app/showPrivacyText', 'AdminController@showPrivacyText');
Route::post('/app/editPrivacyText', 'AdminController@editPrivacyText');
//About
Route::get('/app/showAboutText', 'AdminController@showAboutText');
Route::post('/app/editAboutText', 'AdminController@editAboutText');
//Contact
Route::get('/app/showContactText', 'AdminController@showContactText');
Route::post('/app/editContactText', 'AdminController@editContactText');

//Game
Route::get('/app/showGame', 'GameController@showGame');
Route::get('/app/showGameApi', 'GameController@showGameApi');
Route::post('/app/addGame', 'GameController@addGame');
Route::post('/app/editGame', 'GameController@editGame');
Route::post('/app/deleteGame', 'GameController@deleteGame');
//News
Route::get('/app/showNews', 'AdminController@showNews');
Route::post('/app/addNews', 'AdminController@addNews');
Route::post('/app/editNews', 'AdminController@editNews');
Route::post('/app/deleteNews', 'AdminController@deleteNews');
Route::post('/app/approveNews', 'AdminController@approveNews');
//Category
Route::get('/app/showCategory', 'AdminController@showCategory');
Route::get('/app/api/showCategory', 'AdminController@showCategoryApi');
Route::post('/app/addCategory', 'AdminController@addCategory');
Route::post('/app/editCategory', 'AdminController@editCategory');
Route::post('/app/deleteCategory', 'AdminController@deleteCategory');

//Question
Route::get('/app/showQuestion', 'GameController@showQuestion');
Route::post('/app/addQuestion', 'GameController@addQuestion');
Route::post('/app/editQuestion', 'GameController@editQuestion');
Route::post('/app/deleteQuestion', 'GameController@deleteQuestion');
//Notification
Route::get('/app/showNotification', 'GameController@showNotification');
Route::post('/app/addNotification', 'GameController@addNotification');
Route::post('/app/editNotification', 'GameController@editNotification');
Route::post('/app/deleteNotification', 'GameController@deleteNotification');


//Image Upload
Route::post('/app/upload_image', 'AdminController@image_upload');
Route::get('/', function () {
    return view('login');
});
Route::get('/login', function () {
    return view('login');
});

//all api
Route::post('/api/login-user', 'UserController@api_login');
Route::post('/api/register-user', 'UserController@api_register');
Route::get('/api/showNews', 'AdminController@showNewsApi');
Route::post('/api/showNewsQuery', 'AdminController@showNewsQueryApi');

//news api


//password if forget
Route::post('/api/generate-code-sent-to-email', 'UserController@resetPasswordGetEmail');
Route::post('/api/match-the-reset-code', 'UserController@matchPasswordCode');
Route::post('/api/reset-the-password', 'UserController@resetPasswordEmail');



Route::any('{slug}', 'AdminController@home')->middleware('auth');
