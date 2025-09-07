<?php

namespace App\Http\Controllers;

use App\Models\GenModel;
use App\Models\TermModel;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class TermController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get(Request $request)
    {

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
            $terms = TermModel::where('status','ACT')
            ->where(function($query) use ($request){
                $search = $request->search;
                if($search){
                    $query->where('name','like',"%$search%");
                }
            })
            ->orderBy($sort,$order)
            ->paginate(10);
            return view('term.list', ['terms' => $terms]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    
    protected function add(){
       try{

           $terms = TermModel::all();
           return view('term.add', ['terms' => $terms]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function create(Request $request){
       try{

           $terms = new TermModel();
           $terms->name = $request->name;
           $terms->start_date = $request->start_date;
           $terms->end_date = $request->end_date;
           $terms->save();
           Alert::success('Term Added Successfully');
           return redirect()->route('term');
        }
       catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function find($id){
       try{

           $terms = TermModel::find($id);
           return view('term.edit',compact('terms'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update(Request $request,$id){
        try{
            $terms = TermModel::find($id);
            $terms->name = $request->name;
            $terms->start_date = $request->start_date;
            $terms->end_date = $request->end_date;
            $terms->save();
            Alert::success('Term Edited Successfully');
            return redirect()->route('term');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function show($id){
       try{
           $terms = TermModel::where('id',$id)->first();
           return view('term.delete',compact('terms'));

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function delete($id){
       try{
           DB::table('terms')
           ->where('id',$id)
           ->update(['status' => 'DEL']);
           Alert::success('Term Deleted Successfully');
           return redirect()->route('term');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
}
