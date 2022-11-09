<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    use ResponseTrait;

    public function list(Request $request): JsonResponse
    {
        $date     = new Carbon($request->get('date', today()));
        $filePath = storage_path("logs/laravel-{$date->format('Y-m-d')}.log");
//        dd($filePath);
        $data     = [];
        if (File::exists($filePath)) {
            $data = [
                'lastModified' => new Carbon(File::lastModified($filePath)),
                'size'         => File::size($filePath),
                'file'         => File::get($filePath),
            ];
        }
        return $this->successResponse($data);
    }
}
