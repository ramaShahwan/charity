<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Class_;
use App\Models\Project;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $classes = Class_::orderBy('created_at','Desc')->get();
        return $this->apiResponse($classes, 'ok', 200);
    }


    public function get_class_with_project(){

        $classes = Class_::all();
        $projects = Project::all();
        return $this->apiResponse([$classes, $projects], 'ok', 200);
    }


    public function store(Request $request)
    {
        $validator = Validator([
            'name' => 'required|max:255',
            // 'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $class = new Class_(); 
        $class->name = $request->name;
        $class->save();

       if($request->hasFile('image')){
        $newImage = $request->file('image');
        $newImageName = 'image_' . $class->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move(public_path('img/class/'), $newImageName);

       $class->image = $newImageName;
       $class->update();
       }

        if($class){
            return $this->apiResponse($class, 'The class save', 201);
        }
        return $this->apiResponse(null, 'This class not save', 400);
    }


    public function update(Request $request, $id){

        $validator = Validator( [
            'name' => 'required|max:255',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $class = Class_::find($id);
         $oldImageName=$class->image;

        if(!$class){
            return $this->apiResponse(null, 'This class not found', 404);
        }

        $class->name = $request->name;

        if ($request->hasFile('image')) {
        if ($oldImageName) {
            File::delete(public_path('img/class/') . $oldImageName);
        }

        // Upload new image
        $newImage = $request->file('image');
        $newImageName = 'image_' . $class->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move('img/class/', $newImageName);
  
        // Update the image record with the new image name
            $class->image = $newImageName;
        }
      
        $class->update();
  
        if($class){
            return $this->apiResponse($class, 'The class update', 201);
        }
    }


    public function destroy($id){

        $class = Class_::find($id);

        if(!$class){
            return $this->apiResponse(null, 'This class not found', 404);
        }

        $projects = Project::where('class_id', $id)->get();

        foreach ($projects as $project) {
            $project->delete();
        }

        $class->delete($id);

        if($class){
            return $this->apiResponse(null, 'This class deleted', 200);
        }
    }
    

}