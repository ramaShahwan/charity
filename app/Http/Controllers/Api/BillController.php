<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Donation;
use Illuminate\Http\Request;

class BillController extends Controller
{
    use ApiResponseTrait;

    public function get($bank_id)
    {
            // $bill = Donation::where('bank_id',$bank_id)->latest()->first();
            $bill_num = Bank::where('id',$bank_id)->value('bill_num');
            return $this->apiResponse($bill_num, 'ok', 200);
    }
}
