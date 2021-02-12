<?php

use Arniro\Admin\Http\Controllers\PivotsController;
use Arniro\Admin\Http\Controllers\ResourcesController;
use Arniro\Admin\Http\Controllers\TiptapController;

Route::post('ui', 'UIController');

Route::get('resources/{resource}', [ResourcesController::class, 'index']);
Route::get('resources/{resource}/create', [ResourcesController::class, 'create']);
Route::get('resources/{resource}/{id}', [ResourcesController::class, 'show']);
Route::post('resources/{resource}', [ResourcesController::class, 'store']);
Route::get('resources/{resource}/{id}/edit', [ResourcesController::class, 'edit']);
Route::put('resources/{resource}/{id}', [ResourcesController::class, 'update']);
Route::delete('resources/{resource}/{id}', [ResourcesController::class, 'destroy']);

Route::get('search/{resource}', 'SearchController');
Route::post('resources/{resource}/reorder', 'ReorderController');

Route::get('resources/{resource}/{id}/attach/{relationship}', [PivotsController::class, 'create']);
Route::post('resources/{resource}/{id}/attach/{relationship}', [PivotsController::class, 'store']);
Route::get('resources/{resource}/{id}/attach/{relationship}/edit/{attachId}', [PivotsController::class, 'edit']);
Route::put('resources/{resource}/{id}/attach/{relationship}/edit/{attachId}', [PivotsController::class, 'update']);
Route::delete('resources/{resource}/{id}/detach/{relationship}/{attachId}', [PivotsController::class, 'destroy']);

Route::post('tiptap/files', [TiptapController::class, 'storeFile']);
