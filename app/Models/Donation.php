<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\Bill;

use App\Models\User_Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;
    protected $table = 'donations';
    protected $fillable = ['amount','note','bank_id','user_project_id'];


    public function bank(): BelongsTo
    {
      return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function bill(): BelongsTo
    {
      return $this->belongsTo(Bill::class);
    }

    public function user_project(): BelongsTo
    {
      return $this->belongsTo(User_Project::class, 'user_project_id');
    }

}
