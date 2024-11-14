<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ["Server" => "Server starting......"];
});
