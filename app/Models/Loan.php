<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id user id
 * @property double $amount amount
 * @property varchar $duration duration
 * @property double $interest_rate interest rate
 * @property double $arrangement_fee arrangement fee
 * @property varchar $status status
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Loan extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_SETTLED = 'settled';
    const STATUS_PAID = 'paid';
    /**
     * Database table name
     */
    protected $table = 'loans';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'user_id',
        'amount',
        'duration',
        'interest_rate',
        'arrangement_fee',
        'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}