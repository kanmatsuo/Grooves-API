<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ActionDescription;

class TransactionHistory extends Model
{
    use HasFactory;
    protected $table = 'transaction_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id',
        'from',
        'action',
        'tx_link',
        'price',
        'paytype'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function action_description(): HasOne
    {
        return $this->hasOne(ActionDescription::class, 'id', 'action');
    }
}