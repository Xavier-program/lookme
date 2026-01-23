<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicGirlController;
use App\Http\Controllers\GirlPanelController;
use App\Http\Controllers\AdminGirlController;
use App\Http\Controllers\User\GirlController;
use App\Http\Controllers\User\WelcomeController;
use App\Http\Controllers\User\BuyCodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminCodeController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/
Route::get('/', [WelcomeController::class, 'index'])->name('user.welcome');

/*
|--------------------------------------------------------------------------
| GIRLS – PÚBLICO
|--------------------------------------------------------------------------
*/
Route::get('/girls', [GirlController::class, 'index'])->name('user.girls.index');
Route::get('/girls/{id}', [GirlController::class, 'show'])->name('user.girls.show');

/*
|--------------------------------------------------------------------------
| COMPRA DE CÓDIGOS
|--------------------------------------------------------------------------
*/
Route::get('/girls/{id}/buy', [BuyCodeController::class, 'show'])
    ->name('user.buy.code.show');

Route::post('/girls/{id}/buy', [BuyCodeController::class, 'buy'])
    ->name('user.buy.code');

/*
|--------------------------------------------------------------------------
| CONTENIDO PRIVADO (USUARIO)
|--------------------------------------------------------------------------
*/
Route::get('/girls/{id}/private', [GirlController::class, 'private'])
    ->name('user.girls.private');

Route::post('/girls/{id}/private', [GirlController::class, 'checkCode'])
    ->name('user.girls.private');

Route::get('/girls/{id}/private-content', [GirlController::class, 'privateContent'])
    ->middleware('girl.access')
    ->name('user.girls.privateContent');

Route::post('/girls/{id}/check-code', [GirlController::class, 'checkCodeAjax']);

Route::get('/girls/{id}/full', [GirlController::class, 'fullProfile'])
    ->name('user.girls.full');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin_only'])->group(function () {

    Route::get('/admin/panel', [AdminGirlController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/panel', [AdminGirlController::class, 'index'])
        ->name('panel');

    Route::get('/admin/girls', [AdminGirlController::class, 'index'])
        ->name('admin.girls.index');

    Route::get('/admin/girls/{user}', [AdminGirlController::class, 'show'])
        ->name('admin.girls.show');

    Route::delete('/girls/{girl}', [AdminGirlController::class, 'destroy'])
        ->name('admin.girls.destroy');

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

    Route::post('/admin/generate-codes', [AdminGirlController::class, 'generateCodes'])
        ->name('admin.generate.codes');

    Route::post('/admin/codes/generate', [AdminCodeController::class, 'store'])
        ->name('admin.codes.generate');

    Route::get('/admin/codes', [AdminCodeController::class, 'index'])
        ->name('admin.codes.index');

    Route::get('/admin/codes/{batch}', [AdminCodeController::class, 'show'])
        ->name('admin.codes.show');
});

/*
|--------------------------------------------------------------------------
| PANEL DE CHICA (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {


    Route::get('/chica/panel', [GirlPanelController::class, 'index'])
        ->name('girl.dashboard');

    Route::get('/chica/editar', [GirlPanelController::class, 'editPublic'])
        ->name('chica.edit.public');

    Route::post('/chica/editar', [GirlPanelController::class, 'updatePublic'])
        ->name('chica.update.public');

    Route::get('/chica/privado', [GirlPanelController::class, 'editPrivate'])
        ->name('chica.edit.private');

    Route::post('/chica/privado', [GirlPanelController::class, 'updatePrivate'])
        ->name('chica.update.private');

    Route::delete('/chica/editar/foto-publica', [GirlPanelController::class, 'deletePhotoPublic'])
        ->name('chica.delete.photo_public');

    Route::delete('/chica/privado/foto/{index}', [GirlPanelController::class, 'deletePhotoPrivate'])
        ->name('chica.delete.photo_private');

    Route::delete('/chica/privado/video', [GirlPanelController::class, 'deleteVideoPrivate'])
        ->name('chica.delete.video_private');

    Route::get('/chica/historial-codigos', [GirlPanelController::class, 'history'])
        ->name('chica.history.codes');
});

/*
|--------------------------------------------------------------------------
| PERFIL PÚBLICO CHICA (SIEMPRE AL FINAL)
|--------------------------------------------------------------------------
*/
Route::get('/chica/{id}', [PublicGirlController::class, 'show'])
    ->name('chica.show');

Route::get('/girls/{id}/full', [PublicGirlController::class, 'showFull'])
    ->name('girls.full');

/*
|--------------------------------------------------------------------------
| PERFIL / DASHBOARD / AUTH
|--------------------------------------------------------------------------
*/
Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/login', function () {
    return view('auth.inicio_sesion');
})->name('login');

Route::get('/force-login', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('force.login');

/*
|--------------------------------------------------------------------------
| LEGALES
|--------------------------------------------------------------------------
*/
Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');

require __DIR__.'/auth.php';
