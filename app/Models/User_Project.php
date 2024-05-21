<?php

namespace App\Models;

use App\Models\Donation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User_Project extends Model
{
    use HasFactory;
    protected $table = 'users_projects';
    protected $fillable = ['user_id','project_id'];

    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
      return $this->belongsTo(Project::class, 'project_id');
    }

    public function donation(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
