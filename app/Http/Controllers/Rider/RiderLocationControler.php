<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\RiderLocationRequest;
use App\Models\Restaurant;
use App\Models\RiderLocation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RiderLocationControler extends BaseController
{

    public function store(RiderLocationRequest $request)
    {
        try {
            DB::beginTransaction();
            $result = RiderLocation::create($request->validated());
            return $this->sendSuccess($result, 'Rider Location stored successfully!');
            DB::commit();
        } catch (Exception $exp) {
            DB::rollBack();
            throw new HttpException(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $exp->getMessage());
        }
    }

    public function getNearestRiders($restaurantId)
    {
        try {
            $restaurant = Restaurant::find($restaurantId);
            $riders = [];
            $message = 'No data found!';

            if (!empty($restaurant)) {
                $endTime = Carbon::now();
                $startTime = Carbon::now()->subMinutes(5);

                $latitude = $restaurant?->lat;
                $longitude = $restaurant?->long;
                $distance = 2;

                $riders = RiderLocation::select('*')
                    ->selectRaw(
                        "(6371  acos(cos(radians($latitude))  cos(radians(lat))  cos(radians(long) - radians($longitude)) + sin(radians($latitude))  sin(radians(lat)))) AS distance"
                    )
                    ->having('distance', '<', $distance)
                    ->orderBy('distance')
                    ->whereBetween('capture_time', [$startTime, $endTime])
                    ->get();
            }
            return $this->sendSuccess($riders, $message);
        } catch (Exception $exp) {
            throw new HttpException(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $exp->getMessage());
        }
    }
}
