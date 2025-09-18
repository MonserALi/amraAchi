<?php
// Application Routes

// Home Page
$routes[''] = ['HomeController', 'index'];
$routes['home'] = ['HomeController', 'index'];

// Authentication
$routes['login'] = ['AuthController', 'login'];
$routes['register'] = ['AuthController', 'register'];
$routes['forgot-password'] = ['AuthController', 'forgotPassword'];
$routes['reset-password'] = ['AuthController', 'resetPassword'];
$routes['logout'] = ['AuthController', 'logout'];

// Dashboard
$routes['dashboard'] = ['DashboardController', 'index'];

// Hospitals
$routes['hospitals'] = ['HospitalController', 'index'];
$routes['hospitals/map'] = ['HospitalController', 'map'];
$routes['hospitals/details/(:num)'] = ['HospitalController', 'details'];

// Doctors
$routes['doctors'] = ['DoctorController', 'index'];
$routes['doctors/profile/(:num)'] = ['DoctorController', 'profile'];
$routes['doctors/search'] = ['DoctorController', 'search'];

// Appointments
$routes['appointments'] = ['AppointmentController', 'index'];
$routes['appointments/book'] = ['AppointmentController', 'book'];
$routes['appointments/manage'] = ['AppointmentController', 'manage'];
$routes['appointments/details/(:num)'] = ['AppointmentController', 'details'];
$routes['appointments/cancel/(:num)'] = ['AppointmentController', 'cancel'];

// Patients
$routes['patients'] = ['PatientController', 'index'];
$routes['patients/records'] = ['PatientController', 'records'];
$routes['patients/profile'] = ['PatientController', 'profile'];

// Nurses
$routes['nurses'] = ['NurseController', 'index'];
$routes['nurses/profile/(:num)'] = ['NurseController', 'profile'];
$routes['nurses/schedule'] = ['NurseController', 'schedule'];

// Chat
$routes['chat'] = ['ChatController', 'index'];
$routes['chat/messages/(:num)'] = ['ChatController', 'messages'];

// Emergency
$routes['sos'] = ['EmergencyController', 'sos'];
$routes['epidemic-alert'] = ['EmergencyController', 'epidemicAlert'];

// Search
$routes['search'] = ['SearchController', 'index'];

// About
$routes['about'] = ['HomeController', 'about'];

// Contact
$routes['contact'] = ['HomeController', 'contact'];

// API Routes
$routes['api/hospitals/nearby'] = ['ApiController', 'nearbyHospitals'];
$routes['api/doctors/search'] = ['ApiController', 'searchDoctors'];
$routes['api/appointments/book'] = ['ApiController', 'bookAppointment'];
$routes['api/chat/history'] = ['ApiController', 'chatHistory'];
$routes['api/chat/send'] = ['ApiController', 'sendMessage'];

// Error Pages
$routes['404'] = ['ErrorController', 'notFound'];
$routes['500'] = ['ErrorController', 'serverError'];
