<?php

Auth::routes();
Route::get('/', 'topController@index')->name('top.index');
Route::get('/home', 'ArticleController@index')->name('articles.index')->middleware('auth');
Route::get('/allArticles', 'ArticleController@allArticles')->name('articles.all');
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/{provider}', 'Auth\LoginController@redirectToProvider')->name('{provider}');
    Route::get('/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('{provider}.callback');
});
Route::prefix('register')->name('register.')->group(function () {
    Route::get('/{provider}', 'Auth\RegisterController@showProviderUserRegistrationForm')->name('{provider}');
    Route::post('/{provider}', 'Auth\RegisterController@registerProviderUser')->name('{provider}');
});
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');
Route::resource('/articles', 'ArticleController')->only(['show']);
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
    Route::post('/generateURL', 'ArticleController@getPreSignedUrl')->name('generateURL')->middleware('auth');
});
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');
Route::get('/hobbies/{name}', 'HobbyController@show')->name('hobbies.show');
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/{name}', 'UserController@show')->name('show');
    Route::get('/{name}/likes', 'UserController@likes')->name('likes');
    Route::get('/{name}/followings', 'UserController@followings')->name('followings');
    Route::get('/{name}/followers', 'UserController@followers')->name('followers');
    Route::get('/{name}/blockings', 'UserController@blockings')->name('blockings');
    Route::get('/{name}/blockers', 'UserController@blockers')->name('blockers');
    Route::middleware('auth')->group(function () {
        Route::put('/{name}/follow', 'UserController@follow')->name('follow');
        Route::delete('/{name}/follow', 'UserController@unfollow')->name('unfollow');
        Route::put('/{name}/block', 'UserController@block')->name('block');
        Route::delete('/{name}/block', 'UserController@unblock')->name('unblock');
    });
});
Route::get('/tweet', 'ArticleController@fetch');
Route::get('/getFollowInfo', 'UserController@fetch');
Route::get('/userHobby', 'HobbyController@fetch');
Route::get('/setting', 'SettingController@index')->name('setting.index')->middleware('auth');
Route::get('/setting/profile', 'SettingController@profile')->name('setting.profile')->middleware('auth');
Route::post('/setting/profile', 'SettingController@store')->name('profile.store')->middleware('auth');
Route::post('/profile/generateURL', 'SettingController@getPreSignedUrl')->name('profile.generateURL')->middleware('auth');
Route::get('/setting/block', 'SettingController@block')->name('profile.block')->middleware('auth');
Route::get('/setting/plan', 'SettingController@plan')->name('setting.plan')->middleware('auth');
Route::post('/setting/payment', 'PaymentController@payment')->name('payment.store')->middleware('auth');
Route::get('/setting/credit', 'SettingController@credit')->name('setting.credit')->middleware('auth');
Route::post('/setting/credit/addressStore', 'SettingController@addressStore')->name('credit.address')->middleware('auth');
Route::post('/setting/credit', 'SettingController@creditStore')->name('credit.store')->middleware('auth');
Route::get('/setting/purchaseHistory', 'SettingController@purchaseHistory')->name('setting.purchaseHistory')->middleware('auth');
Route::get('/setting/deleteConfirm', 'SettingController@deleteConfirm')->name('setting.deleteConfirm')->middleware('auth');
Route::delete('/setting/delete', 'SettingController@delete')->name('setting.delete')->middleware('auth');
Route::get('/setting/history', 'SettingController@history')->name('setting.history')->middleware('auth');
Route::get('/setting/qutte', 'SettingController@qutte')->name('setting.qutte')->middleware('auth');
Route::get('/sponsers', 'SettingController@sponsers')->name('setting.sponsers');
Route::get('/information', 'SettingController@information')->name('setting.information');
Route::get('/questions', 'SettingController@questions')->name('setting.questions');
Route::get('/fetchQuestions', 'SettingController@fetchQuestions');
Route::get('/fetchInformation', 'SettingController@fetchInformation');
Route::get('/news/{id}', 'SettingController@news')->name('setting.news');
Route::get('/howtouse', 'SettingController@howtouse')->name('setting.howtouse');


Route::get('/setting/payment/singleQutte', 'PaymentController@singleQutte')->name('payment.singleQutte')->middleware('auth');
Route::get('/setting/payment/setQutte', 'PaymentController@setQutte')->name('payment.setQutte')->middleware('auth');
Route::get('/setting/payment/lite', 'PaymentController@lite')->name('payment.lite')->middleware('auth');
Route::get('/setting/payment/changeLite', 'PaymentController@changeLite')->name('payment.changeLite')->middleware('auth');
Route::get('/setting/payment/standard', 'PaymentController@standard')->name('payment.standard')->middleware('auth');
Route::get('/setting/payment/changeStandard', 'PaymentController@changeStandard')->name('payment.changeStandard')->middleware('auth');
Route::get('/setting/payment/cancelLite', 'PaymentController@cancelLite')->name('payment.cancelLite')->middleware('auth');
Route::get('/setting/payment/cancelStandard', 'PaymentController@cancelStandard')->name('payment.cancelStandard')->middleware('auth');
Route::get('/setting/payment/restartLite', 'PaymentController@restartLite')->name('payment.restartLite')->middleware('auth');
Route::get('/setting/payment/restartStandard', 'PaymentController@restartStandard')->name('payment.restartStandard')->middleware('auth');

Route::post('/setting/payment/addressStore', 'PaymentController@addressStore')->name('payment.address')->middleware('auth');
Route::post('/ticket/update', 'TicketController@done')->name('qutte.done');

Route::post('/webhook', 'PaymentController@webhook');
Route::get('/qutte', 'TicketController@fetch');
Route::get('/bottle', 'TicketController@bottleFetch');
Route::get('/confirmQutte', 'TicketController@confirmFetch');


Route::get('/search/article', 'SearchController@article')->name('search.article');
Route::get('/search/user', 'SearchController@user')->name('search.user');
Route::get('/searchArticle', 'SearchController@fetchSearchArticle');
Route::get('/searchUser', 'SearchController@fetchSearchUser');

Route::get('/contact', 'PagesController@contact')->name('contact');
Route::post('/contactSend', 'PagesController@contactSend')->name('contactSend');

Route::get('/shipmentInfo', 'ShipmentController@fetch');

Route::get('/terms', 'RuleController@terms')->name('rule.terms');
Route::get('/privacy', 'RuleController@privacy')->name('rule.privacy');
Route::get('/tokushoho', 'RuleController@tokushoho')->name('rule.tokushoho');
