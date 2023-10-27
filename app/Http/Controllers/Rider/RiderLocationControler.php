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
use Illuminate\Support\Facades\Log;
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

                $riders = RiderLocation::select('rider_locations.*')
                    ->selectRaw(
                        " ( 
                            3959 * acos( 
                                cos( radians(  ?  ) ) *
                                cos( radians( latitude ) ) * 
                                cos( radians( longitude ) - radians(?) ) + 
                                sin( radians(  ?  ) ) *
                                sin( radians( latitude ) ) 
                            )
                       ) AS distance"
                    )
                    ->having('distance', '<', '?')
                    ->orderBy('distance')
                    ->whereBetween('capture_time', [$startTime, $endTime])
                    ->setBindings([$latitude, $longitude, $latitude, $distance])
                    ->get();
            }
            return $this->sendSuccess($riders, $message);
        } catch (Exception $exp) {
            // return $exp->getMessage();
            throw new HttpException(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $exp->getMessage());
        }
    }
}
