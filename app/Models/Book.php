<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
    ];

    public function categories() {
        return $this->belongsToMany(Category::class, 'book_category');
    }

    public function readers() {
        return $this->belongsToMany(User::class, 'reading_list')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
