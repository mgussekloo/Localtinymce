<?php

Route::post('{resource}/{field}/file-upload', \Gusmanson\Localtinymce\UploadController::class)->name('localtinymce.upload');