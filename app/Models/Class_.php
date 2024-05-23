<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Class_ extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $fillable = ['name','image'];

    
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
