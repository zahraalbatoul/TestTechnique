<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Multi-Tenant Project Management API",
 *     version="1.0.0",
 *     description="A multi-tenant project and task management API built with Laravel",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
