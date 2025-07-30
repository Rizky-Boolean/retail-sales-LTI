<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas dengan sorting dan advanced search.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Handle advanced search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // Search di nama user
                $q->whereHas('user', function($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%");
                })
                // Search di deskripsi aktivitas
                ->orWhere('description', 'like', "%{$searchTerm}%")
                // Search di IP address
                ->orWhere('ip_address', 'like', "%{$searchTerm}%");
                
                // Search berdasarkan waktu (format: dd/mm/yyyy atau dd-mm-yyyy atau yyyy-mm-dd)
                $this->addDateSearch($q, $searchTerm);
            });
        }
        
        // Handle date range filter (optional)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Handle sorting
        $sortField = $request->get('sort', 'created_at'); // default sort by waktu terbaru
        $sortDirection = $request->get('direction', 'desc'); // default descending (terbaru dulu)
        
        // Validasi kolom yang bisa di-sort untuk keamanan
        $allowedSorts = ['created_at', 'description', 'ip_address'];
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else if ($sortField === 'user_name') {
            // Special case untuk sorting berdasarkan nama user
            $query->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
                  ->orderBy('users.name', $sortDirection)
                  ->select('activity_logs.*'); // Pastikan hanya select kolom activity_logs
        } else {
            // Fallback ke default sorting
            $query->orderBy('created_at', 'desc');
        }
        
        $logs = $query->paginate(20);
        
        // Append query parameters ke pagination links
        $logs->appends($request->query());
        
        return view('activity-logs.index', compact('logs'));
    }

    /**
     * Helper method untuk menambahkan pencarian berdasarkan tanggal
     */
    private function addDateSearch($query, $searchTerm)
    {
        // Coba berbagai format tanggal
        $dateFormats = [
            'd/m/Y',     // 25/12/2024
            'd-m-Y',     // 25-12-2024  
            'Y-m-d',     // 2024-12-25
            'd/m/y',     // 25/12/24
            'd-m-y',     // 25-12-24
            'm/d/Y',     // 12/25/2024 (format US)
            'Y/m/d',     // 2024/12/25
        ];
        
        foreach ($dateFormats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $searchTerm);
                if ($date) {
                    $query->orWhereDate('created_at', $date->format('Y-m-d'));
                    break; // Keluar dari loop jika berhasil parse
                }
            } catch (\Exception $e) {
                // Ignore dan coba format berikutnya
                continue;
            }
        }
        
        // Coba pencarian berdasarkan nama bulan (contoh: "januari", "december")
        $monthNames = [
            // Bahasa Indonesia
            'januari' => 1, 
            'februari' => 2, 
            'maret' => 3, 
            'april' => 4,
            'mei' => 5, 
            'juni' => 6, 
            'juli' => 7, 
            'agustus' => 8,
            'september' => 9, 
            'oktober' => 10, 
            'november' => 11, 
            'desember' => 12,
            // Bahasa Inggris
            'january' => 1, 
            'february' => 2, 
            'march' => 3, 
            'may' => 5,
            'june' => 6, 
            'july' => 7, 
            'august' => 8, 
            'oct' => 10, 
            'dec' => 12
        ];
        
        $searchLower = strtolower($searchTerm);
        if (isset($monthNames[$searchLower])) {
            $monthNumber = $monthNames[$searchLower];
            $query->orWhereMonth('created_at', $monthNumber);
        }
        
        // Coba pencarian berdasarkan tahun
        if (is_numeric($searchTerm) && strlen($searchTerm) === 4 && $searchTerm >= 2020 && $searchTerm <= 2030) {
            $query->orWhereYear('created_at', $searchTerm);
        }
    }

    /**
     * [DEPRECATED] Method search untuk AJAX - masih dipertahankan untuk backward compatibility
     * Tapi sudah ditingkatkan dengan advanced search
     */
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = ActivityLog::with('user');
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('ip_address', 'like', "%{$search}%");
                
                // Tambahkan pencarian tanggal untuk AJAX
                $this->addDateSearch($q, $search);
            });
        }
        
        $logs = $query->latest()->get();
        
        return response()->json($logs);
    }
}