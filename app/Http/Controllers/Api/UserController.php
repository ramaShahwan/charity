<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Bill;
use App\Models\Donation;
use App\Models\User_Project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  use ApiResponseTrait;

    public function show_donation()
    { 
      $usersWithProjects = Donation::join('users_projects', 'donations.user_project_id', '=', 'users_projects.id')
      // ->join('projects', 'users_projects.project_id', '=', 'projects.id')
      // ->select('donations.*', 'projects.name as project_name')
  
      ->join('users', 'users_projects.user_id', '=', 'users.id')
      ->where('users.role_id','=','1')
      ->select( 'users.*','donations.*')
      ->get();

      return $this->apiResponse($usersWithProjects, 'ok', 200);
    }


    // public function show_benifit()
    // { 
    //     $users=[];
    //     $projects=[];

    //      $users_id = User_Project::select('user_id')->get();
    //      foreach($users_id as $user_id)
    //     {
    //          $users[] = User::where('id',$user_id)->get();
    //     }

    //     $projects_id = User_Project::select('project_id')->get();
    //     foreach($projects_id as $project_id)
    //    {
    //         $projects[] = Project::where('id',$project_id)->get();
    //    }

    // }

    public function show_benifit()
  {
    $usersWithProjects = User_Project::join('users', 'users.id', '=', 'users_projects.user_id')
        ->join('projects', 'projects.id', '=', 'users_projects.project_id')
        ->where('users.role_id','=','1')
        ->select('users.*', 'projects.name as project_name')
        ->get();

        return $this->apiResponse($usersWithProjects, 'ok', 200);
  }

    public function store_benifit(Request $request)
    {
        $validator = validator([
            'name' => 'required',
            'email' => 'required',
           'password' => 'required',
          //   'role_id' => 'required',
          //   'center_id' => 'required',
        ]);
        
        if ($validator->fails()) {
          return $this->apiResponse(null, $validator->errors(), 400);
      }
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'center_id'=>$request->center_id,
            'role_id'=>'1',
        ]);

        if($user){
          return $this->apiResponse($user, 'user saved succesfully', 201);
      }
      return $this->apiResponse(null, 'user not save', 400);
    }

    public function store_donation(Request $request,$project_id)
    {
        $validator = validator([
            'name' => 'required',
            'email' => 'required',
            'amount' => 'required',
             'bank_id' => 'required',
        ]);
        
        if ($validator->fails()) {
          return $this->apiResponse(null, $validator->errors(), 400);
      }
        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'role_id'=>'2',
        ]);

        $user_proj = User_Project::create([
        'user_id'=>$user->id,
        'project_id'=>$project_id
        ]);

         $don= Donation::create([
            'amount'=>$request->amount,
            'note'=>$request->note,
            'bank_id'=>$request->bank_id,
            'user_project_id'=>$user_proj->id
           ]);

          $num=Bank::where('id',$don->bank_id)->get();
          $num->bill_num = $num->bill_num +1;
          $numb = $num->bill_num;
          $num->update();

         $bill=Bill::create([
            'number'=>$numb
         ]);

        if($user&$user_proj&$don&$numb&$bill){
          return $this->apiResponse($bill, 'user saved succesfully', 201);
      }
      return $this->apiResponse(null, 'user not save', 400);
    }

  
    public function update(Request $request, $id)
    {
        $validator = validator([
            'name' => 'required',
            'email' => 'required' ,
            'password' => 'required',
            'role_id' => 'required',
        ]);
        if ($validator->fails()) {
          return $this->apiResponse(null, $validator->errors(), 400);
      }
      $user = User::findOrFail($id);

      if(!$user){
        return $this->apiResponse(null, 'This user is not found', 404);
    }
  
      $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>$request->role_id,
      ]);

      if($user){
        return $this->apiResponse($user, 'user updated succesfully', 201);
    }
    }

    public function destroy( $id)
    {
      $user = User::find($id);

      if(!$user){
        return $this->apiResponse(null, 'This user not found', 404);
        }

        $projects = User_Project::where('user_id',$id)->get();

        if($projects)
        {
          foreach($projects as $project)
          {
          $donations = Donation::where('user_project_id',$project->id)->get();
          if($donations)
          {
            $donations->delete();
          }
            $project->delete();
          }
        }
      
        $user->delete();
    }
}
