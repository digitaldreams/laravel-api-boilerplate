<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Loans\Destroy;
use App\Http\Requests\Api\Loans\Index;
use App\Http\Requests\Api\Loans\Show;
use App\Http\Requests\Api\Loans\Store;
use App\Http\Requests\Api\Loans\Update;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Models\Loan;
use App\Transformers\LoanTransformer;


/**
 * Loan
 *
 * @Resource("Loan", uri="/loans")
 */
class LoanController extends ApiController
{

    public function index(Index $request)
    {
        return $this->response->paginator(Loan::paginate(10), new LoanTransformer());
    }

    public function show(Show $request, Loan $loan)
    {
        return $this->response->item($loan, new LoanTransformer());
    }

    public function store(Store $request)
    {
        $model = new Loan;
        $model->fill($request->all());

        if ($model->save()) {
            return $this->response->item($model, new LoanTransformer());
        } else {
            return $this->response->errorInternal('Error occurred while saving Loan');
        }
    }

    public function update(Update $request, Loan $loan)
    {
        $loan->fill($request->all());

        if ($loan->save()) {
            return $this->response->item($loan, new LoanTransformer());
        } else {
            return $this->response->errorInternal('Error occurred while saving Loan');
        }
    }

    public function destroy(Destroy $request, Loan $loan)
    {
        if ($loan->delete()) {
            return $this->response->array(['status' => 200, 'message' => 'Loan successfully deleted']);
        } else {
            return $this->response->errorInternal('Error occurred while deleting Loan');
        }
    }
}
