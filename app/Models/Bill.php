<?php

namespace App\Models;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $fillable = ['number', 'donation_id'];


    public function donation(): BelongsTo
    {
      return $this->belongsTo(Donation::class, 'donation_id');
    }

}
