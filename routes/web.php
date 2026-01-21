<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicGirlController;
use App\Http\Controllers\GirlPanelController;
use App\Http\Controllers\AdminGirlController;
use App\Http\Controllers\User\GirlController;
use App\Http\Controllers\User\WelcomeController;
use App\Http\Controllers\User\BuyCodeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;



// RUTA PRINCIPAL (WELCOME)
Route::get('/', [WelcomeController::class, 'index'])->name('user.welcome');

// GIRLS PUBLIC
Route::get('/girls', [GirlController::class, 'index'])->name('user.girls.index');
Route::get('/girls/{id}', [GirlController::class, 'show'])->name('user.girls.show');

// Comprar código
Route::post('/girls/{id}/buy', [BuyCodeController::class, 'buy'])->name('user.buy.code');

// Ver contenido privado
Route::get('/girls/{id}/private', [GirlController::class, 'private'])->name('user.girls.private');
Route::post('/girls/{id}/private', [GirlController::class, 'checkCode'])->name('user.girls.private');

// Contenido privado
Route::get('/girls/{id}/private-content', [GirlController::class, 'privateContent'])->name('user.girls.privateContent');



// -------------------------
// RUTAS DE ADMIN
// -------------------------

Route::middleware(['auth', 'admin_only'])->group(function () {

    Route::get('/admin/panel', [AdminGirlController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/chicas', function () {
        return view('admin.girls');
    })->name('admin.girls');

    Route::get('/admin/estadisticas', function () {
        return view('admin.stats');
    })->name('admin.stats');

    Route::get('/admin/notificaciones', function () {
        return view('admin.notifications');
    })->name('admin.notifications');

    // listado de chicas
    Route::get('/panel', [AdminGirlController::class, 'index'])
        ->name('panel');

   // listado de chicas (ADMIN)
Route::get('/admin/girls', [AdminGirlController::class, 'index'])
    ->name('admin.girls.index');

Route::get('/admin/girls/{user}', [AdminGirlController::class, 'show'])
    ->name('admin.girls.show');


    // eliminar chica
    Route::delete('/girls/{girl}', [AdminGirlController::class, 'destroy'])
        ->name('admin.girls.destroy');

    // eliminar contenido
    Route::delete('/girls/{girl}/photo-public', [AdminGirlController::class, 'deletePhotoPublic'])
        ->name('admin.girls.delete.photo_public');

    Route::delete('/girls/{girl}/photo-private/{index}', [AdminGirlController::class, 'deletePhotoPrivate'])
        ->name('admin.girls.delete.photo_private');

    Route::delete('/girls/{girl}/video-private', [AdminGirlController::class, 'deleteVideoPrivate'])
        ->name('admin.girls.delete.video_private');

    Route::delete('/girls/{girl}/description-public', [AdminGirlController::class, 'deleteDescriptionPublic'])
        ->name('admin.girls.delete.description_public');

    Route::delete('/girls/{girl}/description-private', [AdminGirlController::class, 'deleteDescriptionPrivate'])
        ->name('admin.girls.delete.description_private');
});


// -------------------------
// RUTAS DE CHICAS (panel)
// -------------------------

Route::middleware(['auth'])->group(function () {

    Route::get('/chica/panel', [GirlPanelController::class, 'index'])->name('girl.dashboard');

    Route::get('/chica/editar', [GirlPanelController::class, 'editPublic'])->name('chica.edit.public');
    Route::post('/chica/editar', [GirlPanelController::class, 'updatePublic'])->name('chica.update.public');

    Route::get('/chica/privado', [GirlPanelController::class, 'editPrivate'])->name('chica.edit.private');
    Route::post('/chica/privado', [GirlPanelController::class, 'updatePrivate'])->name('chica.update.private');

    // ELIMINAR FOTO PÚBLICA
    Route::delete('/chica/editar/foto-publica', [GirlPanelController::class, 'deletePhotoPublic'])
        ->name('chica.delete.photo_public');

    // ELIMINAR FOTO PRIVADA
    Route::delete('/chica/privado/foto/{index}', [GirlPanelController::class, 'deletePhotoPrivate'])
        ->name('chica.delete.photo_private');

    // ELIMINAR VIDEO PRIVADO
    Route::delete('/chica/privado/video', [GirlPanelController::class, 'deleteVideoPrivate'])
        ->name('chica.delete.video_private');
});


// -------------------------
// RUTA PUBLIC GIRL SHOW
// -------------------------
Route::get('/chica/{id}', [PublicGirlController::class, 'show'])->name('public.girl.show');



Route::get('/girls', [GirlController::class, 'index'])
    ->name('user.girls.index');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');



    Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');



// Login para chicas/admin
Route::get('/login', function () {
    return view('auth.inicio_sesion');
})->name('login');




Route::get('/force-login', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('force.login');

require __DIR__.'/auth.php';
