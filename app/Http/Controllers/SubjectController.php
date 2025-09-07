<?php

namespace App\Http\Controllers;

use App\Models\MajorModel;
use Illuminate\Http\Request;
use App\Models\subject;
use App\Models\SubjectModel;
use App\Models\SubjectTypeModel;
use App\Models\TermModel;
use Exception;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;


class SubjectController extends Controller
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
               $terms = TermModel::where('status','ACT')->get();
               $subjects = SubjectModel::where('status','ACT')
               ->where(function($query) use ($request) {
                   $search = $request->search;
                   if ($search) {
                       $query->where('name', 'like', "%$search%");
                   }
               })
               ->orderBy($sort,$order)
               ->paginate(10);
       
               return View('subject.list',['subjects'=>$subjects,'terms' => $terms]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
        try{

            $majors = MajorModel::where('status','ACT')->get();
            $terms = TermModel::where('status','ACT')->get();
            return  View('subject.add',compact('majors','terms'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    
    protected function create(Request $request){
        try{

            $subjects = new SubjectModel;
            $subjects->name = $request->name;
            $subjects->full_score = $request->full_score;
            $subjects->term_id = $request->term_id;
            $subjects->major_id = $request->major_id;
            $subjects->save();
            Alert::success('Subject Added Successfully');
            return redirect()->route('subject');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    
    protected function find($id){
        try{

            $majors = MajorModel::where('status','ACT')->get();
            $subjects = SubjectModel::find($id);
            $terms = TermModel::where('status','ACT')->get();
            return view('subject.edit',['majors'=>$majors,'subjects'=>$subjects,'terms'=>$terms]);
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update($id,Request $request){
        try{
            $subjects = SubjectModel::find($id);
            $subjects->name = $request->name;
            $subjects->full_score = $request->full_score;
            $subjects->term_id = $request->term_id;
            $subjects->major_id = $request->major_id;
            $subjects->save();
            Alert::success('Subject Edited Successfully');
            return redirect()->route('subject');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function show($id){
        try{

            $majors = MajorModel::where('status','ACT')->get();
            $subjects = SubjectModel::where('id',$id)->first();
            $terms = TermModel::where('status','ACT')->get();
            return view('subject.delete',compact('majors','subjects','terms'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function delete($id){
        try{

            DB::table('subjects')
            ->where('id', $id)
            ->update(['status' => 'DEL']);
            Alert::success('Subject Deleted Successfully');
            return redirect()->route('subject');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    
    }
    // protected function search(Request $request)
    // {
    //     $search = $request->search;
    
    //     $subjects = SubjectModel::where(function($query) use ($search) {
    //         $query->where('subjectName', 'like', "%$search%")
    //               ->orWhereHas('subjectType', function($query) use ($search) {
    //                   // Use the 'name' field from the related SubjectTypeModel
    //                   $query->where('name', 'like', "%$search%"); 
    //               });
    //     })
    //     ->paginate(10); 
    
    //     return view('subject.list', ['search'=>$search , 'subjects'=>$subjects]);
    // }
    
}
