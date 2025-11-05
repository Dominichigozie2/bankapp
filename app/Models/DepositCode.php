<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositCode extends Model
{
    use HasFactory;

    protected $fillable = ['generated_by','user_id','code','status','expires_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function isValidForUser($userId)
    {
        if ($this->status !== 'active') return false;
        if ($this->user_id && $this->user_id != $userId) return false;
        if ($this->expires_at && now()->greaterThan($this->expires_at)) return false;
        return true;
    }
}
