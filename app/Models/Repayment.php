<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $loan_id loan id
 * @property double $amount amount
 * @property datetime $paid_at paid at
 * @property timestamp $created_at created at
 * @property timestamp $updated_at updated at
 */
class Repayment extends Model
{

    /**
     * Database table name
     */
    protected $table = 'repayments';

    /**
     * Mass assignable columns
     */
    protected $fillable = [
        'loan_id',
        'amount',
        'paid_at'
    ];

    /**
     * Date time columns.
     */
    protected $dates = ['paid_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

}