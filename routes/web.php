<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::redirect('/admin/comments', '/admin/custom-dashboard');
