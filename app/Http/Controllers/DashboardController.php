<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        return view('Dashboard.status');
    }

    public function settings()
    {
        return view('Dashboard.settings');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
