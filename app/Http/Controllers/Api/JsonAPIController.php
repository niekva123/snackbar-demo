<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use JsonSerializable;

abstract class JsonAPIController extends Controller
{
    protected function failedResponse(string $error, int $httpCode = 500, array $details = []): Response
    {
        return $this->response(array_filter([
            'success' => false,
            "error" => 'Validation failed',
            "details" => $details,
        ]), $httpCode);
    }

    protected function response(array|JsonSerializable $body, int $statusCode = 200): Response
    {
        return \response(json_encode($body), $statusCode, [
            'Content-Type' => 'application/json',
        ]);
    }
}
