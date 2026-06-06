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

    // Locations
    Route::resource('locations', 'LocationsController');

    // Services
    Route::delete('services/destroy', 'ServicesController@massDestroy')->name('services.massDestroy');
    Route::resource('services', 'ServicesController');

    // Employees
    Route::get('employees/training-hours', 'TrainingHoursController@index')->name('employees.trainingHours');
    Route::delete('employees/destroy', 'EmployeesController@massDestroy')->name('employees.massDestroy');
    Route::post('employees/media', 'EmployeesController@storeMedia')->name('employees.storeMedia');
    Route::resource('employees', 'EmployeesController');

    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');
    Route::get('topup', 'TopUpController@showTopupPage')->name('topupPage');
    Route::get('my-report', 'ReportController@index')->name('my.report');
    Route::get('clients/{client}/reports', 'ReportController@showClientReports')->name('clients.reports');

    // Appointments
    Route::delete('appointments/destroy', 'AppointmentsController@massDestroy')->name('appointments.massDestroy');
    Route::resource('appointments', 'AppointmentsController');
    Route::post('appointments/{appointment}/join', 'AppointmentsController@join')->name('appointments.join');
    Route::post('appointments/duplicate', 'AppointmentsController@duplicate')->name('appointments.duplicate');
    Route::patch('appointments/{appointment}/report', 'AppointmentsController@updateReport')->name('appointments.updateReport');
    Route::delete('appointments/{appointment}/leave', 'AppointmentsController@leave')->name('appointments.leave');
    Route::get('appointments/{appointment}/client-reports', 'ReportController@showFromAppointment')->name('appointments.clientReports');
    Route::delete('appointments/{appointment}/admin-cancel', 'AppointmentsController@adminCancel')->name('appointments.adminCancel');

    // // Appointments: Employee
    // Route::post('appointments/{appointment}/join', 'AppointmentsController@joinAsEmployee')->name('appointments.joinEmployee');

    //Appointments: Client
    // Route::post('appointments/{appointment}/join', 'AppointmentsClientsController@store')->name('appointments.clients.join');
    // Route::delete('appointments/{appointment}/leave', 'AppointmentsClientsController@leave')->name('appointments.clients.leave');

    //Calendar
    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    Route::get('system-calendar/{date}', 'SystemCalendarController@showDateDetails')->name('systemCalendar.details');
});