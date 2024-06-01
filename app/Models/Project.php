<?php

namespace App\Models;

use App\Models\Class_;
use App\Models\User_Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = ['name','description','image','tag','total_benifit','benefits_count','target',
                          'total_budget','total_donate','finish','class_id'];

    public function class_(): BelongsTo
    {
      return $this->belongsTo(Class_::class, 'class_id');
    }

    public function user_project(): HasMany
    {
        return $this->hasMany(User_Project::class);
    }

}
