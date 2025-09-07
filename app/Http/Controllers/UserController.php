<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\RoleModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
          $users = User::where('status','ACT')
            ->where(function($query) use ($request){
              $search = $request->search;
              if($search){
                  $query->where('name','like',"%$search%");
              }
          })
          ->orderBy($sort,$order)
          ->paginate(10);;
          return view('user.list',compact('users'));

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function add(){
       try{
           $users = User::all();
           $employees = EmployeeModel::where('status','ACT')->get();
           $roles = RoleModel::all();
           return view('user.add',compact('employees','roles','users'));

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }


  
    public function create(Request $request)
    {
       try{
           $request->validate([
               'name' => ['required', 'string', 'max:255'],
               'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
               'password' => ['required', 'confirmed', Rules\Password::defaults()],
           ]);
   
           $users = User::create([
               'name' => $request->name,
               'email' => $request->email,
               'password' => Hash::make($request->password),
           ]);
   
           Alert::success('User Added Successfully');
           return redirect()->route('user');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function find($id){
        try{

            $users = User::findOrFail($id);
            $employees = EmployeeModel::where('status','ACT')->get();
            $roles = RoleModel::all();
            return view('user.edit',compact('users','roles','employees'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }

    protected function update(Request $request,$id){
        try{
            $users = User::findOrFail($id);
    
      $request->validate([
          'name' => ['required', 'string', 'max:255'],
          'email' => [
              'required', 
              'string', 
              'email', 
              'max:255', 
              Rule::unique('users')->ignore($users->id), // Ignore the current user's email
          ],
          'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
      ]);
    
      $users->name = $request->name;
      $users->email = $request->email;
    
      if ($request->filled('password')) {
          $users->password = Hash::make($request->password);
      }
    
      $users->save();
    
      Alert::success('User Edited Successfully');
      return redirect()->route('user');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }


    protected function show($id){
       try{

           $users = User::where('id',$id)->first();
           $employees = EmployeeModel::where('status','ACT')->get();
           $roles = RoleModel::all();
           return view('user.delete',compact('users','roles','employees'));
        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
    
    protected function delete($id){
       try{
           $userToDelete = User::with('role')->findOrFail($id);
           $currentUser = User::with('role')->find(Auth::id());
   
       // SuperAdmin cannot delete themselves
       if ($userToDelete->id === $currentUser->id && $currentUser->hasRole('superAdmin')) {
           Alert::error('Forbidden', 'Super Admin cannot delete their own account.');
           return redirect()->route('user');
       }
   
       // Soft delete
           $userToDelete->status = 'DEL';
           $userToDelete->save();
   
       // If user deleted themselves, force logout
       if ($userToDelete->id === $currentUser->id) {
           Auth::logout();
           session()->invalidate();
           session()->regenerateToken();
           return redirect('/login')->withErrors(['email' => 'Your account has been deleted.']);
       }
   
       Alert::success('Success', 'User deleted successfully.');
       return redirect()->route('user');

        }
        catch (\Exception $ex) {
        Alert::error('Something went wrong: ' . $ex->getMessage())->persistent(true);
        return redirect()->back();
        }
    }
}
