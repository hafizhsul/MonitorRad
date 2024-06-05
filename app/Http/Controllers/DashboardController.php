<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waktu = Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y');
        $data = SensorData::all(['cpm', 'waktu']);

        return view('Dashboard.index', compact('data','waktu'));
    }

    public function latestData()
    {
        $data = SensorData::orderBy('waktu', 'desc')->first();
        return Response::json($data);
    }

    public function chartData()
    {
        $data = SensorData::orderBy('waktu', 'desc')->limit(20)->get();
        return Response::json($data);
    }

    public function conditionData()
    {
        $latestSensorData = SensorData::latest('waktu')->first();
        
        if ($latestSensorData) {
            $latestDateTime = Carbon::parse($latestSensorData->waktu);
            $oneMinuteAgo = $latestDateTime->subMinutes(10);

            $sensors = SensorData::where('waktu', '>=', $oneMinuteAgo)
                ->orderBy('waktu', 'asc')
                ->get();

            $groupedData = $sensors->map(function ($item) {
                return [
                    'waktu' => Carbon::parse($item->waktu)->format('H:i'),
                    'cpm' => $item->cpm,
                    'temp' => $item->temp
                ];
            })->groupBy('waktu')->map(function ($group) {
                return [
                    'waktu' => $group->first()['waktu'],
                    'average_cpm' => $group->avg('cpm'),
                    'average_temperature' => $group->avg('temp')
                ];
            });

            return response()->json($groupedData->values());
        }
    }

    public function status()
    {
        $data = SensorData::where('cpm', '>=', 10)->get();

        return view('Dashboard.status', compact('data'));
    }

    public function settings()
    {
        return view('Dashboard.settings');
    }

    public function deviceStatus() 
    {
        $offlineStatus = Carbon::now()->setTimezone('Asia/Jakarta')->subMinutes(2);
        $latestData = SensorData::orderBy('waktu', 'desc')->first();
        $dateString = $latestData->waktu;
        $dateTime = new DateTime($dateString);
        $lastOnline = $dateTime->format('d-m-Y');

        if ($latestData && $latestData->waktu > $offlineStatus) {
            $status = 'Online';
        } else {
            $status = 'Offline';
        }        

        return response()->json(['status' => $status, 'lastOnline' => $lastOnline]);
    }
}
