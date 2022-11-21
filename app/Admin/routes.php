<?php

use Illuminate\Routing\Router;

Admin::routes();
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('manager/users', UserController::class);
    $router->resource('manager/articles', ArticleController::class);
    $router->resource('manager/tickets', TicketController::class);
    $router->resource('manager/firsts', FirstController::class);
    $router->resource('manager/buyings', BuyingController::class);
    $router->resource('manager/shipments', ShipmentController::class);
    $router->resource('manager/sponsers', SponserController::class);
    $router->resource('manager/information', InformationController::class);
    $router->resource('manager/questions', QuestionController::class);
    $router->resource('manager/tags', TagController::class);
    $router->post('tickets/published', 'TicketController@published')->name('published');
    $router->post('tickets/autoCreate', 'TicketController@autoCreate')->name('autoCreate');
    $router->post('shipments/labelPublish', 'ShipmentController@labelPublish')->name('labelPublish');
    $router->post('shipments/labelPublishedDone', 'ShipmentController@labelPublishedDone')->name('labelPublishedDone');
    $router->post('shipments/labelRePublish', 'ShipmentController@labelRePublish')->name('labelRePublish');
    $router->get('mail', 'MailController@index')->name('mail');
    $router->post('send', 'MailController@send')->name('sendMail');
});
