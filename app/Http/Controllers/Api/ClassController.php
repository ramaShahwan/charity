<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Class_;


class ClassController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $classes = Class_::orderBy('created_at','Asc')->get();
        return $this->apiResponse($classes, 'ok', 200);
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
        // $class->image  = $request->image;
        $class->save();

        // store image
       if($request->hasFile('image')){
        $newImage = $request->file('image');
        //for change image name
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $class = Class_::find($id);

        if(!$class){
            return $this->apiResponse(null, 'This post not found', 404);
        }

        $class->name = $request->name;
        $class->image  = $request->image;

        // update newImage
        if ($request->hasFile('image')) {
        // Delete the old image from the server
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

        $class->delete($id);

        if($class){
            return $this->apiResponse(null, 'This class deleted', 200);
        }
    }
    
    
}