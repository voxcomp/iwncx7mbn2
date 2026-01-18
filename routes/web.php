<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SponsorsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('donate/cause/{cause}', [DonateController::class, 'pageCause'])->name('donate.cause');
Route::get('donate/{event?}/{registrant?}', [DonateController::class, 'page'])->name('donate');
Route::get('donate/team/{event}/{team}', [DonateController::class, 'teamPage'])->name('donate.team');
Route::get('donate/subscription/cancel/{subscription_id}', [DonateController::class, 'cancelSubscription'])->name('donate.cancel');
Route::post('donate', [DonateController::class, 'donate'])->name('donate.post');
Route::get('/', [HomeController::class, 'index'])->name('front');
Route::get('unauthorized', [HomeController::class, 'unauthorized'])->name('unauthorized');

Route::get('promise-wall', [HomeController::class, 'pagePromise'])->name('page.promise');
Route::get('promise-wall/donate/confirm', [HomeController::class, 'pagePromiseConfirm'])->name('donate.promise.confirm');
Route::get('promise-wall/donate', [DonateController::class, 'pagePromise'])->name('donate.promise');
Route::post('promise-wall/donate', [DonateController::class, 'donatePromise'])->name('donate.promise.post');

// ajax pages

Route::post('ajax/user/{id}/{email}', [DataController::class, 'isUserEmail']);
Route::post('ajax/username/{username}', [DataController::class, 'isUniqueUsername']);
Route::post('ajax/team/{event}/{name}', [DataController::class, 'isUniqueTeam']);
Route::post('ajax/profile/photo', [DataController::class, 'profilePhoto'])->name('data.profilePhoto');
Route::post('ajax/profile/cropped', [DataController::class, 'profileCroppedPhoto']);
Route::get('ajax/profile/cropped', [DataController::class, 'profileCroppedPhotodead']);
Route::post('ajax/personalpage/fail/{registrant}', [DataController::class, 'personalpageFail'])->name('admin.personalpage.fail')->middleware('admin');
Route::post('ajax/personalpage/pass/{registrant}', [DataController::class, 'personalpagePass'])->name('admin.personalpage.pass')->middleware('admin');
Route::post('ajax/teampage/fail/{team}', [DataController::class, 'teampageFail'])->name('admin.teampage.fail')->middleware('admin');
Route::post('ajax/teampage/pass/{team}', [DataController::class, 'teampagePass'])->name('admin.teampage.pass')->middleware('admin');
Route::post('ajax/coupon/{coupon}/{amount}', [DataController::class, 'coupon']);

// user pages

Route::get('home', [UsersController::class, 'home'])->name('home');
Route::get('user/validate/{token}', [Auth\RegisterController::class, 'validateUser'])->name('user.validate');
Route::post('user/validate/resend', [Auth\RegisterController::class, 'resendValidation']);
Route::get('user/validate', [Auth\RegisterController::class, 'needValidation']);
Route::get('user/profile/{user}', [UsersController::class, 'profile'])->name('user.profile');
Route::get('account', [UsersController::class, 'profile'])->name('user.account.profile');
Route::patch('user/save', [UsersController::class, 'update'])->name('user.update');
Route::post('user/delete/{user}', [UsersController::class, 'delete'])->name('user.delete');
Route::get('user/create', [UsersController::class, 'create'])->name('user.create')->middleware('admin');
Route::post('user/save', [UsersController::class, 'save'])->name('user.save')->middleware('admin');
Route::get('user/search', [UsersController::class, 'search'])->name('user.search.page')->middleware('admin');
Route::post('user/search', [UsersController::class, 'searchResult'])->name('user.search')->middleware('admin');

// event pages

Route::get('events', [EventsController::class, 'all'])->name('event.list');
Route::get('event/{event}', [EventsController::class, 'view'])->name('event.view');
Route::get('event/personal/{event}/{registrant}', [EventsController::class, 'personalView'])->name('event.view.personal');
Route::get('event/team/{event}/{team}', [EventsController::class, 'teampageView'])->name('event.view.team');
Route::get('event/page/list/{event}', [EventsController::class, 'pageList'])->name('event.page.list');
Route::get('event/manage/list', [EventsController::class, 'all'])->name('event.list')->middleware('admin');
Route::get('event/manage/edit', [EventsController::class, 'create'])->name('event.create')->middleware('admin');
Route::post('event/manage/edit', [EventsController::class, 'save'])->name('event.save')->middleware('admin');
Route::get('event/manage/edit/{event}', [EventsController::class, 'edit'])->name('event.edit')->middleware('admin');
Route::patch('event/manage/edit/{event}', [EventsController::class, 'update'])->name('event.update')->middleware('admin');
Route::post('event/manage/delete/{event}', [EventsController::class, 'delete'])->name('event.delete')->middleware('admin');
Route::get('register/{event}', [EventsController::class, 'register'])->name('event.register');
Route::post('register/{event}', [EventsController::class, 'registerStep1'])->name('event.register.step1');
Route::patch('register/{event}', [EventsController::class, 'registerStep2'])->name('event.register.step2');
Route::post('register/pay/{event}', [EventsController::class, 'registerPay'])->name('event.register.pay');
Route::get('register/pay/confirmation/{event}/{registrant}/{team}/{donation}/{user}', [EventsController::class, 'registerConfirm'])->name('event.register.confirm');
Route::post('event/team/join/{event}/{registrant}/{route?}', [EventsController::class, 'teamJoin'])->name('event.join.team');
Route::post('event/team/leave/{event}/{registrant}', [EventsController::class, 'teamLeave'])->name('event.leave.team');
Route::post('event/personalpage/save/{registrant}', [EventsController::class, 'personalPage'])->name('event.personal.page');
Route::post('event/teampage/save/{team}', [EventsController::class, 'teamPage'])->name('event.team.page');
Route::get('registrant/search', [EventsController::class, 'registrantSearch'])->name('registrant.search.page')->middleware('admin');
Route::post('registrant/search', [EventsController::class, 'registrantSearchResult'])->name('registrant.search')->middleware('admin');

// admin pages

Route::get('admin/personalpage', [AdminController::class, 'personalpageReview'])->name('admin.personalpage.review')->middleware('admin');
Route::get('admin/coupons', [AdminController::class, 'coupons'])->name('coupons');
Route::get('admin/coupons/{coupon}', [AdminController::class, 'coupons'])->name('coupons.edit');
Route::patch('admin/coupons', [AdminController::class, 'couponSave'])->name('coupons.save');
Route::post('admin/coupons', [AdminController::class, 'couponCreate'])->name('coupons.create');
Route::post('admin/coupons/{coupon}', [AdminController::class, 'couponDelete'])->name('coupons.delete');
Route::get('registrant/view/{registrant}', [AdminController::class, 'registrantView'])->name('registrant.view');
Route::get('admin/registrant/{registrant}', [AdminController::class, 'registrantEdit'])->name('admin.registrant.edit');
Route::post('admin/registrant/{registrant}', [AdminController::class, 'registrantSave'])->name('admin.registrant.save');
Route::get('admin/donations', [AdminController::class, 'donations'])->name('admin.donations');
Route::get('admin/donation/{donation}', [AdminController::class, 'donationEdit'])->name('admin.donation.edit');
Route::post('admin/donation/{donation}', [AdminController::class, 'donationSave'])->name('admin.donation.save');
Route::post('admin/donation/delete/{donation}', [AdminController::class, 'donationDelete'])->name('admin.donation.delete');

// volunteer pages

Route::get('volunteer/{event}', [EventsController::class, 'volunteer'])->name('volunteer');
Route::post('volunteer/{event}', [EventsController::class, 'volunteerSubmission'])->name('volunteer.submission');

// sponsor pages

Route::get('sponsor/{event}', [SponsorsController::class, 'sponsor'])->name('sponsor');
Route::post('sponsor/{event}', [SponsorsController::class, 'saveSubmission'])->name('sponsor.submission');
Route::patch('sponsor/{event}', [SponsorsController::class, 'payment'])->name('sponsor.payment');

Route::get('event/sponsor/{event}', [SponsorsController::class, 'eventSponsors'])->name('event.sponsor')->middleware('admin');
Route::post('event/sponsor/{event}', [SponsorsController::class, 'eventSponsorAdd'])->name('event.sponsor.add')->middleware('admin');
Route::post('event/sponsor/delete/{sponsor}', [SponsorsController::class, 'eventSponsorDelete'])->name('event.sponsor.delete')->middleware('admin');

// reporting

Route::get('reports', [ReportsController::class, 'reports'])->name('reports');
Route::post('reports', [ReportsController::class, 'getReports'])->name('get.report');
Route::post('general-reports', [ReportsController::class, 'getGeneralReports'])->name('get.generalreport');

// authentication routes
Auth::routes();

Route::get('test', [HomeController::class, 'test'])->name('test');
