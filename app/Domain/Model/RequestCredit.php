<?php

namespace App\Domain\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCredit extends Model
{
    use HasFactory;

    protected $fillable = ['creationDate', 'amount_requested','product','status','dueDate','phoneNumber','amount_to_repay'];
}
