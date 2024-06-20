<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Response;
use GuzzleHttp\Client;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SensorData::all(['cpm', 'waktu']);
        $latestData = SensorData::orderBy('waktu', 'desc')->first();

        $dateString = $latestData->waktu;
        $dateTime = new DateTime($dateString);
        $lastOnline = $dateTime->format('d-m-Y');

        return view('Dashboard.index', compact('data', 'lastOnline'));
    }

    public function latestData()
    {
        $data = SensorData::orderBy('waktu', 'desc')->first();
        if ($data && $data->cpm >= 30) {
            $this->sendNotification($data);

            return response()->json([
                'cpm' => $data->cpm,
                'temp' => $data->temp,
                'humidity' => $data->humidity,
                'waktu' => $data->waktu,
                'alert' => true
            ]);
        }

        return response()->json([
            'cpm' => $data->cpm,
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
                    'average_cpm' => $group->avg('cpm'),
                    'average_temperature' => $group->avg('temp')
                ];
            }

            return response()->json(array_values($groupedData));
        }

        return response()->json([]);
    }

    public function status()
    {
        $data = SensorData::where('cpm', '>=', 10)->get();

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