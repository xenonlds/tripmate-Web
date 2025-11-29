<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;

use App\Http\Controllers\Supabase\BusinessOwner\{
    DashboardController as OwnerDashboard,
    RoomController as OwnerRoom,
    ClosureController as ClosureController,
    LocationController as OwnerLocation,
    PromotionController as OwnerPromotion,
    StaffController as OwnerStaff
};

use App\Http\Controllers\Supabase\Staff\{
    DashboardController as StaffDashboard,
    RoomController as StaffRoom,
    LocationController as StaffLocation,
    PromotionController as StaffPromotion
};

use App\Http\Controllers\Supabase\Admin\AdminStaffController;
use App\Http\Controllers\Supabase\Admin\AdminMemberController;
use App\Http\Controllers\Supabase\Admin\AdminCommunityController;
use App\Http\Controllers\Supabase\Admin\AdminAnalyticsController; // ✅ NEW

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::post('/setSession', function (Request $request) {
    session([
        'user_id' => $request->input('user_id'),
        'role' => $request->input('role'),
        'name' => $request->input('name')
    ]);
    return response()->json(['success' => true]);
})->name('setSession');

Route::get('auth/login', function () {
    return view('auth.login');
})->name('login');

Route::prefix('/business_owner')->group(function () {
    Route::get('/dashboards', [OwnerDashboard::class, 'getOwnerDetails'])->middleware('role:owner');
    Route::get('/location', function () {
        return view('business_owner.location');
    })->middleware('role:owner');
    Route::get('/promotion', function () {
        return view('business_owner.promotion');
    })->middleware('role:owner');
    Route::get('/room', function () {
        return view('business_owner.room');
    })->middleware('role:owner');
    Route::get('/schedule', [ClosureController::class, 'index'])->middleware('role:owner');
    Route::get('/rooms_id', [ClosureController::class, 'getRooms'])->middleware('role:owner');
    Route::get('/closures', [ClosureController::class, 'getClosures'])->middleware('role:owner');
    Route::post('/closures/add', [ClosureController::class, 'addClosure'])->middleware('role:owner')->name('closures.add');
    Route::delete('/schedule/delete/{id}', [ClosureController::class, 'destroy'])->middleware('role:owner')->name('schedule.delete');
    Route::get('/staff', [OwnerStaff::class, 'index'])->middleware('role:owner');
    Route::post('/staff/store', [OwnerStaff::class, 'store'])->middleware('role:owner');
    Route::get('/staff/{staffId}', [OwnerStaff::class, 'show'])->middleware('role:owner');
    Route::post('/staff/update/{staffId}', [OwnerStaff::class, 'update'])->middleware('role:owner');
    Route::get('/room', [OwnerRoom::class, 'index'])->middleware('role:owner');
    Route::post('/room/store', [OwnerRoom::class, 'store'])->middleware('role:owner');
    Route::get('/room/{roomNo}/edit', [OwnerRoom::class, 'edit'])->middleware('role:owner')->name('owner.room.edit');
    Route::post('/room/{roomNo}/update', [OwnerRoom::class, 'updateRoom'])->middleware('role:owner')->name('owner.room.update');
    Route::get('/promotions', [OwnerPromotion::class, 'index'])->middleware('role:owner');
    Route::get('/promotions/generate', [OwnerPromotion::class, 'generateCode'])->middleware('role:owner');
    Route::post('/promotions/store', [OwnerPromotion::class, 'store'])->middleware('role:owner');
});

Route::prefix('/staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->middleware('role:staff');
});

Route::prefix('/admin')->middleware('role:admin')->group(function () {
    // Dashboard (Overview)
    Route::get('/dashboards', function () {
        $adminName = session('name', 'Admin User');
        $userId = session('user_id');
        $role = session('role');

        return view('admin.dashboards', compact('adminName', 'userId', 'role'));
    })->name('admin.dashboards');

    // ✅ NEW: Analytics JSON endpoint (for charts)
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');

    // ✅ NEW: Analytics PDF export endpoint
    Route::post('/analytics/export-pdf', [AdminAnalyticsController::class, 'exportPdf'])->name('admin.analytics.export');

    // Staff Management Routes - Using Controller
    Route::get('/StaffManagement', [AdminStaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/staff/create', [AdminStaffController::class, 'create'])->name('admin.staff.create');
    Route::post('/staff', [AdminStaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/staff/{userId}/edit', [AdminStaffController::class, 'edit'])->name('admin.staff.edit');
    Route::put('/staff/{userId}', [AdminStaffController::class, 'update'])->name('admin.staff.update');
    Route::delete('/staff/{userId}', [AdminStaffController::class, 'destroy'])->name('admin.staff.destroy');
    Route::post('/staff/{userId}/toggle-status', [AdminStaffController::class, 'toggleStatus'])->name('admin.staff.toggle-status');
    Route::get('/staff/search', [AdminStaffController::class, 'search'])->name('admin.staff.search');
    Route::get('/staff/{userId}', [AdminStaffController::class, 'show'])->name('admin.staff.show');

    // Member Management Routes - Using Controller
    Route::get('/MemberManagement', [AdminMemberController::class, 'index'])->name('admin.member.index');
    Route::get('/member/create', [AdminMemberController::class, 'create'])->name('admin.member.create');
    Route::post('/member', [AdminMemberController::class, 'store'])->name('admin.member.store');
    Route::get('/member/search', [AdminMemberController::class, 'search'])->name('admin.member.search');
    Route::get('/member/statistics', [AdminMemberController::class, 'statistics'])->name('admin.member.statistics');
    Route::get('/member/{touristId}/edit', [AdminMemberController::class, 'edit'])->name('admin.member.edit');
    Route::put('/member/{touristId}', [AdminMemberController::class, 'update'])->name('admin.member.update');
    Route::delete('/member/{touristId}', [AdminMemberController::class, 'destroy'])->name('admin.member.destroy');
    Route::post('/member/{touristId}/toggle-status', [AdminMemberController::class, 'toggleStatus'])->name('admin.member.toggle-status');
    Route::get('/member/{touristId}', [AdminMemberController::class, 'show'])->name('admin.member.show');

    // Community Management Routes - Using Controller
    Route::get('/CommunityManagement', [AdminCommunityController::class, 'index'])->name('admin.community.index');
    Route::get('/community/{postId}', [AdminCommunityController::class, 'show'])->name('admin.community.show');
    Route::post('/community/{postId}/status', [AdminCommunityController::class, 'updateStatus'])->name('admin.community.status');
    Route::delete('/community/{postId}', [AdminCommunityController::class, 'destroy'])->name('admin.community.destroy');
    Route::post('/community/report/{reportId}', [AdminCommunityController::class, 'updateReport'])->name('admin.community.report');

    // Business Management
    Route::get('/BusinessManagement', function () {
        $adminName = session('name', 'Admin User');
        return view('admin.BusinessManagement', compact('adminName'));
    })->name('admin.business.index');
});
