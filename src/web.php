<?php

use Apps\Route;

Route::get("/", "MainController@indexPage");
Route::get("/store", "MainController@storePage");

// 유저 관리
Route::post("/sign-in", "UserController@signIn");
Route::post("/sign-up", "UserController@signUp");
Route::get("/logout", "UserController@logout");

// 온라인 집들이
Route::get("/online-party", "MainController@partyPage", "user");
Route::post("/knowhows", "MainController@writeKnowhow", "user");
Route::post("/knowhows/reviews", "MainController@reviewKnowhow", "user");

// 전문가
Route::get("/specialists", "UserController@specialistPage", "user");
Route::post("/specialists/reviews", "UserController@reviewUser", "user");
Route::get("/specialists/reviews", "UserController@getReviews", "user");

// 시공 견적
Route::get("/estimates", "MainController@estimatePage", "user");
Route::post("/estimates/requests", "MainController@writeRequest", "user");
Route::post("/estimates/responses", "MainController@writeResponse", "user");
Route::get("/estimates/view", "MainController@viewEstimates", "user");
Route::post("/estimates/pick", "MainController@pickEstimate", "user");

Route::connect();