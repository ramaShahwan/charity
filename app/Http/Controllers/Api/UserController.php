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

    // يمكنك استخدام $usersWithProjects هنا حسب احتياجاتك الأخرى
  }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
             'role_id' => 'required',
          //   'center_id' => 'required',

        ]);

        $user= User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'center_id'=>$request->center_id,
            'role_id'=>$request->role_id,

        ]);
    }

  
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required' ,
            'password' => 'required',
            'role_id' => 'required',
        ]);
      $user = User::findOrFail($id);
  
      $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>$request->role_id,
      ]);
    
    }

   
    public function destroy( $id)
    {
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
      User::findOrFail($id)->delete();
   
    }
}
