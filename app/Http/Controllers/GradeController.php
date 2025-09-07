<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\SubjectScoreModel;
use App\Models\TermGradeModel;
use App\Models\TermModel;
use App\Models\GenerationModel;
use App\Models\MajorModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

protected function get(Request $request)
{

     try{

         
    $selectedTermId = $request->get('term_id');
    $selectedGenId = $request->get('gen_id');
    $selectedMajorId = $request->get('major_id');
    $terms = TermModel::all();
    $generations = GenerationModel::all();
    $majors = MajorModel::all();
 
    $students = collect(); // Empty collection by default
    $studentGrades = collect();
 
    // Only query students if both term and generation are selected
    if ($selectedTermId && $selectedGenId) {
        $studentsQuery = StudentModel::query()
            ->where('gen_id', $selectedGenId)
            ->when($selectedMajorId, function ($query) use ($selectedMajorId) {
                return $query->where('major_id', $selectedMajorId);
            })
            ->where(function ($query) use ($request) {
                $search = $request->search;
                if ($search) {
                    $query->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%$search%");
                }
            });
 
        $students = $studentsQuery->paginate(20);
 
        // Get all students with their subject scores for the selected term
        foreach ($students as $student) {
            $subjectScores = SubjectScoreModel::with('subject')
                ->where('status', 'ACT')
                ->where('term_id', $selectedTermId)
                ->where('student_id', $student->id)
                ->where('gen_id', $student->gen_id)
                ->get();
 
            $termGrade = TermGradeModel::where('term_id', $selectedTermId)
                ->where('student_id', $student->id)
                ->first();
 
            $studentGrades->push([
                'student' => $student,
                'subjectScores' => $subjectScores,
                'termGrade' => $termGrade
            ]);
        }
    } else {
        // If term and generation are not both selected, return empty paginated collection
        $students = new \Illuminate\Pagination\LengthAwarePaginator(
            collect(),
            0,
            20,
            1,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }
 
    return view('grade.list', compact(
        'terms',
        'students',
        'selectedTermId',
        'selectedGenId',
        'generations',
        'studentGrades',
        'selectedMajorId',
        'majors'
    ));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
}

    public function getMajorsByGeneration($genId)
    {
         try{

             $majors = MajorModel::whereHas('students', function ($query) use ($genId) {
                 $query->where('gen_id', $genId)
                       ->where('status', 'ACT');
             })->get();
         
             return response()->json($majors);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    public function getStudentsByGenerationAndMajor(Request $request)
    {
         try{

             $genId = $request->input('gen_id');
             $majorId = $request->input('major_id');
         
             $students = StudentModel::where('gen_id', $genId)
                 ->where('status', 'ACT')
                 ->where('major_id', $majorId)
                 ->get();
             
             return response()->json($students);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    public function add()
    {
         try{
             $terms = TermModel::all();
             $generations = GenerationModel::all();
             $majors = MajorModel::all();
     
             // Initially no students or subjects until generation and term are selected
             $students = collect([]);
             $subjects = collect([]);
     
             return view('grade.add', compact('students', 'subjects', 'terms', 'generations', 'majors'));

        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    public function getSubjectsByTerm(Request $request)
    {
         try{

             $termId = $request->input('term_id');
             $majorId = $request->input('major_id');
         
             $subjects = SubjectModel::where('term_id', $termId)
                 ->where('major_id', $majorId)
                 ->get();
         
             return response()->json($subjects);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term_id' => 'required|exists:terms,id',
            'gen_id' => 'required|exists:generations,id',
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.midterm' => 'nullable|numeric|min:0|max:100',
            'subjects.*.final' => 'nullable|numeric|min:0|max:100',
            'subjects.*.grade' => 'nullable|string|max:2',
        ]);

        try {
            DB::beginTransaction();

            $totalScore = 0;
            $subjectCount = 0;

            foreach ($validated['subjects'] as $subjectData) {
                // Skip unchecked subjects (midterm or final not filled)
                if (!isset($subjectData['midterm']) || !isset($subjectData['final']) || !isset($subjectData['grade'])) {
                    continue;
                }

                $midterm = $subjectData['midterm'];
                $final = $subjectData['final'];
                $total = $midterm + $final;

                SubjectScoreModel::create([
                    'student_id' => $validated['student_id'],
                    'subject_id' => $subjectData['subject_id'],
                    'term_id' => $validated['term_id'],
                    'gen_id' => $validated['gen_id'],
                    'midterm' => $midterm,
                    'final' => $final,
                    'total' => $total,
                    'grade' => $subjectData['grade'],
                    'status' => 'ACT'
                ]);

                $totalScore += $total;
                $subjectCount++;
            }

            // First, gather all subject scores for the student in this term and generation
            $subjectScores = SubjectScoreModel::where('student_id', $validated['student_id'])
                ->where('term_id', $validated['term_id'])
                ->where('gen_id', $validated['gen_id'])
                ->where('status', 'ACT')
                ->get();

            // Calculate total and average properly
            $subjectCount = $subjectScores->count();
            $totalScore = $subjectScores->sum('total');

            if ($subjectCount > 0) {
                $averageScore = $totalScore / $subjectCount;
                $overallGrade = match (true) {
                    $averageScore >= 90 => 'A',
                    $averageScore >= 80 => 'B',
                    $averageScore >= 70 => 'C',
                    $averageScore >= 60 => 'D',
                    default => 'F'
                };

                TermGradeModel::updateOrCreate(
                    [
                        'student_id' => $validated['student_id'],
                        'term_id' => $validated['term_id'],
                        'gen_id' => $validated['gen_id']
                    ],
                    [
                        'average_score' => $averageScore,
                        'grade' => $overallGrade
                    ]
                );
            }

            DB::commit();

            Alert::success('Subject scores added successfully!');
            return redirect()->route('grade', [
                'term_id' => $validated['term_id'],
                'gen_id' => $validated['gen_id'],
                'major_id' => $request->major_id
            ])->with('success', 'Subject scores added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error adding scores: ' . $e->getMessage())->persistent(true);
        }
    }

    public function edit($id)
    {

         try{

             $termId = request('term_id'); // get term ID from query string
             if (!$termId) {
                 return redirect()->back()->with('error', 'Term ID is required.');
             }
         
             $selectedStudent = StudentModel::findOrFail($id);
             $selectedGeneration = GenerationModel::findOrFail($selectedStudent->gen_id);
             $selectedMajor = MajorModel::findOrFail($selectedStudent->major_id);
             $generations = GenerationModel::all(); // You might not need this anymore
             $terms = TermModel::all();
             $students = StudentModel::where('gen_id', $selectedStudent->gen_id)
                                     ->where('major_id', $selectedStudent->major_id)
                                     ->get();
             $selectedTerm = TermModel::findOrFail($termId);
         
             // Fetch subjects that belong to the selected term AND the student's major
             $subjects = SubjectModel::where('term_id', $termId)
                                     ->where('major_id', $selectedStudent->major_id)
                                     ->get();
             $majors = MajorModel::all();
             $existingScores = [];
         
             $scores = SubjectScoreModel::where('student_id', $id)
                 ->where('term_id', $termId)
                 ->where('gen_id', $selectedStudent->gen_id)
                 ->where('status', 'ACT')
                 ->get();
         
             foreach ($scores as $score) {
                 $existingScores[$score->subject_id] = $score;
             }
         
             return view('grade.edit', compact(
                 'students',
                 'subjects',
                 'terms',
                 'generations', 
                 'selectedStudent',
                 'selectedMajor', 
                 'selectedGeneration',
                 'selectedTerm',
                 'existingScores',
                 'majors'
             ));
        }
        catch(Exception $ex){
            Alert::error('Something when wrong' + $ex->getMessage())->persistent(true);
        }
    }

    public function getScores($studentId, $termId)
    {

         try{

             $student = StudentModel::findOrFail($studentId);
             $scores = SubjectScoreModel::where('student_id', $studentId)
                 ->where('term_id', $termId)
                 ->where('gen_id', $student->gen_id)
                 ->where('status', 'ACT')
                 ->get();
     
             return response()->json(['scores' => $scores]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
       
    $student = StudentModel::findOrFail($id);

    $validated = $request->validate([
        'term_id' => 'required|exists:terms,id',
        'subjects' => 'required|array',
        'subjects.*.subject_id' => 'required|exists:subjects,id',
        'subjects.*.midterm' => 'nullable|numeric|min:0|max:100',
        'subjects.*.final' => 'nullable|numeric|min:0|max:100',
        'subjects.*.grade' => 'nullable|string|max:2',
    ]);

    try {
        DB::beginTransaction();

        // Fetch existing active scores for this student, term, and gen_id
        $existingScores = SubjectScoreModel::where('student_id', $id)
            ->where('term_id', $validated['term_id'])
            ->where('gen_id', $student->gen_id)
            ->where('status', 'ACT')
            ->get()
            ->keyBy('subject_id');

        $subjectIdsUpdated = [];

        $totalScore = 0;
        $subjectCount = 0;

        foreach ($validated['subjects'] as $subjectData) {
            if (!isset($subjectData['midterm']) || !isset($subjectData['final']) || !isset($subjectData['grade'])) {
                continue;
            }

            $midterm = $subjectData['midterm'];
            $final = $subjectData['final'];
            $total = $midterm + $final;
            $subjectId = $subjectData['subject_id'];

            // Check if this subject score already exists
            if ($existingScores->has($subjectId)) {
                // Update existing score
                $score = $existingScores->get($subjectId);
                $score->update([
                    'midterm' => $midterm,
                    'final' => $final,
                    'total' => $total,
                    'grade' => $subjectData['grade'],
                    'status' => 'ACT'
                ]);
            } else {
                // Create new score
                SubjectScoreModel::create([
                    'student_id' => $id,
                    'subject_id' => $subjectId,
                    'term_id' => $validated['term_id'],
                    'gen_id' => $student->gen_id,
                    'midterm' => $midterm,
                    'final' => $final,
                    'total' => $total,
                    'grade' => $subjectData['grade'],
                    'status' => 'ACT'
                ]);
            }

            $subjectIdsUpdated[] = $subjectId;
            $totalScore += $total;
            $subjectCount++;
        }

        // Mark as deleted any scores not in the current update list
        SubjectScoreModel::where('student_id', $id)
            ->where('term_id', $validated['term_id'])
            ->where('gen_id', $student->gen_id)
            ->whereNotIn('subject_id', $subjectIdsUpdated)
            ->update(['status' => 'DEL']);

        if ($subjectCount > 0) {
            $averageScore = $totalScore / $subjectCount;
            $overallGrade = match (true) {
                $averageScore >= 90 => 'A',
                $averageScore >= 80 => 'B',
                $averageScore >= 70 => 'C',
                $averageScore >= 60 => 'D',
                default => 'F'
            };

            TermGradeModel::updateOrCreate(
                [
                    'student_id' => $id,
                    'term_id' => $validated['term_id'],
                    'gen_id' => $student->gen_id
                ],
                [
                    'average_score' => $averageScore,
                    'grade' => $overallGrade
                ]
            );
        } else {
            // If no subjects, optionally delete or mark term grade as empty/null
            TermGradeModel::where('student_id', $id)
                ->where('term_id', $validated['term_id'])
                ->where('gen_id', $student->gen_id)
                ->delete();
        }

        DB::commit();

        Alert::success('Grades updated successfully!');
        return redirect()->route('grade', [
            'term_id' => $validated['term_id'],
            'gen_id' => $student->gen_id,
            'major_id' => $student->major_id
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Error updating scores: ' . $e->getMessage())->persistent(true);
    }
}


    protected function show($id)
    {
        try{

            $termId = request('term_id'); // get term ID from query string
            if (!$termId) {
                return redirect()->back()->with('error', 'Term ID is required.');
            }
        
            $selectedStudent = StudentModel::findOrFail($id);
            $selectedGeneration = GenerationModel::findOrFail($selectedStudent->gen_id);
            $selectedMajor = MajorModel::findOrFail($selectedStudent->major_id);
            $generations = GenerationModel::all(); // You might not need this anymore
            $terms = TermModel::all();
            $students = StudentModel::where('gen_id', $selectedStudent->gen_id)
                                    ->where('major_id', $selectedStudent->major_id)
                                    ->get();
            $selectedTerm = TermModel::findOrFail($termId);
        
            // Fetch subjects that belong to the selected term AND the student's major
            $subjects = SubjectModel::where('term_id', $termId)
                                    ->where('major_id', $selectedStudent->major_id)
                                    ->get();
            $majors = MajorModel::all();
            $existingScores = [];
        
            $scores = SubjectScoreModel::where('student_id', $id)
                ->where('term_id', $termId)
                ->where('gen_id', $selectedStudent->gen_id)
                ->where('status', 'ACT')
                ->get();
        
            foreach ($scores as $score) {
                $existingScores[$score->subject_id] = $score;
            }
        
            return view('grade.delete', compact(
                'students',
                'subjects',
                'terms',
                'generations', 
                'selectedStudent',
                'selectedMajor', 
                'selectedGeneration',
                'selectedTerm',
                'existingScores',
                'majors'
            ));
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function delete($studentId)
    {
         try{

             $student = StudentModel::findOrFail($studentId);
             $genId = $student->gen_id;
             $majorId = $student->major_id;
             $termId = request('term_id');
     
             DB::table('subject_scores')
                 ->where('student_id', $studentId)
                 ->when($termId, function ($query) use ($termId) {
                     return $query->where('term_id', $termId);
                 })
                 ->where('gen_id', $genId)
                 ->update(['status' => 'DEL']);
     
             Alert::success('Grades deleted successfully!');
             return redirect()->route('grade', [
                 'gen_id' => $genId,
                 'term_id' => $termId,
                 'major_id' => $majorId
             ]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
}