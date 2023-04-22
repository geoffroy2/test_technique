<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCredits extends Model
{
    use HasFactory;

    protected $fillable = ['creationDate', 'amount_requested','product','status','dueDate','phoneNumber','amount_to_repay'];
}
