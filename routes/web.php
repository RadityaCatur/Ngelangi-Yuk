<?php

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::redirect('/login', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Services
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::resource('services', 'ServicesController');

    // Employees
    Route::delete('employees/destroy', 'EmployeesController@massDestroy')->name('employees.massDestroy');
    Route::post('employees/media', 'EmployeesController@storeMedia')->name('employees.storeMedia');
    Route::resource('employees', 'EmployeesController');

    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');
    Route::get('topup', 'TopUpController@showTopupPage')->name('topupPage');

    // Appointments
    Route::delete('appointments/destroy', 'AppointmentsController@massDestroy')->name('appointments.massDestroy');
    Route::resource('appointments', 'AppointmentsController');
    Route::post('appointments/{appointment}/join', 'AppointmentsController@join')->name('appointments.join');
    Route::delete('appointments/{appointment}/leave', 'AppointmentsController@leave')->name('appointments.leave');

    // // Appointments: Employee
    // Route::post('appointments/{appointment}/join', 'AppointmentsController@joinAsEmployee')->name('appointments.joinEmployee');

    //Appointments: Client
    // Route::post('appointments/{appointment}/join', 'AppointmentsClientsController@store')->name('appointments.clients.join');
    // Route::delete('appointments/{appointment}/leave', 'AppointmentsClientsController@leave')->name('appointments.clients.leave');

    //Calendar
    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('system-calendar/{date}', 'SystemCalendarController@showDateDetails')->name('systemCalendar.details');
});