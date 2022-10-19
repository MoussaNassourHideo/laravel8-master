<?php

namespace App\Models;

use App\Scopes\LatestScope;

use Illuminate\Database\Eloquent\Builder ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\scopes\DeletedAdminScope;
use App\Traits\Taggable;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes ,Taggable;
    protected $fillable = ['title','content','user_id'] ;
    use HasFactory;

    public function comments() 
    {
        return $this->morphMany('App\Models\Comment' , 'commentable')->latest();
    }

    public function user() 
    {
        return $this->belongsTo('App\Models\User');
    }
     
    public function scopeLatest(Builder $query) 
    {

        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query) 
    {
          
        return $query->withCount('comments')->orderBy('comments_count' , 'desc') ;
        
    }

    
    
    public function image() 
    {
        return $this->morphOne('App\Models\Image' , 'imageable');
    }

    public function scopeLatestwithRelations(Builder $query)
    {
        return $query->latest()
        ->withCount('comments')
        ->with('user')
        ->with('tags');
    }

    public static function boot() 
    {
        static::addGlobalScope(new LatestScope);
        static::addGlobalScope(new DeletedAdminScope);
        parent::boot(); 
        
        
        
    }
}
