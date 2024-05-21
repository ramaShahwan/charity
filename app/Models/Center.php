<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Center extends Model
{
    use HasFactory;
    protected $table = 'centers';
    protected $fillable = ['name', 'address','phone','image'];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
