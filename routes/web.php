<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static info pages
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/conditions-de-vente', [PageController::class, 'cgv'])->name('cgv');
Route::post('/contact', [PageController::class, 'contactSend'])->name('contact.send');

// Public Music/Concerts
Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');

// Public Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Public Venues
Route::resource('venues', VenueController::class)->only(['index', 'show']);

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/theme/toggle', [DashboardController::class, 'toggleTheme'])->name('theme.toggle');
    
    Route::get('/organizer/concerts', [ConcertController::class, 'manage'])->name('organizer.concerts.manage');
    Route::get('/organizer/concerts/{id}/stats', [DashboardController::class, 'concertStats'])->name('organizer.concert.stats');
    Route::get('/organizer/events/{id}/stats', [DashboardController::class, 'eventStats'])->name('organizer.event.stats');
    Route::get('/organizer/stands', [\App\Http\Controllers\StandBookingController::class, 'index'])->name('organizer.stands.index');
    Route::post('/organizer/stands/{booking}/status', [\App\Http\Controllers\StandBookingController::class, 'updateStatus'])->name('organizer.stands.status');
    
    Route::get('/concerts/create', [ConcertController::class, 'create'])->name('concerts.create');
    Route::post('/concerts', [ConcertController::class, 'store'])->name('concerts.store');
    Route::get('/concerts/{concert}/edit', [ConcertController::class, 'edit'])->name('concerts.edit');
    Route::put('/concerts/{concert}', [ConcertController::class, 'update'])->name('concerts.update');

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    
    Route::post('/concerts/{concert}/book', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{uuid}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/verify', [TicketController::class, 'verifyManual'])->name('tickets.verify');
    Route::post('/tickets/{ticket}/cancel', [TicketController::class, 'cancel'])->name('tickets.cancel');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/artists/{artist}/follow', [ProfileController::class, 'toggleFollow'])->name('artists.follow');
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/events', [AdminController::class, 'manageEvents'])->name('events');
        Route::post('/events/{type}/{id}/label', [AdminController::class, 'updateEventLabel'])->name('events.label');
        Route::post('/events/{type}/{id}/block', [AdminController::class, 'toggleEventBlock'])->name('events.block');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
        Route::post('/artists/{artist}/verify', [AdminController::class, 'verifyArtist'])->name('artists.verify');
    });
});

// Public Show Routes (Must be at the bottom to prevent catching /create and /edit routes)
Route::get('/concerts/{slug}', [ConcertController::class, 'show'])->name('concerts.show');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
