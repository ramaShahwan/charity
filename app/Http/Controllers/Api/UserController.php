<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User_Project;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;


class UserController extends Controller
{
  use ApiResponseTrait;

    public function show_donation()
    { 
         $users = Donation::whereNull('password')->orderBy('created_at','DESC');
        
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
    $usersWithProjects = User_Project::join('users', 'users.id', '=', 'user_projects.user_id')
        ->join('projects', 'projects.id', '=', 'user_projects.project_id')
        ->select('users.*', 'projects.name as project_name')
        ->get();

        return $this->apiResponse($usersWithProjects, 'ok', 200);
  }

    public function store(Request $request)
    {
        $validator = $request->validater([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
             'role_id' => 'required',
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
            'role_id'=>$request->role_id,

        ]);

        if($user){
          return $this->apiResponse($user, 'user saved succesfully', 201);
      }
      return $this->apiResponse(null, 'user not save', 400);
    }

  
    public function update(Request $request, $id)
    {
        $validator = $request->validater([
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
      $user = User::findOrFail($id);

      if(!$user){
        return $this->apiResponse(null, 'This class not found', 404);
    }
        $projects = User_Project::where('user_id',$id)->get();
        foreach($projects as $project)
        {
        $donations = Donation::where('user_project_id',$project->id)->get();
        if($donations)
        {
          $donations->delete();
        }
          $project->delete();
        }
        $user->delete();
    }
}
