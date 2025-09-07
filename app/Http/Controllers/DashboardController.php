<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\SubjectScoreModel;
use App\Models\TeacherModel;
use App\Models\TermModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    //
        public function __construct()
        {
            $this->middleware('auth');
        }

public function dashboard(Request $request)
{

    try{
        
    // Calculate totals
    $totalTeachers = TeacherModel::count();
    $totalStudents = StudentModel::count();
    $totalSubjects = SubjectModel::count();
    $totalEmployees = EmployeeModel::count();
    $totalUsers = User::count();
    
    // Get subject scores for the selected term
    $selectedTermId = TermModel::first()->id ?? null;
    $subjectScores = SubjectScoreModel::select('subject_id', 'student_id','total')
        ->where('term_id', $selectedTermId)
        ->with('subject:id,name')
        ->with('student:id')
        ->get()
        ->groupBy('subject.name')
        ->map(function ($scores) {
            return $scores->avg('score');
        });
    $subjectLabels = $subjectScores->keys()->toArray();
    $subjectData = $subjectScores->values()->map(fn($score) => round($score, 2))->toArray();
    
    // Get yearly enrollment data
    $enrollmentData = $this->getEnrollmentData();
    
    // Get top majors by enrollment
    $topMajors = $this->getTopMajors();
    
    // Get enrollment statistics
    $activeStudents = StudentModel::where('status', 'active')->count();
    $studentsThisYear = StudentModel::whereYear('enroll_at', date('Y'))->count();
    
    return view('dashboard', compact(
        'totalTeachers', 
        'totalStudents',
        'totalUsers', 
        'totalSubjects', 
        'totalEmployees', 
        'subjectLabels', 
        'subjectData',
        'enrollmentData',
        'topMajors',
        'activeStudents',
        'studentsThisYear'
    ));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }

}



 private function getAvailableYears()
{

    try{

        // Get years from database
        $years = StudentModel::selectRaw('YEAR(enroll_at) as year')
                            ->whereNotNull('enroll_at')
                            ->distinct()
                            ->pluck('year')
                            ->toArray();
        
        // Get current year and month
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('n'); // 1-12
        
        // Define when to show next year (e.g., after December)
        $showNextYearAfterMonth = 12; // December
        
        // Always include current year
        $years[] = $currentYear;
        
        // Only include next year if we're past the cutoff month
        if ($currentMonth > $showNextYearAfterMonth) {
            $years[] = $currentYear + 1;
        }
        
        // Remove duplicates and sort
        $years = array_unique($years);
        sort($years);
        
        return $years;
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
}




private function getEnrollmentData()
{
    try{

        // Get enrollment data from database
        $yearlyEnrollments = StudentModel::selectRaw('YEAR(enroll_at) as year, COUNT(*) as count')
                                        ->whereNotNull('enroll_at')
                                        ->groupBy('year')
                                        ->get()
                                        ->pluck('count', 'year')
                                        ->toArray();
        
        // Get current year and date
        $currentYear = (int)date('Y');
        $currentDate = date('Y-m-d');
        $yearEndDate = $currentYear . '-12-31';
        
        // Get available years from database
        $dbYears = array_keys($yearlyEnrollments);
        
        // Only include next year if current year has ended
        $yearsToInclude = array_merge($dbYears, [$currentYear]);
        if ($currentDate > $yearEndDate) {
            $yearsToInclude[] = $currentYear + 1;
        }
        
        // Remove duplicates and sort
        $years = array_unique($yearsToInclude);
        sort($years);
        
        // Take the most recent 6 years
        $displayYears = array_slice($years, max(0, count($years) - 6), 6);
        
        // Create labels and data arrays
        $labels = $displayYears;
        $data = [];
        
        // Fill data array with enrollment counts or zeros
        foreach ($displayYears as $year) {
            $data[] = $yearlyEnrollments[$year] ?? 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
}

/**
 * Get top majors by enrollment across all years
 * 
 * @return \Illuminate\Support\Collection
 */
private function getTopMajors()
{

    try{

        // Get top majors across all years
        return DB::table('students')
            ->join('majors', 'students.major_id', '=', 'majors.id')
            ->selectRaw('majors.name, COUNT(*) as count')
            ->whereNotNull('students.enroll_at')
            ->whereNotNull('students.major_id')
            ->groupBy('majors.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
}


    // public function dashboard(): View
    // {
    //     $totalTeachers = TeacherModel::count();
    //     $totalStudents = StudentModel::count();
    //     $totalSubjects = SubjectModel::count();
    //     $totalEmployees = EmployeeModel::count();
    //     $totalUsers = User::count();
    //     $selectedTermId = TermModel::first()->id ?? null;

    //     $subjectScores = SubjectScoreModel::select('subject_id', 'student_id', 'score')
    //         ->where('term_id', $selectedTermId)
    //         ->with('subject:id,name')
    //         ->with('student:id') 
    //         ->get()
    //         ->groupBy('subject.name')
    //         ->map(function ($scores) {
    //             return $scores->avg('score');
    //         });

    //     $subjectLabels = $subjectScores->keys()->toArray();
    //     $subjectData = $subjectScores->values()->map(fn($score) => round($score, 2))->toArray();

    //     return view('dashboard', compact('totalTeachers', 'totalStudents','totalUsers' , 'totalSubjects', 'totalEmployees' , 'subjectLabels', 'subjectData'));
    // }
}
