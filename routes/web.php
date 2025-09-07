<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect()->route('login');
});

//Subject
Route::get('/subject',[SubjectController::class, 'get'])->name('subject');
Route::get('/subject/add',[SubjectController::class, 'add'])->name('subject.add');
Route::post('/subject/create',[SubjectController::class, 'create'])->name('subject.create');
Route::get('/subject/edit/{id}',[SubjectController::class, 'find'])->name('subject.edit');
Route::put('/subject/update/{id}',[SubjectController::class, 'update'])->name('subject.update');
Route::get('/subject/show/{id}',[SubjectController::class,'show'])->name('subject.show');
Route::delete('/subject/delete/{id}',[SubjectController::class, 'delete'])->name('subject.delete');



//Teacher
Route::get('/teacher',[TeacherController::class,'get'])->name('teacher');
Route::get('/teacher/add',[TeacherController::class,'add'])->name('teacher.add');
Route::post('/teacher/create',[TeacherController::class,'create'])->name('teacher.create');
Route::get('/teacher/edit/{id}',[TeacherController::class,'find'])->name('teacher.edit');
Route::put('/teacher/update/{id}',[TeacherController::class,'update'])->name('teacher.update');
Route::get('/teacher/show/{id}',[TeacherController::class,'show'])->name('teacher.show');
Route::delete('/teacher/delete/{id}',[TeacherController::class,'delete'])->name('teacher.delete');

//Student
Route::get('/student',[StudentController::class,'get'])->name('student');
Route::get('/student/add',[StudentController::class,'add'])->name('student.add');
Route::post('student/creat',[StudentController::class,'create'])->name('student.create');
Route::get('/student/edit/{id}',[StudentController::class,'find'])->name('student.edit');
Route::put('student/update/{id}',[StudentController::class,'update'])->name('student.update');
Route::get('student/show/{id}',[StudentController::class,'show'])->name('student.show');
Route::delete('student/delete/{id}',[StudentController::class,'delete'])->name('student.delete');

//Class
Route::get('/class',[ClassController::class,'get'])->name('class');
Route::get('/class/add',[ClassController::class,'add'])->name('class.add');
Route::post('/class/create',[ClassController::class,'create'])->name('class.create');
Route::get('/class/edit/{id}',[ClassController::class,'find'])->name('class.edit');
Route::put('/class/update/{id}',[ClassController::class,'update'])->name('class.update');
Route::get('/class/show/{id}',[ClassController::class,'show'])->name('class.show');
Route::delete('/class/delete/{id}',[ClassController::class,'delete'])->name('class.delete');
//Class teacher
Route::get('class/class_teacher/view/{id}',[ClassController::class,'view'])->name('view');
Route::get('class/class_teacher/addClassTeacher/{id}',[ClassController::class,'addClassTeacher'])->name('addClassTeacher');
Route::post('class/class_teacher/createClassTeacher',[ClassController::class,'createClassTeacher'])->name('createClassTeacher');
Route::get('class/class_teacher/editClassTeacher/{id}',[ClassController::class,'findClassTeacher'])->name('editClassTeacher');
Route::put('class/class_teacher/updateClassTeacher/{id}',[ClassController::class,'updateClassTeacher'])->name('updateClassTeacher');
Route::get('class/class_teacher/showClassTeacher/{id}',[ClassController::class,'showClassTeacher'])->name('showClassTeacher');
Route::delete('class/class_teacher/deleteClassTeacher/{id}',[ClassController::class,'deleteClassTeacher'])->name('deleteClassTeacher');


//Grade
Route::get('/grade',[GradeController::class ,'get'])->name('grade');
Route::get('/api/subjects-by-term/{termId}',[GradeController::class, 'getSubjectsByTerm']);
Route::get('/grades/get-scores/{studentId}/{termId}',[GradeController::class,'getScores']);
Route::get('/grade/add',[GradeController::class,'add'])->name('grade.add');
Route::post('grade/create',[GradeController::class,'create'])->name('grade.create');
Route::get('/grade/{student}/edit', [GradeController::class, 'edit'])->name('grade.edit');
Route::put('/grade/{student}/update', [GradeController::class, 'update'])->name('grade.update');
Route::get('/grade/{student}/show', [GradeController::class, 'show'])->name('grade.show');
Route::delete('/grade/{student}/delete', [GradeController::class, 'delete'])->name('grade.delete');

Route::get('/api/students-by-generation/{genId}', [GradeController::class, 'getStudentsByGeneration']);
Route::get('/api/subjects-by-term/{termId}', [GradeController::class, 'getSubjectsByTerm']);
Route::get('/api/subjects-by-term', [GradeController::class, 'getSubjectsByTerm']);

Route::get('/api/majors-by-generation/{genId}', [GradeController::class, 'getMajorsByGeneration']);
Route::get('/api/students-by-generation-and-major', [GradeController::class, 'getStudentsByGenerationAndMajor']);

//Term
Route::get('/term', [TermController::class, 'get'])->name('term');
Route::get('/term/add',[TermController::class,'add'])->name('term.add');
Route::post('/term/create',[TermController::class,'create'])->name('term.create');
Route::get('/term/edit/{id}',[TermController::class,'find'])->name('term.edit');
Route::put('/term/update/{id}',[TermController::class,'update'])->name('term.update');
Route::get('/term/show/{id}',[TermController::class,'show'])->name('term.show');
Route::delete('/term/delete/{id}',[TermController::class,'delete'])->name('term.delete');

//Genertation
Route::get('/generation' , [GenerationController::class,'get'])->name('generation');
Route::get('/generation/add',[GenerationController::class,'add'])->name('generation.add');
Route::post('/generation/create',[GenerationController::class,'create'])->name('generation.create');
Route::get('/generation/find/{id}',[GenerationController::class,'find'])->name('generation.edit');
Route::put('/generation/update{id}',[GenerationController::class,'update'])->name('generation.update');
Route::get('/generation/show/{id}',[GenerationController::class,'show'])->name('generation.show');
Route::put('/generation/delete{id}',[GenerationController::class,'delete'])->name('generation.delete');


//Employee
Route::get('/employee', [EmployeeController::class, 'get'])
    ->middleware('role:SuperAdmin')
    ->name('employee');

// Route::get('/employee',[EmployeeController::class , 'get'])->name('employee');
Route::get('/employee/add',[EmployeeController::class, 'add'])->name('employee.add');
Route::post('/employee/create',[EmployeeController::class, 'create'])->name('employee.create');
Route::get('/employee/edit/{id}',[EmployeeController::class,'find'])->name('employee.edit');
Route::put('/employee/update/{id}',[EmployeeController::class,'update'])->name('employee.update');
Route::get('/employee/show/{id}',[EmployeeController::class,'show'])->name('employee.show');
Route::delete('/employee/delete/{id}',[EmployeeController::class,'delete'])->name('employee.delete');

//Position 
Route::get('/position',[PositionController::class , 'get'])->name('position');
Route::get('/position/add',[PositionController::class , 'add'])->middleware('role:superAdmin')->name('position.add');
Route::post('/position/create',[PositionController::class, 'create'])->name('position.create');
Route::get('/position/edit/{id}',[PositionController::class,'find'])->name('position.edit');
Route::put('/position/update/{id}',[PositionController::class,'update'])->name('position.update');
Route::get('/position/show/{id}',[PositionController::class,'show'])->name('position.show');
Route::delete('/position/delete/{id}',[PositionController::class, 'delete'])->name('position.delete');

//Role
Route::get('/role',[RoleController::class,'get'])->name('role');
Route::get('/role/add',[RoleController::class, 'add'])->name('role.add');
Route::post('/role/create',[RoleController::class,'create'])->name('role.create');
Route::get('/role/edit/{id}',[RoleController::class,'find'])->name('role.edit');
Route::put('/role/update/{id}',[RoleController::class,'update'])->name('role.update');
Route::get('/role/show/{id}',[RoleController::class,'show'])->name('role.show');
Route::delete('role/show/{id}',[RoleController::class,'delete'])->name('role.delete');




//User
Route::get('/user',[UserController::class , 'get'])->name('user');
//Register 
Route::get('/user/add',[UserController::class,'add'])->name('user.add');
Route::post('/user/create',[UserController::class,'create'])->name('user.create');
//Edit 
Route::get('/user/edit/{id}',[UserController::class, 'find'])->name('user.edit');
Route::put('/user/update/{id}',[UserController::class, 'update'])->name('user.update');
//Delete
Route::get('/user/show/{id}',[UserController::class,'show'])->name('user.show');
Route::delete('/user/delete/{id}',[UserController::class,'delete'])->name('user.delete');


//Major
Route::get('/major',[MajorController::class, 'get'])->name('major');
Route::get('/major/add',[MajorController::class, 'add'])->name('major.add');
Route::post('/major/create',[MajorController::class, 'create'])->name('major.create');
Route::get('/major/edit/{id}',[MajorController::class,'find'])->name('major.edit');
Route::put('major/update/{id}',[MajorController::class,'update'])->name('major.update');
Route::get('/major/show/{id}',[MajorController::class,'show'])->name('major.show');
Route::delete('major/delete/{id}',[MajorController::class,'delete'])->name('major.delete');

//Dashboard
Route::get('/dashboard/{year?}', [DashboardController::class, 'dashboard'])
    ->name('dashboard');

// API route for fetching enrollment data for AJAX updates
Route::get('/api/enrollment-data/{year}', [DashboardController::class, 'getEnrollmentData'])
    ->name('api.enrollment.data');

// Additional route for changing dashboard year via AJAX
Route::post('/dashboard/change-year', [DashboardController::class, 'changeYear'])
    ->name('dashboard.change-year');


Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
