<?php

namespace App\Http\Controllers;

use App\Models\GenerationModel;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class GenerationController extends Controller
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
            $generations = GenerationModel::where('status','ACT')
            ->where(function($query) use ($request){
                $search = $request->search;
                if($search){
                    $query::where('gen','like',"%$search%");
                }
            })
            ->orderBy($sort,$order)
            ->paginate(10);
            return view('generation.list',compact('generations'));

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
    }
    }

    protected function add(){
        try{
             return view('generation.add');
        }   
        catch(Exception $ex){
            Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
            return redirect()->back();
        }
       
    }

    protected function create(Request $request)
{
    try {
        // 1. Extract just the year (e.g. “2026”) from whatever the user submitted.
        //    If your form field is a date picker that returns “YYYY‐MM‐DD”,
        //    this ensures only “YYYY” is stored.
        $yearOnly = date('Y', strtotime($request->year));

        // 2. Check for duplicate gen before saving.
        if (GenerationModel::where('gen', $request->gen)->exists()) {
            Alert::error('Generation “' . $request->gen . '” already exists.');
            return redirect()->back()->withInput();
        }

        // 3. Everything’s good—create the record.
        $generations = new GenerationModel();
        $generations->gen  = $request->gen;
        $generations->year = $yearOnly;
        $generations->save();

        Alert::success('Generation Added Successfully');
        return redirect()->route('generation');
    }
    catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back()->withInput();
    }
}

    protected function find($id){
        try{
            
            $generations = GenerationModel::find($id);
            return view('generation.edit',compact('generations'));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update(Request $request, $id)
{
    try {
        // 1. Same year‐only extraction
        $yearOnly = date('Y', strtotime($request->year));

        // 2. Prevent “gen” collision with any other record
        $duplicateCheck = GenerationModel::where('gen', $request->gen)
                            ->where('id', '!=', $id)
                            ->exists();
        if ($duplicateCheck) {
            Alert::error('Another generation already uses “' . $request->gen . '”.');
            return redirect()->back()->withInput();
        }

        // 3. Find the existing record, update gen+year, then save.
        $generations = GenerationModel::findOrFail($id);
        $generations->gen  = $request->gen;
        $generations->year = $yearOnly;
        $generations->save();

        Alert::success('Generation Edited Successfully');
        return redirect()->route('generation');
    }
    catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back()->withInput();
    }
}

    protected function show($id){
         try{
            
             $generations = GenerationModel::where('id',$id)->first();
             return view('generation.delete',compact('generations'));
        }
         catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    protected function delete($id){
        try{
            DB::table('generations')
            ->where('id',$id)
            ->update(['status' => 'DEL']);
            return redirect()->route('generation');
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
}
