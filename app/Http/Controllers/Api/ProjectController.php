<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User_Project;
use App\Models\Donation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 

class ProjectController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $projects = Project::orderBy('created_at','Desc')->get();
        return $this->apiResponse($projects, 'ok', 200);
    }

    
    public function get_project_for_class($class_id){
        
        $projects = Project::where('class_id',$class_id)->get();
        return $this->apiResponse($projects, 'ok', 200);
    }

    
    public function statistic($project_id){

        if($project_id){

        $target = Project::where('id', $project_id)->value('target');

        if(!$target){
            return $this->apiResponse(null, 'This target is invalid', 404);
        }

        $benefits_count = Project::where('id', $project_id)->value('benefits_count');

        if(!$benefits_count){
            $benefits_count = 0;
        }

        $projects = User_Project::where('project_id', $project_id)->get();

        if(!$projects){
            return $this->apiResponse(null, 'This projects is invalid', 404);
        }
 
        $last_donation = User_Project::join('projects', 'users_projects.project_id', '=', 'projects.id')
        ->join('users', 'users_projects.user_id', '=', 'users.id')
        ->where('users.role_id', '=', '2')
        ->latest()
        ->select('users_projects.*')
        ->first();
     

        if(!$last_donation){
  
            $last_donation_date = null;
        }
        else{
            $last_donation = Donation::where('user_project_id', $last_donation->id)
            ->latest()->first();
    
            $last_donation_date = $last_donation->created_at->format('Y-m-d H:i:s');
        }

        $donations = Donation::all();
        $donation_count = 0;

        foreach($projects as $project){
            foreach($donations as $donation){
                if($donation->user_project_id == $project->id){
                    $donation_count = $donation_count + 1;
                }
            }
        }

      return $this->apiResponse([$target, $benefits_count, $last_donation_date, $donation_count], 'This is all data', 200);
     }

      else{
      return $this->apiResponse(null, 'This project_id is invalid', 404);

      }

    }


    public function store(Request $request)
    {
        $validator = Validator([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'required',
            'target' => 'required',
            'total_budget' => 'required',
            'total_benifit' => 'required',
            'class_id' => 'required', 
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $project = new Project(); 
        $project->name = $request->name;
        $project->description = $request->description;

        $project->target = $request->target;
        $project->total_budget = $request->total_budget;
        $project->total_benifit = $request->total_benifit;

        $project->class_id = $request->class_id;
        $project->save();


        // store image
       if($request->hasFile('image')){
            $newImage = $request->file('image');
            $newImageName = 'image_' . $project->id . '.' . $newImage->getClientOriginalExtension();
            $newImage->move(public_path('img/project/'), $newImageName);

            $project->image = $newImageName;
            $project->update();
       }


        // store tag
        if($request->hasFile('tag')){
            $newTag = $request->file('tag');
            $newTagName = 'tag_' . $project->id . '.' . $newTag->getClientOriginalExtension();
            $newTag->move(public_path('img/project/'), $newTagName);
        
           $project->tag = $newTagName;
           $project->update();
       }

        if($project){
            return $this->apiResponse($project, 'The project save', 201);
        }
        return $this->apiResponse(null, 'This project not save', 400);
    }


    public function update(Request $request, $id){

        $validator = Validator( [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'required',
            'target' => 'required',
            'total_budget' => 'required',   
            'total_benifit' => 'required',   
           
            'class_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $project = Project::findOrFail($id);
        $oldImageName = $project->image;
        $oldTagName = $project->tag;
         
        if(!$project){
            return $this->apiResponse(null, 'This project not found', 404);
        }

        $project->name = $request->name;
        $project->description = $request->description;
        $project->target = $request->target;
        $project->total_budget = $request->total_budget;
        $project->total_benifit = $request->total_benifit;

        $project->class_id = $request->class_id;
        
        // update newImage
        if ($request->hasFile('image')) {
        if ($oldImageName) {
            File::delete(public_path('img/project/') . $oldImageName);
        }
        // Upload new image
        $newImage = $request->file('image');
        $newImageName = 'image_' . $project->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move('img/project/', $newImageName);

         $project->image = $newImageName;
        }
      

        // update newTag
        if ($request->hasFile('tag')) {
            // Delete the old tag from the server
            if ($oldTagName) {
                File::delete(public_path('img/project/') . $oldTagName);
            }
            // Upload new tag
            $newTag = $request->file('tag');
            $newTagName = 'tag_' . $project->id . '.' . $newTag->getClientOriginalExtension();
            $newTag->move('img/project/', $newTagName);
    
              $project->tag = $newTagName;
            }

        $project->update();
  
        if($project){
            return $this->apiResponse($project, 'The project update', 201);
        }

    }


    public function destroy($id){

        $project = Project::find($id); 
        if(!$project){
            return $this->apiResponse(null, 'This project not found', 404);
        }

        $project->delete($id);
        if($project){
            return $this->apiResponse(null, 'This project deleted', 200);
        }
    }

}
