<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChemicalRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ChemicalImport;
use App\Models\ProductionRegistration;


class DashboardController extends Controller
{
    public function index()
    {
        $today = now();
        // ทะเบียนนำเข้าทั้งหมด
        $totalImport = ChemicalImport::count();
        $soonImport = ChemicalImport::whereDate('expired_license_date', '>=', Carbon::now())
            ->whereDate('expired_license_date', '<=', Carbon::now()->addMonths(6))
            ->count();
        $expiredImport = ChemicalImport::whereDate('expired_license_date', '<', Carbon::now())->count();


        // ทะเบียนสินค้า
        $totalRegistrations = ChemicalRegistration::count();
        $soonRegistrations = ChemicalRegistration::where('new_or_old', false)
            ->whereBetween('expired_license_number', [now(), now()->addDays(180)])
            ->count();
        $expiredRegistrations = ChemicalRegistration::where('expired_license_number', '<', $today)
            ->where('new_or_old', false)
            ->count();

        // ทะเบียนผลิต
        $totalProduct = ProductionRegistration::count();
        $soonProduct = ProductionRegistration::whereDate('expired_license_date', '>=', Carbon::now())
            ->whereDate('expired_license_date', '<=', Carbon::now()->addMonths(6))
            ->count();
        $expiredProduct = ProductionRegistration::whereDate('expired_license_date', '<', Carbon::now())->count();

        // ขึ้นทะเบียนสินค้าใหม่
        $totalNewRegistrations = ChemicalRegistration::where('new_or_old', true)->where('progress', '<', 100)->count();
        $betweenNewRegistrations = ChemicalRegistration::where('new_or_old', true)->where('progress', '>', 1)->count();


        return view('dashboard', [
            'totalImport' =>  $totalImport,
            'expiredImport' => $expiredImport,
            'soonImport' => $soonImport,

            'totalRegistrations' =>  $totalRegistrations,
            'soonRegistrations' => $soonRegistrations,
            'expiredRegistrations' => $expiredRegistrations,

            'totalProduct' =>  $totalProduct,
            'soonProduct' => $soonProduct,
            'expiredProduct' => $expiredProduct,

            'totalNewRegistrations' =>  $totalNewRegistrations,
            'betweenNewRegistrations' => $betweenNewRegistrations,
        ]);
    }
}
