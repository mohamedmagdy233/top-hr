<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\Treasury;
use App\Services\ExpenseService as ObjService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(protected ObjService $objService)
    {

    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(Request $request)
    {
        return $this->objService->store($request);
    }

    public function edit($id)
    {
        return $this->objService->edit($id);
    }

    public function update(ExpenseRequest $request, $id)
    {
        return $this->objService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        $total=Treasury::first();
        $esxpense =Expense::find($id);
        $total->value = $total->value + $esxpense->value;
        $total->save();
        return $this->objService->delete($id);

    }
}
