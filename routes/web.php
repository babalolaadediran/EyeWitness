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

# entity route grouping
Route::group(['entity' => 'entity'], function () {
    
    # login page
    Route::get('/', function(){
        return view('login');
    });

    # login admin
    Route::post('/', 'LoginController@login');
});

# admin route grouping
Route::group(['admin' => 'admin'], function () {
    Route::prefix('administrator')->group(function () {

        # admin home
        Route::get('/home', 'AdminController@home');
    
        # admin profile
        Route::get('/profile', 'AdminController@profile');
    
        # update profile
        Route::post('/update/profile', 'AdminController@updateProfile');
    
        # update password 
        Route::post('/update/password', 'AdminController@updatePassword');
    
        # district page
        Route::get('/district', 'AdminController@districtPage');
    
        # create new district
        Route::post('/district', 'AdminController@createNewDistrict');
    
        # edit district
        Route::get('/edit/district/{id}', 'AdminController@editDistrict');
    
        # update district
        Route::post('/edit/district/{id}', 'AdminController@updateDistrict');
    
        # delete district
        Route::delete('/delete/district', 'AdminController@deleteDistrict');
    
        # district head page
        Route::get('/district/head', 'AdminController@registerDistrictHeadPage');
    
        # create district head
        Route::post('/district/head', 'AdminController@registerDistrictHead');
    
        # manage district heads
        Route::get('/manage/district/heads', 'AdminController@manageDistrictHeads');
    
        # edit district head
        Route::get('/edit/district/head/{id}', 'AdminController@editDistrictHead');
    
        # update district head
        Route::post('/edit/district/head/{id}', 'AdminController@updateDistrictHead');
    
        # delete district head
        Route::delete('/delete/district/head', 'AdminController@deleteDistrictHead');
    
        # municipal page
        Route::get('/municipal', 'AdminController@municipalPage');
    
        # create new municipal
        Route::post('/municipal', 'AdminController@createNewMunicipal');
    
        # edit municipal
        Route::get('/edit/municipal/{id}', 'AdminController@editMunicipal');
    
        # update municipal
        Route::post('/edit/municipal/{id}', 'AdminController@updateMunicipal');
    
        # delete municipal
        Route::delete('/delete/municipal', 'AdminController@deleteMunicipal');
    
        # municipal head page
        Route::get('/municipal/head', 'AdminController@registerMunicipalHeadPage');
    
        # create municipal head
        Route::post('/municipal/head', 'AdminController@registerMunicipalHead');
    
        # manage municipal heads
        Route::get('/manage/municipal/heads', 'AdminController@manageMunicipalHeads');
    
        # edit municipal head
        Route::get('/edit/municipal/head/{id}', 'AdminController@editMunicipalHead');
    
        # update municipal head
        Route::post('/edit/municipal/head/{id}', 'AdminController@updateMunicipalHead');
    
        # delete municipal head
        Route::delete('/delete/municipal/head', 'AdminController@deleteMunicipalHead');
    
        # citizen page
        Route::get('/citizen', 'AdminController@citizenPage');
    
        # register citizen
        Route::post('/citizen', 'AdminController@registerCitizen');
    
        # manage citizens
        Route::get('/manage/citizens', 'AdminController@manageCitizens');
    
        # edit citizen
        Route::get('/edit/citizen/{id}', 'AdminController@editCitizen');
    
        # update citizen
        Route::post('/edit/citizen/{id}', 'AdminController@updateCitizen');
    
        # delete citizen
        Route::delete('/delete/citizen', 'AdminController@deleteCitizen');
    
        # reports page
        Route::get('/reports', 'AdminController@reportsPage');
    
        # report details
        Route::get('/report/details/{id}', 'AdminController@reportDetails');
    
        # approve report
        Route::post('/approve/report', 'AdminController@approveReport');
    
        # decline report
        Route::post('/decline/report', 'AdminController@declineReport');
    
        # delete
        Route::delete('/delete/report', 'AdminController@deleteReport');
    
        # agency page
        Route::get('/agency', 'AdminController@agencyPage');
    
        # register agency
        Route::post('/agency', 'AdminController@registerAgency');
    
        # edit agency
        Route::get('/edit/agency/{id}', 'AdminController@editAgency');
    
        # update agency
        Route::post('/edit/agency/{id}', 'AdminController@updateAgency');
    
        # delete agency
        Route::delete('/delete/agency', 'AdminController@deleteAgency');
    
        # admin logout
        Route::post('/logout', 'AdminController@logout');
    });
});

# district route grouping
Route::group(['district' => 'district'], function () {
    Route::prefix('district')->group(function () {
        # district head home
        Route::get('/home', 'DistrictController@home');
    
        # district head profile
        Route::get('/profile', 'DistrictController@profile');
    
        # update profile
        Route::post('/update/profile', 'DistrictController@updateProfile');
    
        # update password 
        Route::post('/update/password', 'DistrictController@updatePassword');
    
        # municipal head page
        Route::get('/municipal/head', 'DistrictController@registerMunicipalHeadPage');
    
        # create municipal head
        Route::post('/municipal/head', 'DistrictController@registerMunicipalHead');
    
        # manage municipal heads
        Route::get('/manage/municipal/heads', 'DistrictController@manageMunicipalHeads');
    
        # edit municipal head
        Route::get('/edit/municipal/head/{id}', 'DistrictController@editMunicipalHead');
    
        # update municipal head
        Route::post('/edit/municipal/head/{id}', 'DistrictController@updateMunicipalHead');
    
        # delete municipal head
        Route::delete('/delete/municipal/head', 'DistrictController@deleteMunicipalHead');
    
        # citizen page
        Route::get('/citizen', 'DistrictController@citizenPage');
    
        # register citizen
        Route::post('/citizen', 'DistrictController@registerCitizen');
    
        # manage citizens
        Route::get('/manage/citizens', 'DistrictController@manageCitizens');
    
        # edit citizen
        Route::get('/edit/citizen/{id}', 'DistrictController@editCitizen');
    
        # update citizen
        Route::post('/edit/citizen/{id}', 'DistrictController@updateCitizen');
    
        # delete citizen
        Route::delete('/delete/citizen', 'DistrictController@deleteCitizen');
    
        # reports page
        Route::get('/reports', 'DistrictController@reportsPage');
    
        # report details
        Route::get('/report/details/{id}', 'DistrictController@reportDetails');
    
        # approve report
        Route::post('/approve/report', 'DistrictController@approveReport');
    
        # decline report
        Route::post('/decline/report', 'DistrictController@declineReport');
    
        # delete
        Route::delete('/delete/report', 'DistrictController@deleteReport');
    
        # agency page
        Route::get('/agency', 'DistrictController@agencyPage');
    
        # register agency
        Route::post('/agency', 'DistrictController@registerAgency');
    
        # edit agency
        Route::get('/edit/agency/{id}', 'DistrictController@editAgency');
    
        # update agency
        Route::post('/edit/agency/{id}', 'DistrictController@updateAgency');
    
        # delete agency
        Route::delete('/delete/agency', 'DistrictController@deleteAgency');
    
        # district head logout
        Route::post('/logout', 'DistrictController@logout');
    });
});

# municipal route grouping
Route::group(['municipal' => 'municipal'], function () {
    Route::prefix('municipal')->group(function () {

        # municipal head home
        Route::get('/home', 'MunicipalController@home');
    
        # municipal head profile
        Route::get('/profile', 'MunicipalController@profile');
    
        # update profile
        Route::post('/update/profile', 'MunicipalController@updateProfile');
    
        # update password 
        Route::post('/update/password', 'MunicipalController@updatePassword');
    
        # citizen page
        Route::get('/citizen', 'MunicipalController@citizenPage');
    
        # register citizen
        Route::post('/citizen', 'MunicipalController@registerCitizen');
    
        # manage citizens
        Route::get('/manage/citizens', 'MunicipalController@manageCitizens');
    
        # edit citizen
        Route::get('/edit/citizen/{id}', 'MunicipalController@editCitizen');
    
        # update citizen
        Route::post('/edit/citizen/{id}', 'MunicipalController@updateCitizen');
    
        # delete citizen
        Route::delete('/delete/citizen', 'MunicipalController@deleteCitizen');
    
        # reports page
        Route::get('/reports', 'MunicipalController@reportsPage');
    
        # report details
        Route::get('/report/details/{id}', 'MunicipalController@reportDetails');
    
        # approve report
        Route::post('/approve/report', 'MunicipalController@approveReport');
    
        # decline report
        Route::post('/decline/report', 'MunicipalController@declineReport');
    
        # delete
        Route::delete('/delete/report', 'MunicipalController@deleteReport');
    
        # municipal head logout
        Route::post('/logout', 'MunicipalController@logout');
    });
});

# agency route grouping
Route::group(['agency' => 'agency'], function () {
    Route::prefix('agency')->group(function () {
        # home
        Route::get('/home', 'AgencyController@home');
    
        # profile
        Route::get('/profile', 'AgencyController@profile');
    
        # update profile
        Route::post('/update/profile', 'AgencyController@updateProfile');
    
        # update password
        Route::post('/update/password', 'AgencyController@updatePassword');
        
        # report details
        Route::get('/report/details/{id}', 'AgencyController@reportDetails');
    
        # logout
        Route::post('/logout', 'AgencyController@logout');
    });
});