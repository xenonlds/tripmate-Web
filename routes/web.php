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
use App\Http\Controllers\Supabase\Admin\MemberDetailsController;
use App\Http\Controllers\Supabase\Admin\AdminCommunityController;
use App\Http\Controllers\Supabase\Admin\AdminAnalyticsController;

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

// Logout route
Route::get('/logout', function () {
    session()->flush();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login')->with('success', 'You have been logged out successfully.');
})->name('logout');

// ============================================
// BUSINESS OWNER ROUTES
// ============================================
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

// ============================================
// STAFF ROUTES
// ============================================
Route::prefix('/staff')->group(function () {
    Route::get('/dashboard', function () {
        return view('staff.dashboard');
    })->middleware('role:staff');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('/admin')->middleware('role:admin')->group(function () {
    // Dashboard (Overview)
    Route::get('/dashboards', function () {
        $adminName = session('name', 'Admin User');
        $userId = session('user_id');
        $role = session('role');
        return view('admin.dashboards', compact('adminName', 'userId', 'role'));
    })->name('admin.dashboards');

    // Analytics
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
    Route::post('/analytics/export-pdf', [AdminAnalyticsController::class, 'exportPdf'])->name('admin.analytics.export');

    // ============================================
    // MEMBER MANAGEMENT ROUTES
    // ============================================

    // Member List & Create
    Route::get('/MemberManagement', [AdminMemberController::class, 'index'])->name('admin.member.index');
    Route::get('/member/create', [AdminMemberController::class, 'create'])->name('admin.member.create');
    Route::post('/member', [AdminMemberController::class, 'store'])->name('admin.member.store');

    // Member Search & Statistics (must be before {touristId} routes)
    Route::get('/member/search', [AdminMemberController::class, 'search'])->name('admin.member.search');
    Route::get('/member/statistics', [AdminMemberController::class, 'statistics'])->name('admin.member.statistics');

    // Member Details & Export (must be before edit route)
    Route::get('/member/{touristId}', [MemberDetailsController::class, 'show'])->name('admin.member.show');
    Route::get('/member/{touristId}/export', [MemberDetailsController::class, 'exportMemberData'])->name('admin.member.export');

    // Member Edit & Update
    Route::get('/member/{touristId}/edit', [AdminMemberController::class, 'edit'])->name('admin.member.edit');
    Route::put('/member/{touristId}', [AdminMemberController::class, 'update'])->name('admin.member.update');

    // Member Status & Delete
    Route::delete('/member/{touristId}', [AdminMemberController::class, 'destroy'])->name('admin.member.destroy');
    Route::post('/member/{touristId}/toggle-status', [AdminMemberController::class, 'toggleStatus'])->name('admin.member.toggle-status');

    // Post Management within Member Details
    Route::post('/member/post/{postId}/toggle-visibility', [MemberDetailsController::class, 'togglePostVisibility'])->name('admin.member.post.toggle-visibility');
    Route::delete('/member/post/{postId}/delete', [MemberDetailsController::class, 'deletePost'])->name('admin.member.post.delete');

    // ============================================
    // COMMUNITY MANAGEMENT ROUTES
    // ============================================

    // Main page
    Route::get('/CommunityManagement', [AdminCommunityController::class, 'index'])
        ->name('admin.community.index');

    // Create new post as admin
    Route::get('/community/create', [AdminCommunityController::class, 'create'])
        ->name('admin.community.create');
    Route::post('/community/create', [AdminCommunityController::class, 'store'])
        ->name('admin.community.store');

    // Content CRUD (put specific routes before dynamic {postId})
    Route::get('/community/users/blocked', [AdminCommunityController::class, 'getBlockedUsers'])
        ->name('admin.community.blockedUsers');

    Route::get('/community/{postId}', [AdminCommunityController::class, 'show'])
        ->name('admin.community.show');
    Route::put('/community/{postId}', [AdminCommunityController::class, 'update'])
        ->name('admin.community.update');
    Route::put('/community/{postId}/status', [AdminCommunityController::class, 'updateStatus'])
        ->name('admin.community.updateStatus');
    Route::delete('/community/{postId}', [AdminCommunityController::class, 'destroy'])
        ->name('admin.community.destroy');

    // User Blocking
    Route::post('/community/user/block', [AdminCommunityController::class, 'blockUser'])
        ->name('admin.community.blockUser');
    Route::post('/community/user/unblock', [AdminCommunityController::class, 'unblockUser'])
        ->name('admin.community.unblockUser');

    // Warning System
    Route::post('/community/warning/issue', [AdminCommunityController::class, 'issueWarning'])
        ->name('admin.community.issueWarning');
    Route::get('/community/post/{postId}/warnings', [AdminCommunityController::class, 'getPostWarnings'])
        ->name('admin.community.postWarnings');
    Route::get('/community/user/{touristId}/warnings', [AdminCommunityController::class, 'getUserWarnings'])
        ->name('admin.community.userWarnings');
    Route::post('/community/warning/{warningId}/dismiss', [AdminCommunityController::class, 'dismissWarning'])
        ->name('admin.community.dismissWarning');

    // Report Management
    Route::post('/community/report/{reportId}', [AdminCommunityController::class, 'updateReport'])
        ->name('admin.community.updateReport');

    // Business Management
    Route::get('/BusinessManagement', function () {
        $adminName = session('name', 'Admin User');
        return view('admin.BusinessManagement', compact('adminName'));
    })->name('admin.business.index');
});
