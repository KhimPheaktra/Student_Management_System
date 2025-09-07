<?php

namespace App\Http\Controllers;

use App\Models\GenderModel;
use App\Models\GenerationModel;
use App\Models\GenModel;
use App\Models\MajorModel;
use App\Models\ProvinceModel;
use App\Models\StudentModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    protected function get(Request $request){
         try{

             $sort = $request->sort ?? 'id';
             $order = $request->order ?? 'asc';
             
             $allowedSorts = ['id','student_code','first_name','last_name'];
             $allowedOrders = ['asc','desc'];
             if(!in_array($sort,$allowedSorts)){
                 $sort = 'id';
             }
             if(!in_array($order,$allowedOrders)){
                 $order = 'asc';
             }
             $students = StudentModel::where('status','ACT')
             ->where(function($query) use ($request){
                 $search = $request->search;
                 if($search){
                     $query->where(DB::raw("CONCAT(first_name,' ',last_name)"),'like',"%$search%");
                 }
             })
             ->orderBy($sort,$order)
             ->paginate(10);
             return view('student.list',['students'=> $students]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
           try{

               $majors = MajorModel::where('status','ACT')->get();
               $gens = GenerationModel::all();
               $genders = GenderModel::All();
               $provinces = ProvinceModel::where('status','ACT')->get();
               return view('student.add',['majors' => $majors,'gens' => $gens,'genders'=>$genders,'provinces' => $provinces]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage());
        return redirect()->back();
        }
    }
    protected function create(Request $request){
        $validator = Validator::make($request->only('student_code', 'phone'), [
            'student_code' => ['required', 'string', Rule::unique('students', 'student_code')],
            'phone' => ['required', 'string', Rule::unique('students', 'phone')],
        ]);
           try{

               if ($validator->fails()) {
                   $errors = $validator->errors();
               
                   if ($errors->has('student_code')) {
                       Alert::error('Student Code already exists!');
                   } elseif ($errors->has('phone')) {
                       Alert::error('Student phone number already exists!');
                   }
               
                   return redirect()->back()->withErrors($validator)->withInput();
               }
               
               $students = new StudentModel();
               $students->student_code = $request->student_code;
               $students->first_name = $request->first_name;
               $students->last_name = $request->last_name;
               $students->dob = $request->dob;
               $students->pob = $request->pob;
               $students->phone = $request->phone;
               $students->gender_id = $request->gender_id;
               $students->gen_id = $request->gen_id;
               $students->parent_phone = $request->parent_phone;
               $students->major_id = $request->major_id;
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
               $students->image =$imageUrl;
               $students->enroll_at = Carbon::parse($request->enroll_at)->toDateString();
               $students->save();
               Alert::success('Student Added Successfully');
               return redirect()->route('student');
        }
       catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
    }

    protected function find($id){
           try{

               $majors = MajorModel::where('status','ACT')->get();
               $gens = GenerationModel::all();
               $students = StudentModel::find($id);
               $genders = GenderModel::All();
               $provinces = ProvinceModel::where('status','ACT')->get();
               return view('student.edit',['majors'=>$majors,'gens'=>$gens,'students' => $students,'genders'=>$genders,'provinces'=>$provinces]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage());
        return redirect()->back();
        }
    }
    protected function update(Request $request){
        
        // Assuming you're passing student_id in the request
        $studentId = $request->id;
        // First, find the specific student to update
        $students = StudentModel::where('id', $studentId)->first();
        // Check if the student code is unique (excluding the current student)
        $validator = Validator::make($request->only('student_code','phone'), [
            'student_code' => ['required', 'string', Rule::unique('students', 'student_code')->ignore($students->id)],
            'phone' => ['nullable', 'string', Rule::unique('students', 'phone')->ignore($students->id)],
        ]);
           try{

               if ($validator->fails()) {
                   Alert::error('Student Code already exists!');
                   return redirect()->back()->withErrors($validator)->withInput();
               }
               // $students = StudentModel::where('status','ACT')->get();
               $students->student_code = $request->student_code;
               $students->last_name = $request->last_name;
               $students->dob = $request->dob;
               $students->pob = $request->pob;
               $students->phone = $request->phone;
               $students->gender_id = $request->gender_id;
               $students->gen_id = $request->gen_id;
               $students->major_id = $request->major_id;
               $students->parent_phone = $request->parent_phone;
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
                   $students->image = $imageUrl; // Update the teacher image with the new one
               } else {
                   // Keep the old image if no new image is uploaded
                   $students->image = $old_image;
               }
               $students->enroll_at = Carbon::parse($request->enroll_at)->toDateString();
               $students->save();
               Alert::success('Student Edited Successfully');
               return redirect()->route('student',);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
        
    }

    protected function show($id){
           try{

               $majors = MajorModel::where('status','ACT')->get();
               $gens = GenerationModel::all();
               $students = StudentModel::where('id',$id)->first();
               $genders = GenderModel::All();
               $provinces = ProvinceModel::where('status','ACT')->get();
               return view('student.delete',['majors'=>$majors,'gens'=>$gens,'students'=>$students,'genders'=>$genders,'provinces'=>$provinces]);
        }
      catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function delete($id){
        try{

               DB::table('students')
               ->where('id',$id)
               ->update(['status' => 'DEL']);
               Alert::success('Student Deleted Successfully');
               return redirect()->route('student');
        }
    catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
    }

 
}
