<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\LatestScope;
use Illuminate\Support\Facades\Cache;
use App\Traits\Taggable;
class Comment extends Model
{
  use SoftDeletes , Taggable ;
    use HasFactory;

    protected $fillable = ['user_id' , 'content'];
    protected $hidden = ['deleted_at' , 'commentable_type','commentable_id','user_id'];
  public function commentable() 
  {
      return $this->morphTo();
  }
    
    public function user() 
    
    {
      return $this->belongsTo('App\Models\User');
    }

    

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

   
}
