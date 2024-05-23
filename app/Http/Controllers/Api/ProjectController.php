<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 

class ProjectController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $projects = Project::orderBy('created_at','Asc')->get();
        return $this->apiResponse($projects, 'ok', 200);
    }


    public function store(Request $request)
    {
        $validator = Validator([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'image' => 'required',
            // 'tag' => 'required',

            'target' => 'required',
            'total_budget' => 'required',
            // 'total_donate' => 'required',
            // 'finish' => 'required',            
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $project = new Project(); 
        $project->name = $request->name;
        $project->description = $request->description;

        $project->target = $request->target;
        $project->total_budget = $request->total_budget;

        $project->save();


        // store image
       if($request->hasFile('image')){
            $newImage = $request->file('image');
            //for change image name
            $newImageName = 'image_' . $project->id . '.' . $newImage->getClientOriginalExtension();
            $newImage->move(public_path('img/project/'), $newImageName);

            $project->image = $newImageName;
            $project->update();
       }


        // store tag
        if($request->hasFile('tag')){
            $newTag = $request->file('tag');
            //for change tag name
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


        // $project->update([
        //     'name' => $request->name,
        //     'description' => $request->description,
        //     'target' => $request->target,
        //     'total_budget' => $request->total_budget,
        // ]);



        // update newImage
        if ($request->hasFile('image')) {
        // Delete the old image from the server
        if ($oldImageName) {
            File::delete(public_path('img/project/') . $oldImageName);
        }
        // Upload new image
        $newImage = $request->file('image');
        $newImageName = 'image_' . $project->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move('img/project/', $newImageName);

        // Update the image record with the new image name
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
    
            // Update the image record with the new image name
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
