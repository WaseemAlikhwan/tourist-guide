<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\User\ReviewController as UserReviewController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\DestinationController as UserDestinationController;
use App\Http\Controllers\User\ActivityController as UserActivityController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContentProviderController;
use App\Http\Controllers\Admin\RecommendationInsightsController;
use App\Http\Controllers\User\RecommendationController;
use App\Http\Controllers\Provider\ActivityGalleryController;
use App\Http\Controllers\Provider\ApplicationStatusController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\ProviderStorefrontController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Routes للموقع الرئيسي (المستخدمين والزوار)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Routes للمصادقة (المستخدمين)
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);
Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserAuthController::class, 'register']);
Route::get('/auth/{provider}/redirect', [UserAuthController::class, 'redirectToProvider'])->name('auth.social.redirect');
Route::get('/auth/{provider}/callback', [UserAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');

// تسجيل مزوّدي المحتوى السياحي (نظام توثيق واعتماد)
Route::get('/provider/register', [UserAuthController::class, 'showProviderRegisterForm'])->name('provider.register');
Route::post('/provider/register', [UserAuthController::class, 'registerProvider'])->middleware('auth')->name('provider.register.submit');

Route::middleware(['auth'])->prefix('provider')->name('provider.')->group(function () {
    Route::get('/application-status', [ApplicationStatusController::class, 'show'])->name('application-status');

    Route::middleware(['approvedContentProvider'])->group(function () {
        Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [ProviderDashboardController::class, 'bookings'])->name('bookings.index');
        Route::get('/activities', [ProviderDashboardController::class, 'activities'])->name('activities.index');
        Route::get('/activities/create', [ProviderDashboardController::class, 'createActivity'])->name('activities.create');
        Route::post('/activities', [ProviderDashboardController::class, 'storeActivity'])->name('activities.store');
        Route::get('/activities/{activity}/edit', [ProviderDashboardController::class, 'editActivity'])->name('activities.edit');
        Route::put('/activities/{activity}', [ProviderDashboardController::class, 'updateActivity'])->name('activities.update');
        Route::delete('/activities/{activity}', [ProviderDashboardController::class, 'destroyActivity'])->name('activities.destroy');
        Route::post('/activities/{activity}/gallery', [ActivityGalleryController::class, 'store'])->name('activities.gallery.store');
        Route::delete('/activities/{activity}/gallery/{gallery}', [ActivityGalleryController::class, 'destroy'])->name('activities.gallery.destroy');
        Route::post('/activities/{activity}/gallery/reorder', [ActivityGalleryController::class, 'reorder'])->name('activities.gallery.reorder');
        Route::get('/export/bookings.csv', [ProviderDashboardController::class, 'exportBookingsCsv'])->name('export.bookings');
        Route::get('/export/earnings.csv', [ProviderDashboardController::class, 'exportEarningsCsv'])->name('export.earnings');
        Route::get('/account', [ProviderDashboardController::class, 'account'])->name('account');
        Route::post('/account', [ProviderDashboardController::class, 'updateAccount'])->name('account.update');
        Route::get('/notifications', [ProviderDashboardController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [ProviderDashboardController::class, 'markNotificationAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [ProviderDashboardController::class, 'markAllNotificationsAsRead'])->name('notifications.read-all');
    });
});

Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

// Routes للوجهات (عامة)
Route::get('/destinations', [UserDestinationController::class, 'index'])->name('destinations.index');
// يجب وضع routes الثابتة قبل routes المعاملات لتجنب التضارب
Route::get('/destinations/compare', [UserDestinationController::class, 'compare'])->name('destinations.compare');
Route::post('/destinations/generate-itinerary', [UserDestinationController::class, 'generateItinerary'])->name('destinations.generate-itinerary');
Route::get('/destinations/{destination}', [UserDestinationController::class, 'show'])->name('destinations.show');

// Routes للطقس
Route::get('/destinations/{destination}/weather', [\App\Http\Controllers\WeatherController::class, 'getWeather'])->name('destinations.weather');
Route::post('/destinations/{destination}/weather/update', [\App\Http\Controllers\WeatherController::class, 'updateWeather'])->name('destinations.weather.update');

// Routes للأنشطة (عامة)
Route::get('/activities', [UserActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [UserActivityController::class, 'show'])->name('activities.show');
Route::get('/activities/{activity}/recommendations', [RecommendationController::class, 'byActivity'])->name('activities.recommendations');
Route::get('/partners', [ProviderStorefrontController::class, 'index'])->name('partners.index');
Route::get('/p/{user}', [ProviderStorefrontController::class, 'show'])->name('providers.storefront');

// Routes للفنادق (عامة)
Route::get('/hotels/{hotel}', [\App\Http\Controllers\User\HotelController::class, 'show'])->name('hotels.show');

// Routes للتواصل
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Routes لأساسيات السفر
Route::get('/travel-basics', [\App\Http\Controllers\TravelBasicsController::class, 'index'])->name('travel-basics.index');
Route::get('/travel-basics/{travelBasic}', [\App\Http\Controllers\TravelBasicsController::class, 'show'])->name('travel-basics.show');

// Routes لتقويم الفعاليات
Route::get('/events/calendar', [\App\Http\Controllers\EventController::class, 'calendar'])->name('events.calendar');
Route::get('/api/events', [\App\Http\Controllers\EventController::class, 'getEvents'])->name('api.events');

// Routes للخريطة التفاعلية
Route::get('/interactive-map', [\App\Http\Controllers\InteractiveMapController::class, 'index'])->name('interactive-map');

// Routes للتقييمات والمفضلة (يحتاج تسجيل دخول)
Route::middleware(['auth'])->group(function () {
    // التقييمات (تقييم واحد فقط)
    Route::post('/activities/{activity}/reviews', [UserReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [UserReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [UserReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // التعليقات (متعددة)
    Route::post('/activities/{activity}/comments', [\App\Http\Controllers\User\CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [\App\Http\Controllers\User\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\User\CommentController::class, 'destroy'])->name('comments.destroy');
    
    // المفضلة
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    
    // الحجوزات
    Route::get('/bookings', [UserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/activities/{activity}/book', [UserBookingController::class, 'create'])->name('bookings.create');
    Route::post('/activities/{activity}/book', [UserBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [UserBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [UserBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/validate-coupon', [UserBookingController::class, 'validateCoupon'])->name('bookings.validate-coupon');
    
    // الإشعارات
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['isAdmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Routes للوجهات
        Route::resource('destinations', DestinationController::class)->names([
            'index' => 'admin.destinations.index',
            'create' => 'admin.destinations.create',
            'store' => 'admin.destinations.store',
            'show' => 'admin.destinations.show',
            'edit' => 'admin.destinations.edit',
            'update' => 'admin.destinations.update',
            'destroy' => 'admin.destinations.destroy',
        ]);

        // Routes للأنشطة
        Route::resource('activities', ActivityController::class)->names([
            'index' => 'admin.activities.index',
            'create' => 'admin.activities.create',
            'store' => 'admin.activities.store',
            'show' => 'admin.activities.show',
            'edit' => 'admin.activities.edit',
            'update' => 'admin.activities.update',
            'destroy' => 'admin.activities.destroy',
        ]);

        // Routes للفنادق
        Route::resource('hotels', HotelController::class)->names([
            'index' => 'admin.hotels.index',
            'create' => 'admin.hotels.create', 
            'store' => 'admin.hotels.store',
            'show' => 'admin.hotels.show',
            'edit' => 'admin.hotels.edit',
            'update' => 'admin.hotels.update',
            'destroy' => 'admin.hotels.destroy',
        ]);

        // إدارة التقييمات (تقييم واحد فقط)
        Route::resource('reviews', AdminReviewController::class)->only(['update', 'destroy'])->names([
            'update' => 'admin.reviews.update',
            'destroy' => 'admin.reviews.destroy',
        ]);
        Route::post('/reviews/{review}/toggle-approval', [AdminReviewController::class, 'toggleApproval'])->name('admin.reviews.toggle-approval');

        // إدارة التعليقات (متعددة)
        Route::get('/comments', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('admin.comments.index');
        Route::put('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'update'])->name('admin.comments.update');
        Route::post('/comments/{comment}/toggle-approval', [\App\Http\Controllers\Admin\CommentController::class, 'toggleApproval'])->name('admin.comments.toggle-approval');
        Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('admin.comments.destroy');

        // إدارة الحجوزات
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
        Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
        Route::post('/bookings/{booking}/confirm-payment', [AdminBookingController::class, 'confirmPayment'])->name('admin.bookings.confirm-payment');
        Route::post('/bookings/{booking}/cancel-unpaid', [AdminBookingController::class, 'cancelUnpaid'])->name('admin.bookings.cancel-unpaid');
        Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('admin.bookings.destroy');
        Route::get('/recommendations/insights', [RecommendationInsightsController::class, 'index'])->name('admin.recommendations.insights');

        // إدارة الكوبونات
        Route::resource('coupons', CouponController::class)->names([
            'index' => 'admin.coupons.index',
            'create' => 'admin.coupons.create',
            'store' => 'admin.coupons.store',
            'show' => 'admin.coupons.show',
            'edit' => 'admin.coupons.edit',
            'update' => 'admin.coupons.update',
            'destroy' => 'admin.coupons.destroy',
        ]);
        Route::post('/coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('admin.coupons.toggle-status');


        // إدارة معرض الصور
        Route::post('/gallery', [GalleryController::class, 'store'])->name('admin.gallery.store');
        Route::put('/gallery/{gallery}', [GalleryController::class, 'update'])->name('admin.gallery.update');
        Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('admin.gallery.destroy');

        // إدارة رسائل التواصل
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
        Route::post('/contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('admin.contacts.update-status');
        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');

        // إدارة أساسيات السفر
        Route::resource('travel-basics', \App\Http\Controllers\Admin\TravelBasicController::class)->names([
            'index' => 'admin.travel-basics.index',
            'create' => 'admin.travel-basics.create',
            'store' => 'admin.travel-basics.store',
            'show' => 'admin.travel-basics.show',
            'edit' => 'admin.travel-basics.edit',
            'update' => 'admin.travel-basics.update',
            'destroy' => 'admin.travel-basics.destroy',
        ]);

        // إدارة المستخدمين
        Route::resource('users', UserController::class)->names([
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);

        // إدارة طلبات مزوّدي المحتوى السياحي
        Route::get('/content-providers', [ContentProviderController::class, 'index'])->name('admin.content-providers.index');
        Route::get('/content-providers/{application}', [ContentProviderController::class, 'show'])->name('admin.content-providers.show');
        Route::get('/content-providers/{application}/documents/{document}', [ContentProviderController::class, 'viewDocument'])->name('admin.content-providers.documents.view');
        Route::post('/content-providers/{application}/approve', [ContentProviderController::class, 'approve'])->name('admin.content-providers.approve');
        Route::post('/content-providers/{application}/reject', [ContentProviderController::class, 'reject'])->name('admin.content-providers.reject');

        Route::get('/providers/{user}', [ContentProviderController::class, 'showProvider'])->name('admin.providers.show');
        Route::patch('/providers/{user}', [ContentProviderController::class, 'updateProvider'])->name('admin.providers.update');
        Route::post('/providers/{user}/toggle-active', [ContentProviderController::class, 'toggleActive'])->name('admin.providers.toggle-active');
    });
});

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);
    session(['locale' => $locale]);
    app()->setLocale($locale);
    return back();
})->name('locale.switch');