<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 


class CenterController extends Controller
{
    use ApiResponseTrait;

    public function show()
    { 
      $centers = Center::orderBy('created_at','Desc')->get();
      return $this->apiResponse($centers, 'ok', 200);
    }

    public function store(Request $request)
    {
        $validator = validator([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            //  'image' => 'required',
        ]);
        
        if ($validator->fails()) {
          return $this->apiResponse(null, $validator->errors(), 400);
      }
        $center=new Center;
        $center->name = $request->name;
        $center->address = $request->address;
        $center->phone = $request->phone;
        $center->save();

       if($request->hasFile('image')){
        $newImage = $request->file('image');
        $newImageName = 'image_' . $center->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move(public_path('img/center/'), $newImageName);

       $center->image = $newImageName;
       $center->update();
       }

        if($center){
          return $this->apiResponse($center, 'center saved succesfully', 201);
      }
      return $this->apiResponse(null, 'center not save', 400);
    }


    public function update(Request $request, $id){

        $validator = Validator( [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }


        $center = Center::find($id);
         $oldImageName=$center->image;

        if(!$center){
            return $this->apiResponse(null, 'This center not found', 404);
        }

        $center->name = $request->name;
        $center->address = $request->address;
        $center->phone = $request->phone;

        if ($request->hasFile('image')) {
        if ($oldImageName) {
            File::delete(public_path('img/class/') . $oldImageName);
        }

        // Upload new image
        $newImage = $request->file('image');
        $newImageName = 'image_' . $center->id . '.' . $newImage->getClientOriginalExtension();
        $newImage->move('img/class/', $newImageName);
  
        // Update the image record with the new image name
            $center->image = $newImageName;
        }
      
        $center->update();
  
        if($center){
            return $this->apiResponse($center, 'center updated succesfully', 201);
        }
    }


    public function destroy($id){

        $center = Center::find($id);

        if(!$center){
            return $this->apiResponse(null, 'center not found', 404);
        }

        $users = User::where('center_id',$id)->get();

        if($users)
        {
            foreach($users as $user)
            {
             $user->delete();
            }
        }
      
        $center->delete($id);

        if($center){
            return $this->apiResponse(null, 'center deleted succesfully', 200);
        }
    }
    
    

}
