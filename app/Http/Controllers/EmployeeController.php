<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryModel;
use App\Models\GenderModel;
use App\Models\PositionModel;
use App\Models\ProvinceModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    //

    public function __construct(){
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
            $employees = EmployeeModel::where('status','ACT')
            ->where(function($query) use ($request){
                $search = $request->search;
                if($search){
                    $query->where(DB::raw("CONCAT(first_name,' ',last_name)"));
                }
            })
            ->orderBy($sort,$order)
            ->paginate(10);
            return view('employee.list',compact('employees'));
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
       
    }
    protected function add(){
        try{
            $employee_salary = EmployeeSalaryModel::all();
            $positions = PositionModel::all();
            $genders = GenderModel::all();
            $provinces = ProvinceModel::all();
            return view('employee.add',compact('employee_salary','positions','genders','provinces'));
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }


        
    }

    protected function create(Request $request){
        try{
 // 1. Create the Salary Record First
        $employeeSalary = new EmployeeSalaryModel();
        $employeeSalary->salary = $request->salary;  // changed from salary to salary_name
        $employeeSalary->save();
        

        $employees = new EmployeeModel();
        $employees->first_name = $request->first_name;
        $employees->last_name = $request->last_name;
        $employees->position_id = $request->position_id;
        $employees->dob = $request->dob;
        $employees->gender_id = $request->gender_id;
        $employees->pob = $request->pob;
        $employees->phone = $request->phone;
        $employees->salary_id = $employeeSalary->id; 
        $image = $request->file('image');
        if($image != null){
            $imageName = ('Y_M_d_H_i_s') . '.' . $image->getClientOriginalExtension();
            $imagepath = 'image/employee/';
            $imageUrl = $imagepath.$imageName;
            $image->move($imagepath,$imageName);
        }
        else{
            $imageUrl = null;
        }
        $employees->image = $imageUrl;
        $employees->save();
        if($employees != null){
            Alert::success('Employee Added Successfully');
            return redirect()->route('employee');
        }
        else{
              Alert::error('Something when wrong !','Cant Add Employee');
        }
     
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
       
    }


    protected function find($id){

        try{

            $employee_salary = EmployeeSalaryModel::all();
            $positions = PositionModel::all();
            $genders = GenderModel::all();
            $provinces = ProvinceModel::all();
            $employees = EmployeeModel::find($id);
            return view('employee.edit',compact('employee_salary','positions','genders','provinces','employees'));
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }

    }

    protected function update(Request $request,$id){

        try{
$employeeId = $request->id;
        // First, find the specific student to update
        $employees = EmployeeModel::where('id', $employeeId)->first();
        // Check if the student code is unique (excluding the current student)
        $validator = Validator::make($request->only('phone'), [
            'phone' => ['nullable', 'string', Rule::unique('employees', 'phone')->ignore($employees->id)],
        ]);
        $employeeSalary = EmployeeSalaryModel::find($id);
        $employeeSalary->salary = $request->salary;  // changed from salary to salary_name
        $employeeSalary->save();

        $employees = EmployeeModel::find($id);
        $employees->first_name = $request->first_name;
        $employees->last_name = $request->last_name;
        $employees->position_id = $request->position_id;
        $employees->dob = $request->dob;
        $employees->gender_id = $request->gender_id;
        $employees->pob = $request->pob;
        $employees->phone = $request->phone;
        $employees->salary_id = $employeeSalary->id; 
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
            $employees->image = $imageUrl; // Update the teacher image with the new one
        } else {
            // Keep the old image if no new image is uploaded
            $employees->image = $old_image;
        }
        $employees->save();
        if($employees != null){
            Alert::success('Employee Edited Successfully');
            return redirect()->route('employee');
        }
        else{
            Alert::error('Something when wrong !','Cant Edit Employee');
        }
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
    }

    protected function show($id){
        try{

            $employee_salary = EmployeeSalaryModel::all();
            $positions = PositionModel::all();
            $genders = GenderModel::all();
            $provinces = ProvinceModel::all();
            $employees = EmployeeModel::where('id',$id)->first();
            return view('employee.delete',compact('employee_salary','positions','genders','provinces','employees'));
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }

    }

    protected function delete($id){

        try{
            $employees = DB::table('employees')->where('id',$id)->first();
        if($employees){
            $salaryId = $employees->salary_id;
            DB::table('employees')
            ->where('id',$id)
            ->update(['status'=>'DEL']);

            if($salaryId){
                DB::table('employee_salary')
                ->where('id',$salaryId)
                ->update(['status' => 'DEL']);
            }

            Alert::success('Employee Deleted Successfully');
            return redirect()->route('employee');
        }
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
    }
}
