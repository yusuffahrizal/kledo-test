<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(title="Kledo API 1", version="1.0")
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example="false"),
 *     @OA\Property(property="data", type="mixed", example=null),
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(property="errors", type="mixed", example=null)
 * )
 *
 * @OA\Response(
 *   response=401,
 *   description="Unauthorized",
 *   @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 * )
 * 
 * @OA\Response(
 *     response=403,
 *     description="Forbidden",
 *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 * )
 *
 * @OA\Response(
 *     response=404,
 *     description="Not Found",
 *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
 * )
 * 
 * @OA\Response(
 *   response=422,
 *   description="Validation error",
 *   @OA\JsonContent(
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example="false"),
 *     @OA\Property(property="data", type="mixed", example=null),
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={"property": {"property field is required."}},
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         )
 *     )
 *   )
 * )
 * 
 * @OA\SecurityScheme(
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   securityScheme="bearerAuth"
 * )
 */
class BaseController extends Controller {}
