<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';
    protected $fillable = ['name','description','image','tag','visits_count','benefits_count','target',
                          'total_budget','total_donate','finish','class_id'];
}
