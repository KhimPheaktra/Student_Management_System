<?php

namespace App\Http\Controllers;

use App\Models\GenderModel;
use App\Models\PositionModel;
use App\Models\ProvinceModel;
use App\Models\SubjectTypeModel;
use App\Models\TeacherModel;
use App\Models\TeacherSalaryModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class TeacherController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function get(Request $request){
        try{

            $sort = $request->sort ?? 'id';
            $order = $request->order ?? 'asc';
            
            $allowedSorts = ['id', 'name'];
            $allowedOrders = ['asc','desc'];
            if(!in_array($sort,$allowedSorts)){
                $sort = 'id';
            }
            if(!in_array($order,$allowedOrders)){
                $order = 'asc';
            }
            $teachers = TeacherModel::where('status', 'ACT')
            ->where(function($query) use ($request){
                $search = $request->search;
                if($search){
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%$search%");
                }
            })
            ->orderBy($sort,$order)
            ->paginate(10);
            return View('teacher.list',['teachers'=>$teachers,]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function add(){
        try{

            $teacher_salary = TeacherSalaryModel::all();
            $positions = PositionModel::All();
            $genders = GenderModel::All();
            $provinces = ProvinceModel::where('status','ACT')->get();
            return View('teacher.add',['teacher_salary'=>$teacher_salary,'positions'=>$positions,'genders'=>$genders,'provinces' => $provinces]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    public function create(Request $request)
    {
        try{

            // 1. Create the Salary Record First
           $teacherSalary = new TeacherSalaryModel();
           $teacherSalary->salary = $request->salary;
           $teacherSalary->save();
    
           $teachers = new TeacherModel();
           $teachers->first_name = $request->first_name;
           $teachers->last_name =  $request->last_name;
           $teachers->dob = $request->dob;
           $teachers->pob = $request->pob;
           $teachers->gender_id = $request->gender_id;
           $teachers->phone = $request->phone;
           $teachers->position_id = $request->position_id;
           $teachers->salary_id = $teacherSalary->id; // Use the ID of the *newly created* salary record
           $image  = $request->file('image');
           if($image != null){
               $imageName = ('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
               $imagepath ='image/teacher/';
               $imageUrl = $imagepath.$imageName;
               $image->move($imagepath,$imageName);
           }
           else{
               $imageUrl= null;
           }
           $teachers->image =$imageUrl;
           $teachers->save();
           Alert::success("Teacher Added Successfully");
           return redirect()->route('teacher');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }

    }

    protected function find($id){
        try{

            $teacherSalary = TeacherSalaryModel::all();
            $positions = PositionModel::all();
            $teachers =  TeacherModel::find($id);
            $genders = GenderModel::All();
            $provinces = ProvinceModel::where('status','ACT')->get();
            return View('teacher.edit' ,['teacherSalary'=>$teacherSalary,'positions'=>$positions,'teachers'=>$teachers , 'genders'=>$genders,'provinces'=>$provinces]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update($id,Request $request){

       try{
           // Assuming you're passing student_id in the request
           $teacherId = $request->id;
           // First, find the specific student to update
           $teachers = TeacherModel::where('id', $teacherId)->first();
           // Check if the student code is unique (excluding the current student)
           $validator = Validator::make($request->only('phone'), [
               'phone' => ['nullable', 'string', Rule::unique('students', 'phone')->ignore($teachers->id)],
           ]);
           
           if ($validator->fails()) {
               Alert::error('Student Code already exists!');
               return redirect()->back()->withErrors($validator)->withInput();
           }
         // 1. Create the Salary Record First
         $teacherSalary = TeacherSalaryModel::find($id);
         $teacherSalary->salary = $request->salary;
         $teacherSalary->save();
   
         $teachers = TeacherModel::find($id);
         $teachers->first_name = $request->first_name;
         $teachers->last_name =  $request->last_name;
         $teachers->dob = $request->dob;
         $teachers->pob = $request->pob;
         $teachers->gender_id = $request->gender_id;
         $teachers->phone = $request->phone;
         $teachers->position_id = $request->position_id;
         $teachers->salary_id = $teacherSalary->id; 
         $old_image = $request->input('old_image'); // Get the old image path from hidden input
         $image = $request->file('image');
         
         if ($image) {
             // Delete the old image if it exists
             if ($old_image && file_exists(public_path($old_image))) {
                 unlink(public_path($old_image));
             }
         
             // Save the new image
             $imageName = date('Y_m_d_H_i_s') . '.' . $image->getClientOriginalExtension();
             $imagePath = 'image/teacher/';
             $imageUrl = $imagePath . $imageName;
             $image->move(public_path($imagePath), $imageName);
             $teachers->image = $imageUrl; // Update the teacher image with the new one
         } else {
             // Keep the old image if no new image is uploaded
             $teachers->image = $old_image;
         }
         $teachers->save();
         Alert::success("Teacher Edited Successfully");
         return redirect()->route('teacher');

        }
       catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function show($id)
    {
       try{

           $teacherSalary = TeacherSalaryModel::all();
           $positions = PositionModel::all();
           $teachers = TeacherModel::where('id',$id)->first();
           $genders = GenderModel::All();
           $provinces = ProvinceModel::where('status','ACT')->get();
           return view('teacher.delete',['teacherSalary'=>$teacherSalary,'positions'=>$positions,'teachers'=>$teachers, 'genders'=>$genders,'provinces'=>$provinces]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function delete($id){
         try{

             // 1. Get the teacher's salary_id
           $teacher = DB::table('teachers')->where('id', $id)->first(); // Use first() to get a single object
           if ($teacher) {
               $salaryId = $teacher->salary_id;  // Access the salary_id
               
               // 2. Update the teacher's record
               DB::table('teachers')
                   ->where('id', $id)
                   ->update(['status' => 'DEL']);
               
               // 3. Update the teacher's salary record (if it exists)
               if ($salaryId) { // Check if there's a salary_id
                   DB::table('teacher_salary')
                       ->where('id', $salaryId)
                       ->update(['status' => 'DEL']);
               }
       
               Alert::success('Teacher Deleted Successfully'); // improved
               return redirect()->route('teacher');
           } else {
               Alert::error('Teacher Not Found'); // add error
               return redirect()->route('teacher'); // add redirect
           }
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

}
