<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{

    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        $search = $request->input('search');

        if ($search) {
            $searchLower = strtolower($search);

            // [PERBAIKAN] Logika khusus untuk filter cepat berdasarkan kata kunci
            if ($searchLower === 'hari ini') {
                $query->whereDate('created_at', Carbon::today());

            } elseif ($searchLower === 'kemarin') {
                $query->whereDate('created_at', Carbon::yesterday());

            } elseif ($searchLower === 'minggu ini') {
                $query->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()]);

            } else {
                // Logika pencarian umum jika bukan kata kunci
                 $query->where(function ($q) use ($search, $searchLower) {
                    $q->whereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
                    
                    // Coba parsing tanggal jika formatnya dd/mm/yyyy
                    try {
                        $date = Carbon::createFromFormat('d/m/Y', $search);
                        $q->orWhereDate('created_at', $date);
                    } catch (\Exception $e) {
                    }
                    $months = [
                    'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 
                    'mei' => 5, 'juni' => 6, 'juli' => 7, 'agustus' => 8, 
                    'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12
                ];
                if (isset($months[$searchLower])) {
                    $q->orWhereMonth('created_at', $months[$searchLower]);
                }

                // [TAMBAH] Logika untuk mencari berdasarkan tahun
                if (is_numeric($search) && strlen($search) === 4) {
                    $q->orWhereYear('created_at', $search);
                }
                });
            }
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        if ($sort === 'user_name') {
            $query->join('users', 'activity_logs.user_id', '=', 'users.id')
                ->orderBy('users.name', $direction)
                ->select('activity_logs.*');
        } else {
            $query->orderBy($sort, $direction);
        }

        $logs = $query->paginate(15)->withQueryString();

        return view('activity-logs.index', compact('logs')); // Pastikan path view Anda sudah benar
    }

    /**
     * Helper method untuk menambahkan pencarian berdasarkan tanggal.
     * (Tidak ada perubahan di sini)
     */
    private function addDateSearch($query, $searchTerm)
    {
        $dateFormats = [
            'd/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/y', 'd-m-y', 'm/d/Y', 'Y/m/d',
        ];
        
        $dateParsed = false;
        foreach ($dateFormats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $searchTerm);
                if ($date) {
                    $query->orWhereDate('created_at', $date->format('Y-m-d'));
                    $dateParsed = true;
                    break;
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        
        if (!$dateParsed) {
            $monthNames = [
                'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6, 
                'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
                'january' => 1, 'february' => 2, 'march' => 3, 'may' => 5, 'june' => 6, 'july' => 7, 
                'august' => 8, 'oct' => 10, 'dec' => 12
            ];
            
            $searchLower = strtolower($searchTerm);
            if (isset($monthNames[$searchLower])) {
                $monthNumber = $monthNames[$searchLower];
                $query->orWhereMonth('created_at', $monthNumber);
            }
            
            if (is_numeric($searchTerm) && strlen($searchTerm) === 4) {
                $query->orWhereYear('created_at', $searchTerm);
            }
        }
    }
}