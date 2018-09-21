<?php

return [
    // =========== Institution ============
    'PUT,PATCH v1/institution/<id>' => 'v1/institution/update',
    'DELETE v1/institution/<id>' => 'v1/institution/delete',
    'GET,HEAD v1/institution/<id>' => 'v1/institution/view',
    'POST v1/institution' => 'v1/institution/create',
    'GET,HEAD v1/institution' => 'v1/institution/index',

    // =========== User ============
    'PUT,PATCH v1/user/<id>' => 'v1/user/update',
    'DELETE v1/user/<id>' => 'v1/user/delete',
    'GET,HEAD v1/user/<id>' => 'v1/user/view',
    'POST v1/user' => 'v1/user/create',
    'GET,HEAD v1/user' => 'v1/user/index',
    'POST user/login' => 'user/login',

    // =========== Complaint ============
    'POST v1/complaint/forward/<id>' => 'v1/complaint/forward',
    'POST v1/complaint/formalize/<id>' => 'v1/complaint/formalize',
    'POST v1/complaint/finalize/<id>' => 'v1/complaint/finalize',
    'POST v1/complaint/response/<id>' => 'v1/complaint/response',
    'PUT,PATCH v1/complaint/<id>' => 'v1/complaint/update',
    'DELETE v1/complaint/<id>' => 'v1/complaint/delete',
    'GET,HEAD v1/complaint/<id>' => 'v1/complaint/view',
    'POST v1/complaint' => 'v1/complaint/create',
    'GET,HEAD v1/complaint' => 'v1/complaint/index',

    // =========== Citizen ============
    'POST v1/citizen' => 'v1/citizen/create',
    'GET,HEAD v1/citizen/<id>' => 'v1/citizen/view',
    'GET,HEAD v1/citizen' => 'v1/citizen/index',
];
