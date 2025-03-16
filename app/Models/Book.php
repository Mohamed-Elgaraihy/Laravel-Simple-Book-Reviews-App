<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title','author'];
    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, $title):Builder{
        return $query->where('title','LIKE',"%$title%");
    }

    public function scopeMinReviews(Builder $query, $minRev):Builder{
        return $query->having('reviews_count','>=',$minRev);
    }

    public function scopeWithPopular(Builder $query,$from=null,$to=null):Builder{
        return $query->withCount(['reviews'=>fn($q)=>$this->dateRangeFilter($q,$from,$to)])->orderBy('reviews_count','desc');
    }
    public function scopeWithHighRated(Builder $query,$from=null,$to=null):Builder{
        return $query->withAvg(['reviews'=>fn($q)=>$this->dateRangeFilter($q,$from,$to)],'rating')->orderBy('reviews_avg_rating','desc');
    }

    public function scopePopular(Builder $query, $from=null,$to=null):Builder{
      return $query->withPopular($from,$to);
    }

    public function scopePopularLastMonth(Builder $query):Builder{
        return $query
        ->popular(now()->subMonth(),now())
        ->highRated(now()->subMonth(),now())
        ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query):Builder{
        return $query
        ->popular(now()->subMonths(6),now())
        ->highRated(now()->subMonths(6),now())
        ->minReviews(5);
    }

    public function scopeHighRated(Builder $query,$from=null,$to=null):Builder{
        return $query->withHighRated($from,$to);
    }

    public function scopeHighRatedLastMonth(Builder $query):Builder{
        return $query
        ->highRated(now()->subMonth(),now())
        ->popular(now()->subMonth(),now())
        ->minReviews(2);
    }

    public function scopeHighRatedLast6Months(Builder $query):Builder{
        return $query
        ->highRated(now()->subMonths(6),now())
        ->popular(now()->subMonths(6),now())
        ->minReviews(5);
    }

    private function dateRangeFilter(Builder $q, $from=null,$to = null){
        if($from && !$to){
            $q->where('created_at','>=',$from);
        }elseif(!$from && $to){
            $q->where('created_at','<=',$to);
        }elseif($from && $to){
            $q->whereBetween('created_at',[$from,$to]);
        }
    }

    protected static function booted(){
        static::updated(fn(Book $book)=>cache()->forget('book:'.$book->id));
       static::deleted(fn(Book $book)=>cache()->forget('book:'.$book->id));
       static::created(fn(Book $book)=>cache()->forget('book:'.$book->id));
    }
}
