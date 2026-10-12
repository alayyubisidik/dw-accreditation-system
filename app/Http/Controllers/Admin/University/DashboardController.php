<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationAgency;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAgencies = UniversityAccreditationAgency::count();
        $totalAccreditations = UniversityAccreditation::count();

        $accreditationProgress = UniversityAccreditation::with(['agency', 'indicators'])
            ->latest()
            ->get()
            ->map(function (UniversityAccreditation $accreditation) {
                $total = $accreditation->indicators->count();
                $filled = $accreditation->indicators->filter(fn ($i) => ! is_null($i->result))->count();
                $achieved = $accreditation->indicators->filter(
                    fn ($i) => ! is_null($i->result) && ! is_null($i->target_absolute) && $i->result >= $i->target_absolute
                )->count();

                $accreditation->setAttribute('total_indicators', $total);
                $accreditation->setAttribute('filled_indicators', $filled);
                $accreditation->setAttribute('achieved_indicators', $achieved);
                $accreditation->setAttribute('progress', $total > 0 ? round(($filled / $total) * 100) : 0);
                $accreditation->setAttribute('achieved_progress', $total > 0 ? round(($achieved / $total) * 100) : 0);

                return $accreditation;
            });

        return view('university.dashboard.index', compact(
            'totalAgencies',
            'totalAccreditations',
            'accreditationProgress',
        ));
    }
}
