<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User;
use App\Models\Event;
use App\Models\Booking;

class OwnerController extends Controller
{
    // ==================== DASHBOARD ====================

    public function dashboard()
    {
        $totalVendor  = User::where('role', 'vendor')->count();
        $totalTenant  = User::where('role', 'tenant')->count();
        $totalUser    = User::where('role', 'user')->count();
        $totalEvent   = Event::count();
        $totalBooking = Booking::count();
        $unreadReport = Report::where('status', 'unread')->count();

        $latestReports = Report::with('admin')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalVendor',
            'totalTenant',
            'totalUser',
            'totalEvent',
            'totalBooking',
            'unreadReport',
            'latestReports'
        ));
    }

    // ==================== LAPORAN ====================

    public function reports(Request $request)
    {
        $query = Report::with('admin')->orderBy('created_at', 'desc');

      // Filter bulan
    if ($request->filled('month')) {
        $query->whereMonth('period_start', $request->month);
    }

    // Filter tahun
    if ($request->filled('year')) {
        $query->whereYear('period_start', $request->year);
    }

        $reports = $query->paginate(10);

        // Tandai semua sebagai read saat halaman laporan dibuka
        Report::where('status', 'unread')->update(['status' => 'read']);

        $years = Report::selectRaw('DISTINCT YEAR(period_start) as year')
        ->orderBy('year', 'desc')
        ->pluck('year');

        return view('owner.Reports.reports', compact('reports', 'years'));
    }

    public function showReport(Report $report)
    {
        if ($report->status === 'unread') {
            $report->update(['status' => 'read']);
        }

        return view('owner.Reports.report-detail', compact('report'));
    }

    public function downloadReport(Report $report)
    {
        $filePath = storage_path('app/public/' . $report->file_path);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $report->file_name);
    }
}