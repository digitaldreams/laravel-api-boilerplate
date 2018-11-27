<?php

namespace App\Transformers;

use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;
use App\Models\Repayment;

class RepaymentTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    private $validParams = ['q', 'limit', 'page'];

    /**
     * @var array
     */
    protected $availableIncludes = ['loan'];

    /**
     * @var array
     */
    protected $defaultIncludes = [];


    public function transform(Repayment $repayment)
    {
        $data = [
            "id" => $repayment->id,
            "loan_id" => $repayment->loan_id,
            "amount" => $repayment->amount,
            "paid_at" => $repayment->paid_at,
            "created_at" => $repayment->created_at,
            "updated_at" => $repayment->updated_at,
        ];
        return $this->filterFields($data);
    }

    /**
     * @param Repayment $repayment
     * @param ParamBag|null $paramBag
     * @return \League\Fractal\Resource\Item
     */
    public function includeLoan(Repayment $repayment, ParamBag $paramBag = null)
    {
        return $this->item($repayment->loan, new LoanTransformer($paramBag->get('fields')));
    }
}