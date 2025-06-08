<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Comments\Controllers\CommentController;

Route::middleware(['auth:sanctum'])->prefix('comments')->group(function () {
    Route::post('/', [CommentController::class, 'store']);
});
