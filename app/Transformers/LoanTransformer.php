<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;
use App\Models\Loan;

class LoanTransformer extends BaseTransformer
{

    /**
     * @var array
     */
    private $validParams = ['q', 'limit', 'page'];

    /**
     * @var array
     */
    protected $availableIncludes = ['user', 'repayments'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];


    public function transform(Loan $loan)
    {
        $data = [
            "id" => $loan->id,
            "user_id" => $loan->user_id,
            "amount" => $loan->amount,
            "duration" => $loan->duration,
            "interest_rate" => $loan->interest_rate,
            "arrangement_fee" => $loan->arrangement_fee,
            "status" => $loan->status,
            "created_at" => $loan->created_at,
            "updated_at" => $loan->updated_at,
        ];
        return $this->filterFields($data);
    }

    /**
     * @param Loan $loan
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRepayments(Loan $loan, ParamBag $paramBag = null)
    {
        return $this->collection($loan->repayments, new RepaymentTransformer($paramBag->get('fields')));
    }

    /**
     * @param Loan $loan
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Loan $loan, ParamBag $paramBag = null)
    {
        return $this->item($loan->user, new UserTransformer($paramBag->get('fields')));
    }
}