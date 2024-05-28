<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $banks = Bank::orderBy('created_at','Desc')->get();
        return $this->apiResponse($banks, 'ok', 200);
    }


    public function store(Request $request)
    {
        $validator = Validator([
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $bank = new Bank(); 
        $bank->name = $request->name;
        $bank->save();

        if($bank){
            return $this->apiResponse($bank, 'The bank save', 201);
        }
        return $this->apiResponse(null, 'This bank not save', 400);
    }


    public function update(Request $request, $id){

        $validator = Validator([
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $bank = Bank::findOrFail($id); 
        if(!$bank){
            return $this->apiResponse(null, 'This bank not found', 404);
        }

        $bank->name = $request->name;
        $bank->update();

        if($bank){
            return $this->apiResponse($bank, 'The bank update', 201);
        }

    }


    public function destroy($id){

        $bank = Bank::find($id); 
        if(!$bank){
            return $this->apiResponse(null, 'This bank not found', 404);
        }

        $bank->delete($id);
        if($bank){
            return $this->apiResponse(null, 'This bank deleted', 200);
        }
    }

}
