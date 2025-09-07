<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }
    


    protected function get(Request $request){
         try{

             $search = $request->search;
             $sort = $request->sort ?? 'id';        // default sort column
             $order = $request->order ?? 'asc';    // default sort direction
     
             // Validate allowed sort fields and order
             $allowedSorts = ['id', 'name'];
             $allowedOrders = ['asc', 'desc'];
     
             if (!in_array($sort, $allowedSorts)) {
                 $sort = 'id';
             }
     
             if (!in_array($order, $allowedOrders)) {
                 $order = 'asc';
             }
     
             $roles = RoleModel::where('status','ACT')
             ->where(function($query) use ($request){
                 $search = $request->search;
                 if($search){
                     $query->where('name','like',"%$search%");
                 }
             })
             ->orderBy($sort, $order)
             ->paginate(10);
             return view('role.list',compact('roles'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
        $roles = RoleModel::where('status','ACT')->get();
        return view('role.add',compact('roles'));
    }

    protected function create(Request $request){
        try{
            $roles = new RoleModel();
            $roles->name = $request->name;
            $roles->save();
            if($roles != null){
                 Alert::success('Role Added Successfully');
                return redirect()->route('role');
            }
           else{
             Alert::error('Something when wrong !','Cant Add New Role');
           }
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
      
    }
    protected function find($id){
         try{

             $roles = RoleModel::find($id);
             return view('role.edit',compact('roles'));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }

    }

    protected function update(Request $request,$id){
        try{
            $roles = RoleModel::find($id);
            $roles->name = $request->name;
            $roles->save();
            if($roles != null){
                Alert::success('Role Edited Successfully');
                return redirect()->route('role');
            }
            else{
                 Alert::error('Something when wrong !','Cant Edit Role');
            }
        }
        catch(Exception $ex){
             Alert::error('Something when wrong !' . $ex->getMessage())->persistent(true);
        }
      
    }

    protected function show($id){
         try{
             $roles = RoleModel::where('id',$id)->first();
             return view('role.delete',compact('roles'));

        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    protected function delete($id){
        try{
             DB::table('roles')
            ->where('id',$id)
            ->update(['status' => 'DEL']);
            Alert::success('Role Deleted Successfully');
            return redirect()->route('role');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
      
    }
    
    
}
