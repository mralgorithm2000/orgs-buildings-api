<?php
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\ActivityController;

// Organization APIs
Route::get('organizations/{id}', [OrganizationController::class, 'show']);
Route::get('organizations/building/{buildingId}', [OrganizationController::class, 'getByBuilding']);
Route::get('organizations/activity/{activityId}', [OrganizationController::class, 'getByActivity']);
Route::get('organizations/activity-tree/{activityId}', [OrganizationController::class, 'getByActivityTree']);
Route::get('organizations/search/name', [OrganizationController::class, 'searchByName']);
Route::get('organizations/search/nearby', [OrganizationController::class, 'getNearby']); // Pass coordinates and radius

// Building APIs
Route::get('buildings', [BuildingController::class, 'index']);
