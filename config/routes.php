<?php

use FastD\Routing\RouteCollection;

route()->get('/mq', 'MqController@index');
route()->get('/supervisor', 'SupervisorController@index');
route()->get('/monitor', 'MonitorController@index');
route()->group('/api/v1', function (RouteCollection $route) {
    $route->post('/mq', 'Api\MqController@store');
    $route->put('/mq', 'Api\MqController@update');
    $route->get('/mq', 'Api\MqController@index');
    $route->put('/monitor/{id}', 'Api\MonitorController@update');
    $route->put('/monitor/status/{id}', 'Api\MonitorController@status');
    $route->get('/monitor', 'Api\MonitorController@index');
    $route->get('/system', 'Api\MonitorController@system');
    $route->get('/supervisor', 'Api\SupervisorController@index');
    $route->put('/supervisor/restart', 'Api\SupervisorController@restart');
    $route->put('/supervisor/stop', 'Api\SupervisorController@stop');
});
