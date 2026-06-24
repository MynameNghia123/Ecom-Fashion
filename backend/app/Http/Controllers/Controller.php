<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Tài liệu API E-commerce Fashion',
    description: 'Hợp đồng API giữa Vue.js Frontend và Laravel Backend'
)]
#[OA\Server(
    url: 'http://localhost:8000',
    description: 'Local Development Server'
)]
abstract class Controller
{
    //
}
