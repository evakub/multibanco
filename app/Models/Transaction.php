<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $table = 'transactions';

    protected $fillable = [
        'request_payload',
        'type',
        'payload',
    ];

    public function setPayload($payload){
        $this->payload = json_encode($payload);
    }

    public function getArrayPayload(){
        return json_decode($this->payload);
    }
}
