<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassTeacherModel;
use App\Models\ClassViewModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ClassController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function get(Request $request){

        try{

            $classes = ClassModel::where('status','ACT')
            ->where(function($query) use ($request){
               $search = $request->search;
               if($search){
                   $query->where('name','like',"%$search%");
               }
            })
            ->paginate(10);
            return view('class.list',['classes' => $classes]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
         try{

             return view('class.add');
        }
       catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function create(Request $request){
         try{

             $classes = new ClassModel();
             $classes->name = $request->name;
             $classes->save();
             Alert::success('Class Added Successfully');
             return redirect()->route('class');
        }
       catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function find($id){
         try{

             $classes = ClassModel::find($id);
             return view('class.edit',['classes'=>$classes]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function update(Request $request){
         try{

             $classes = ClassModel::where('status','ACT')->get();
             $classes->name = $request->name;
             $classes->save();
             Alert::success('Class Edited Successfully');
             return redirect()->route('class');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function show($id){
         try{

             $classes = ClassModel::where('id',$id)->first();
             return view('class.delete',['classes'=>$classes]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function delete($id){
         try{

             DB::table('classes')
             ->where('id',$id)
             ->update(['status' => 'DEL']);
             Alert::success('Class Deleted Successfully');
             return redirect()->route('class');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    //Class Teacher
    protected function View(Request $request ,$id){
        try{

            $classes = ClassModel::find($id);
            $classViews = ClassViewModel::where('id', $id)->get(); 
            $classTeachers = ClassTeacherModel::where('class_id', $id)
            ->when($request->search, function ($query, $search) {
                $query->whereHas('teacher', function ($q) use ($search) {
                    $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$search%");
                });
            })
            ->with('teacher')
             ->paginate(10);
            return view('class.class_teacher.view',['classes' => $classes,'classViews' => $classViews,'classTeachers' => $classTeachers]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }
    
    protected function addClassTeacher($id){
         try{

             $classTeachers = ClassTeacherModel::where('id', $id)->first();
             $teachers = TeacherModel::where('status','ACT')->get();
             $subjects = SubjectModel::where('status','ACT')->get();
             $classViews = ClassViewModel::where('id', $id)->get(); 
             $classes = ClassModel::find($id);
             return view('class.class_teacher.add_class_teacher',
             ['teachers' => $teachers,'subjects' => $subjects,
             'classes' => $classes,'classViews' => $classViews,'classTeachers'=> $classTeachers]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    protected function createClassTeacher(Request $request){
         try{

             $classTeachers = new ClassTeacherModel();
             $classTeachers->class_id = $request->class_id;
             $classTeachers->teacher_id = $request->teacher_id;
             $classTeachers->subject_id = $request->subject_id;
             $classTeachers->save();
             Alert::success('Added Teacher To Class Successfully');
             return redirect()->route('view' ,['id' => $request->class_id]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }


    protected function findClassTeacher($id){
         try{

             $teachers = TeacherModel::where('status','ACT')->get();
             $subjects = SubjectModel::where('status','ACT')->get();
             $classViews = ClassViewModel::where('id', $id)->get(); 
             $classes = ClassModel::find($id);
             $classTeachers = ClassTeacherModel::where('id', $id)->first();
             return view('class.class_teacher.edit_class_teacher',
             ['teachers' => $teachers,'subjects' => $subjects,
             'classes' => $classes,'classViews' => $classViews,'classTeachers'=> $classTeachers]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }


    protected function updateClassTeacher(Request $request, $id){
         try{

             $classTeachers = ClassTeacherModel::find($id);
             if(!$classTeachers) {
                 Alert::error('Error', 'Class Teacher not found');
                 return redirect()->back();
             }
             
             $classTeachers->class_id = $request->class_id;
             $classTeachers->teacher_id = $request->teacher_id;
             $classTeachers->subject_id = $request->subject_id;
             $classTeachers->save();
             
             Alert::success('Edited Teacher To Class Successfully');
             return redirect()->route('view', ['id' => $request->class_id]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    protected function showClassTeacher($id){
         try{

             $classTeachers = ClassTeacherModel::where('id', $id)->first();
             $teachers = TeacherModel::where('status','ACT')->get();
             $subjects = SubjectModel::where('status','ACT')->get();
             $class_id = $classTeachers->class_id;
             $classes = ClassModel::find($class_id);
             $classViews = ClassViewModel::where('id', $class_id)->get();
             
             return view('class.class_teacher.delete_class_teacher', [
                 'teachers' => $teachers,
                 'subjects' => $subjects,
                 'classes' => $classes,
                 'classViews' => $classViews,
                 'classTeachers' => $classTeachers
             ]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }
    protected function deleteClassTeacher($id) {
         try{

             // Get the class_id before deleting the record
             $classTeacher = DB::table('class_teachers')->where('id', $id)->first();
             
             if (!$classTeacher) {
                 Alert::error('Error', 'Class Teacher not found');
                 return redirect()->back();
             }
             
             $class_id = $classTeacher->class_id;
             
             // Delete the record
             DB::table('class_teachers')->where('id', $id)->delete();
             
             Alert::success('Deleted Class Teacher Successfully');
             return redirect()->route('view', ['id' => $class_id]);
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

}
