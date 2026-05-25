<?php

use App\Helpers\Router;

// Route Publik
Router::get('/', 'HomeController@index');
Router::get('/events', 'HomeController@index');
Router::get('/event/{slug}', 'HomeController@detail');

// Route Autentikasi
Router::get('/login', 'AuthController@showLogin');
Router::post('/login', 'AuthController@login');
Router::get('/register', 'AuthController@showRegister');
Router::post('/register', 'AuthController@register');
Router::get('/verify-otp', 'AuthController@showVerifyOtp');
Router::post('/verify-otp', 'AuthController@processVerifyOtp');
Router::post('/resend-otp', 'AuthController@resendOtp');
Router::get('/logout', 'AuthController@logout');

// Route Dashboard
Router::get('/dashboard', 'DashboardController@index');
Router::get('/profile', 'DashboardController@profile');
Router::post('/profile/update', 'DashboardController@updateProfile');
Router::post('/event/{id}/daftar', 'PendaftaranController@daftar');
Router::get('/ticket/{kode_tiket}', 'PendaftaranController@ticket');
Router::get('/sertifikat/{kode_tiket}', 'PendaftaranController@sertifikat');

// Route Admin
Router::get('/admin', 'AdminController@index');
Router::get('/admin/events', 'AdminController@events');
Router::get('/admin/event/create', 'AdminController@createEvent');
Router::post('/admin/event/store', 'AdminController@storeEvent');
Router::get('/admin/event/edit/{id}', 'AdminController@editEvent');
Router::post('/admin/event/update/{id}', 'AdminController@updateEvent');
Router::post('/admin/event/delete/{id}', 'AdminController@deleteEvent');
Router::get('/admin/scan', 'AdminController@scan');
Router::post('/admin/scan/process', 'PendaftaranController@processScan');
Router::get('/admin/peserta', 'AdminController@peserta');
Router::get('/admin/export/peserta', 'AdminController@exportPeserta');
Router::get('/admin/export/attendance', 'AdminController@exportAttendance');
