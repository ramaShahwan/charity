<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'banks';
    protected $fillable = ['name', 'bill_num'];

    
    public function donation(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
