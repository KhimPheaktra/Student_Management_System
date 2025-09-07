<?php

namespace App\Http\Controllers;

use App\Models\PositionModel;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
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
             $positions = PositionModel::where('status','ACT')
             ->where(function($query) use ($request){
                 $search = $request->search;
                 if($search){
                     $query->where('name','like',"%$search%");
                 }
             })
             ->orderBy($sort,$order)
             ->paginate(10);
             return view('position.list',compact('positions'));
        }
            catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
         try{

             return view('position.add');
        }
             catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function create(Request $request){
        try{
            $positions = new PositionModel();
            $positions->name = $request->name;
            $positions->save();
            Alert::success('Position Added Successfully');
            return redirect()->route('position');
        }
             catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
      
    }

    protected function find($id){
         try{

             $positions = PositionModel::find($id);
             return view('position.edit',compact('positions'));
        }
             catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update(Request $request,$id){
        try{
            $positions = PositionModel::find($id);
            $positions->name = $request->name;
            $positions->save();
            Alert::success('Position Edited Successfully');
            return redirect()->route('position');
        }
             catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
        
    }

    protected function show($id){
         try{

             $positions = PositionModel::where('id',$id)->first();
             return view('position.delete',compact('positions'));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function delete($id){

        try{
            DB::table('positions')
            ->where('id',$id)
            ->update(['status' => 'DEL']);
            Alert::success('Position Delete Successfully');
            return redirect()->route('position');
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
       
    }
}
