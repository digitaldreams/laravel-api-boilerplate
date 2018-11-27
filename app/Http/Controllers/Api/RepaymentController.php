<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\Repayment;
use App\Models\Loan;
use App\Transformers\RepaymentTransformer;
use App\Http\Requests\Api\Repayments\Index;
use App\Http\Requests\Api\Repayments\Show;
use App\Http\Requests\Api\Repayments\Store;
use App\Http\Requests\Api\Repayments\Update;
use App\Http\Requests\Api\Repayments\Destroy;


/**
 * Repayment
 *
 * @Resource("Repayment", uri="/repayments/{loan}")
 */
class RepaymentController extends ApiController
{

    public function index(Index $request, Loan $loan)
    {
        return $this->response->paginator(Repayment::paginate(10), new RepaymentTransformer());
    }

    public function show(Show $request, Loan $loan, Repayment $repayment)
    {
        return $this->response->item($repayment, new RepaymentTransformer());
    }

    public function store(Store $request, Loan $loan)
    {
        $model = new Repayment;
        $model->loan_id = $loan->id;
        $model->fill($request->all());
        if ($model->save()) {
            session()->flash('app_message', 'Repayment saved successfully');
            return $this->response->item($model, new RepaymentTransformer());
        } else {
            return $this->response->errorInternal('Error occurred while saving Repayment');
        }
    }

    public function update(Update $request, Loan $loan, Repayment $repayment)
    {
        $repayment->fill($request->all());
        if ($repayment->save()) {
            return $this->response->item($repayment, new RepaymentTransformer());
        } else {
            return $this->response->errorInternal('Error occurred while saving Repayment');
        }
    }

    public function destroy(Destroy $request, Loan $loan, Repayment $repayment)
    {
        if ($repayment->delete()) {
            return $this->response->array(['status' => 200, 'message' => 'Repayment successfully deleted']);
        } else {
            return $this->response->errorInternal('Error occurred while deleting Repayment');
        }
    }

}
