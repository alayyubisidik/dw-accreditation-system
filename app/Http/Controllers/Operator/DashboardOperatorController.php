<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationIndicator;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardOperatorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $studyProgram = StudyProgram::with('faculty')->find($user->study_program_id);

        // ---------------------------------------------------------
        // AKREDITASI YANG BERJALAN UNTUK PRODI OPERATOR INI
        // ---------------------------------------------------------
        $accreditations = StudyProgramAccreditation::with('batch.agency')
            ->where('study_program_id', $user->study_program_id)
            ->withCount([
                'indicators as total_indicators',
                'indicators as filled_indicators' => fn($q) => $q->whereNotNull('result'),
                'indicators as achieved_indicators' => fn($q) => $q->whereNotNull('result')
                    ->where(function ($q2) {
                        $q2->where('gap', '<=', 0)
                            ->orWhere(function ($q3) {
                                $q3->whereNull('gap')->whereColumn('result', '>=', 'target');
                            });
                    }),
            ])
            ->latest()
            ->get()
            ->map(function ($accreditation) {
                $accreditation->progress = $accreditation->total_indicators > 0
                    ? round($accreditation->filled_indicators / $accreditation->total_indicators * 100)
                    : 0;

                $accreditation->unfilled_indicators   = $accreditation->total_indicators - $accreditation->filled_indicators;
                $accreditation->unachieved_indicators = $accreditation->filled_indicators - $accreditation->achieved_indicators;

                return $accreditation;
            });

        // ---------------------------------------------------------
        // RINGKASAN
        // ---------------------------------------------------------
        $totalIndicators      = $accreditations->sum('total_indicators');
        $filledIndicators     = $accreditations->sum('filled_indicators');
        $achievedIndicators   = $accreditations->sum('achieved_indicators');
        
        $unachievedIndicators = $filledIndicators - $achievedIndicators;
        $incompleteIndicators = $totalIndicators - $filledIndicators;
        
        $overallProgress      = $totalIndicators > 0 ? round($filledIndicators / $totalIndicators * 100) : 0;

        // ---------------------------------------------------------
        // INDIKATOR YANG PALING BUTUH SEGERA DIISI
        // ---------------------------------------------------------
        $pendingIndicators = StudyProgramAccreditationIndicator::with('accreditation.batch')
            ->whereHas('accreditation', fn($q) => $q->where('study_program_id', $user->study_program_id))
            ->whereNull('result')
            ->latest()
            ->take(5)
            ->get();

        return view('operator.index', compact(
            'studyProgram',
            'accreditations',
            'totalIndicators',
            'filledIndicators',
            'achievedIndicators',
            'unachievedIndicators',
            'incompleteIndicators',
            'overallProgress',
            'pendingIndicators'
        ));
    }
}
