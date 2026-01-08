<?php

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

Route::get('donate/cause/{cause}', 'DonateController@pageCause')->name('donate.cause');
Route::get('donate/{event?}/{registrant?}', 'DonateController@page')->name('donate');
Route::get('donate/team/{event}/{team}', 'DonateController@teamPage')->name('donate.team');
Route::get('donate/subscription/cancel/{subscription_id}', 'DonateController@cancelSubscription')->name('donate.cancel');
Route::post('donate', 'DonateController@donate')->name('donate.post');
Route::get('/', 'HomeController@index')->name('front');
Route::get('unauthorized', 'HomeController@unauthorized')->name('unauthorized');

Route::get('promise-wall', 'HomeController@pagePromise')->name('page.promise');
Route::get('promise-wall/donate/confirm', 'HomeController@pagePromiseConfirm')->name('donate.promise.confirm');
Route::get('promise-wall/donate', 'DonateController@pagePromise')->name('donate.promise');
Route::post('promise-wall/donate', 'DonateController@donatePromise')->name('donate.promise.post');

// ajax pages

Route::post('ajax/user/{id}/{email}', 'DataController@isUserEmail');
Route::post('ajax/username/{username}', 'DataController@isUniqueUsername');
Route::post('ajax/team/{event}/{name}', 'DataController@isUniqueTeam');
Route::post('ajax/profile/photo', 'DataController@profilePhoto')->name('data.profilePhoto');
Route::post('ajax/profile/cropped', 'DataController@profileCroppedPhoto');
Route::get('ajax/profile/cropped', 'DataController@profileCroppedPhotodead');
Route::post('ajax/personalpage/fail/{registrant}', 'DataController@personalpageFail')->name('admin.personalpage.fail')->middleware('admin');
Route::post('ajax/personalpage/pass/{registrant}', 'DataController@personalpagePass')->name('admin.personalpage.pass')->middleware('admin');
Route::post('ajax/teampage/fail/{team}', 'DataController@teampageFail')->name('admin.teampage.fail')->middleware('admin');
Route::post('ajax/teampage/pass/{team}', 'DataController@teampagePass')->name('admin.teampage.pass')->middleware('admin');
Route::post('ajax/coupon/{coupon}/{amount}', 'DataController@coupon');

// user pages

Route::get('home', 'UsersController@home')->name('home');
Route::get('user/validate/{token}', 'Auth\RegisterController@validateUser')->name('user.validate');
Route::post('user/validate/resend', 'Auth\RegisterController@resendValidation');
Route::get('user/validate', 'Auth\RegisterController@needValidation');
Route::get('user/profile/{user}', 'UsersController@profile')->name('user.profile');
Route::get('account', 'UsersController@profile')->name('user.account.profile');
Route::patch('user/save', 'UsersController@update')->name('user.update');
Route::post('user/delete/{user}', 'UsersController@delete')->name('user.delete');
Route::get('user/create', 'UsersController@create')->name('user.create')->middleware('admin');
Route::post('user/save', 'UsersController@save')->name('user.save')->middleware('admin');
Route::get('user/search', 'UsersController@search')->name('user.search.page')->middleware('admin');
Route::post('user/search', 'UsersController@searchResult')->name('user.search')->middleware('admin');

// event pages

Route::get('events', 'EventsController@all')->name('event.list');
Route::get('event/{event}', 'EventsController@view')->name('event.view');
Route::get('event/personal/{event}/{registrant}', 'EventsController@personalView')->name('event.view.personal');
Route::get('event/team/{event}/{team}', 'EventsController@teampageView')->name('event.view.team');
Route::get('event/page/list/{event}', 'EventsController@pageList')->name('event.page.list');
Route::get('event/manage/list', 'EventsController@all')->name('event.list')->middleware('admin');
Route::get('event/manage/edit', 'EventsController@create')->name('event.create')->middleware('admin');
Route::post('event/manage/edit', 'EventsController@save')->name('event.save')->middleware('admin');
Route::get('event/manage/edit/{event}', 'EventsController@edit')->name('event.edit')->middleware('admin');
Route::patch('event/manage/edit/{event}', 'EventsController@update')->name('event.update')->middleware('admin');
Route::post('event/manage/delete/{event}', 'EventsController@delete')->name('event.delete')->middleware('admin');
Route::get('register/{event}', 'EventsController@register')->name('event.register');
Route::post('register/{event}', 'EventsController@registerStep1')->name('event.register.step1');
Route::patch('register/{event}', 'EventsController@registerStep2')->name('event.register.step2');
Route::post('register/pay/{event}', 'EventsController@registerPay')->name('event.register.pay');
Route::get('register/pay/confirmation/{event}/{registrant}/{team}/{donation}/{user}', 'EventsController@registerConfirm')->name('event.register.confirm');
Route::post('event/team/join/{event}/{registrant}/{route?}', 'EventsController@teamJoin')->name('event.join.team');
Route::post('event/team/leave/{event}/{registrant}', 'EventsController@teamLeave')->name('event.leave.team');
Route::post('event/personalpage/save/{registrant}', 'EventsController@personalPage')->name('event.personal.page');
Route::post('event/teampage/save/{team}', 'EventsController@teamPage')->name('event.team.page');
Route::get('registrant/search', 'EventsController@registrantSearch')->name('registrant.search.page')->middleware('admin');
Route::post('registrant/search', 'EventsController@registrantSearchResult')->name('registrant.search')->middleware('admin');

// admin pages

Route::get('admin/personalpage', 'AdminController@personalpageReview')->name('admin.personalpage.review')->middleware('admin');
Route::get('admin/coupons', 'AdminController@coupons')->name('coupons');
Route::get('admin/coupons/{coupon}', 'AdminController@coupons')->name('coupons.edit');
Route::patch('admin/coupons', 'AdminController@couponSave')->name('coupons.save');
Route::post('admin/coupons', 'AdminController@couponCreate')->name('coupons.create');
Route::post('admin/coupons/{coupon}', 'AdminController@couponDelete')->name('coupons.delete');
Route::get('registrant/view/{registrant}', 'AdminController@registrantView')->name('registrant.view');
Route::get('admin/registrant/{registrant}', 'AdminController@registrantEdit')->name('admin.registrant.edit');
Route::post('admin/registrant/{registrant}', 'AdminController@registrantSave')->name('admin.registrant.save');
Route::get('admin/donations', 'AdminController@donations')->name('admin.donations');
Route::get('admin/donation/{donation}', 'AdminController@donationEdit')->name('admin.donation.edit');
Route::post('admin/donation/{donation}', 'AdminController@donationSave')->name('admin.donation.save');
Route::post('admin/donation/delete/{donation}', 'AdminController@donationDelete')->name('admin.donation.delete');

// volunteer pages

Route::get('volunteer/{event}', 'EventsController@volunteer')->name('volunteer');
Route::post('volunteer/{event}', 'EventsController@volunteerSubmission')->name('volunteer.submission');

// sponsor pages

Route::get('sponsor/{event}', 'SponsorsController@sponsor')->name('sponsor');
Route::post('sponsor/{event}', 'SponsorsController@saveSubmission')->name('sponsor.submission');
Route::patch('sponsor/{event}', 'SponsorsController@payment')->name('sponsor.payment');

Route::get('event/sponsor/{event}', 'SponsorsController@eventSponsors')->name('event.sponsor')->middleware('admin');
Route::post('event/sponsor/{event}', 'SponsorsController@eventSponsorAdd')->name('event.sponsor.add')->middleware('admin');
Route::post('event/sponsor/delete/{sponsor}', 'SponsorsController@eventSponsorDelete')->name('event.sponsor.delete')->middleware('admin');

// reporting

Route::get('reports', 'ReportsController@reports')->name('reports');
Route::post('reports', 'ReportsController@getReports')->name('get.report');
Route::post('general-reports', 'ReportsController@getGeneralReports')->name('get.generalreport');

// authentication routes
Auth::routes();

Route::get('test', 'HomeController@test')->name('test');
