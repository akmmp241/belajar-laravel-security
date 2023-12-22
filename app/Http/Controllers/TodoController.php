<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseCode;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TodoController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $this->authorize('create', Todo::class);

        return Response::json([
            "message" => "created"
        ])->setStatusCode(ResponseCode::HTTP_CREATED);
    }
}
