<?php

namespace App\Http\Controllers;

use App\Models\MajorModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class MajorController extends Controller
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
           
           $allowedSorts = ['id', 'name'];
           $allowedOrders = ['asc','desc'];
           if(!in_array($sort,$allowedSorts)){
               $sort = 'id';
           }
           if(!in_array($order,$allowedOrders)){
               $order = 'asc';
           }
           $majors = MajorModel::where('status','ACT')
           ->where(function($query) use ($request){
               $search = $request->search;
               if($search){
                   $query->where('name','like',"%$search%");
               }
           })
           ->orderBy($sort,$order)
           ->paginate(10);
           return view('major.list',compact('majors'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
         try{

             return view('major.add');
        }
          catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    
    protected function create(Request $request){
        try{
             $majors = new MajorModel();
        $majors->name = $request->name;
        $majors->save();

        if($majors != null){
            Alert::success('Major Added Successfully');
        }
        else{
            Alert::error('Something When Wrong');
        }

        return redirect()->route('major');
        }
      catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
       
    }

    protected function find($id){
         try{

             $majors = MajorModel::find($id);
             return view('major.edit',compact('majors'));
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update(Request $request , $id){
        try{
            $majors = MajorModel::find($id);
            $majors->name = $request->name;
            $majors->save();

            if($majors != null){
                Alert::success('Major Edited Successfully');
            }
            else{
                Alert::error('Something When Wrong');
            }

            return redirect()->route('major');
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
       
    }

    protected function show($id){
         try{

             $majors = MajorModel::where('id',$id)->first();
             return view('major.delete',compact('majors'));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
         }
    }

    protected function delete($id){
         try{

             DB::table('majors')
             ->where('id',$id)
             ->update(['status'=>'DEL']);
             alert::success('Major Deleted Successfully');
             return redirect()->route('major');
        }
           catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
}
