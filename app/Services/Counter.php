<?php 

namespace App\Services;


use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;
use App\Contracts\CounterContract;
class Counter implements CounterContract
   {
    private $timeout;
    private $cache;
    private $session;
    private $supportsTags;

    public function __construct(Cache $cache, Session $session,  int $timeout) 
    {
        $this->cache = $cache;
       $this->timeout = $timeout ;
       $this->session = $session;
       $this->supportsTags = method_exists($cache, 'tags');
    }
   public function increment(string $key, array $tags = null): int

   {
     $sessionId = $this->session->getId();
     $counterkey = "{$key}-counter";
     $userskey="{$key}-users" ;

    $cache = $this->supportsTags && null !==  $tags ? $this->cache->tags($tags) : $this->cache ;

     $users = $cache->get( $userskey, []);
     $usersUpdate = [];
     $diffrence=0;
     $now = now();
    foreach($users as $session => $lastvisit) {
        if ($now->diffInMinutes($lastvisit) >= $this->timeout) {
            $diffrence--;
        } else {
            $usersUpdate[$session] = $lastvisit;
        }
    }
    
    if(!array_key_exists($sessionId , $users) || $now->diffInMinutes($users[$sessionId])>= $this->timeout) {
        $diffrence++;
    }
    $usersUpdate[$sessionId] = $now;
    $cache->forever($userskey , $usersUpdate);

    if(!$cache->has($counterkey)) {
        $cache->forever($counterkey , 1);
    } else {
        $cache->increment($counterkey, $diffrence);
    }
    
     $counter = $cache->get($counterkey);
     return $counter;
   }
}