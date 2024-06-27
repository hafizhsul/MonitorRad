<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = SensorData::all();
        $latestData = SensorData::orderBy('waktu', 'desc')->first();

        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $oneMinuteAgo = $now->subMinute();

        $dataCpm = SensorData::where('waktu', '>=', $oneMinuteAgo)->get();
        $cpm = $dataCpm->sum('cpm');

        $dateString = $latestData->waktu;
        $dateTime = new DateTime($dateString);
        $lastOnline = $dateTime->format('d-m-Y');

        $query = SensorData::query();
        if ($request->has('daterange')) {
            $dateRange = explode(' - ', $request->daterange);
            $startDate = Carbon::createFromFormat('m/d/Y h:i A', trim($dateRange[0]))->format('Y-m-d H:i:s');
            $endDate = Carbon::createFromFormat('m/d/Y h:i A', trim($dateRange[1]))->format('Y-m-d H:i:s');
            $query->whereBetween('waktu', [$startDate, $endDate]);
        }

        $historyData = $query->get()->groupBy(function ($date) {
            return Carbon::parse($date->waktu)->format('Y-m-d H:m');
        });

        $averages = $historyData->map(function ($row) {
            return [
                'avg_temp' => number_format($row->avg('temp'), 1),
                'avg_humidity' => number_format($row->avg('humidity'), 1),
                'avg_cpm' => number_format($row->sum('cpm') / 60, 1),
                'waktu' => Carbon::parse($row->first()->waktu)->format('d-m-Y H:00'),
            ];
        })->take(15);

        return view('Dashboard.index', compact('data', 'lastOnline', 'averages'));
    }

    public function latestData()
    {
        $data = SensorData::orderBy('waktu', 'desc')->first();

        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $oneMinuteAgo = $now->subMinute();

        $dataCpm = SensorData::where('waktu', '>=', $oneMinuteAgo)->get();
        $cpm = $dataCpm->sum('cpm');

        if ($data && $data->cpm >= 150) {
            $this->sendNotification($data);

            return response()->json([
                'cpm' => $cpm,
                'temp' => $data->temp,
                'humidity' => $data->humidity,
                'waktu' => $data->waktu,
                'alert' => true
            ]);
        }

        return response()->json([
            'cpm' => $cpm,
            'temp' => $data->temp,
            'humidity' => $data->humidity,
            'waktu' => $data->waktu,
            'alert' => false
        ]);
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
            $lastHours = $latestDateTime->copy()->subHours(12);

            $sensors = SensorData::where('waktu', '>=', $lastHours)
                ->orderBy('waktu', 'asc')
                ->get();

            $groupedData = [];

            for ($i = 0; $i < 12; $i++) {
                $hour = $lastHours->copy()->addHours($i)->format('Y-m-d H:00:00');
                $groupedData[$hour] = [
                    'waktu' => $lastHours->copy()->addHours($i)->format('H:00'),
                    'average_cpm' => 0,
                    'average_temperature' => 0
                ];
            }

            $sensorsGrouped = $sensors->groupBy(function ($item) {
                return Carbon::parse($item->waktu)->format('Y-m-d H:00:00');
            });

            foreach ($sensorsGrouped as $hour => $group) {
                $groupedData[$hour] = [
                    'waktu' => Carbon::parse($hour)->format('H:00'),
                    'average_cpm' => number_format($group->sum('cpm') / 60, 1),
                    'average_temperature' => number_format($group->avg('temp'), 1)
                ];
            }

            return response()->json(array_values($groupedData));
        }

        return response()->json([]);
    }

    public function status()
    {
        $data = SensorData::where('cpm', '>=', 150)->get();

        $data->transform(function ($item, $key) {
            $item->waktu_new = Carbon::parse($item->waktu)->format('H:i:s');
            $item->tanggal_new = Carbon::parse($item->waktu)->format('d-m-Y');
            return $item;
        });

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

    public function sendNotification($data)
    {
        $cpm = isset($data->cpm) ? $data->cpm : 'N/A';
        $suhu = isset($data->temp) ? $data->temp : 'N/A';
        $humidity = isset($data->humidity) ? $data->humidity : 'N/A';

        $message = "⚠️ *PERINGATAN RADIASI LIMBAH B3* ⚠️

‼️ Deteksi radiasi berbahaya telah teridentifikasi di area sekitar.

Data Radiasi
CPM : {$cpm}
Suhu : {$suhu}
Kelembaban : {$humidity}

🛑 Mohon segera menjauh dari lokasi dan ikuti prosedur keselamatan yang berlaku. 
📞 Hubungi petugas keamanan atau pihak berwenang untuk informasi lebih lanjut. 
🚨 Utamakan keselamatan Anda dan orang-orang di sekitar.";
        $chatId = '1152076122';
        $botToken = '7281018577:AAEjj1uh5jsuqKpRDvlc-EgqzxJPFKxrMhw';

        $client = new Client();
        $response = $client->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'form_params' => [
                'chat_id' => $chatId,
                'text' => $message,
            ]
        ]);

        return $response->getBody();
    }
}
