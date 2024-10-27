<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionRequest;
use App\Services\AdditionService;
use Illuminate\Http\Request;

class AdditionController extends Controller
{
    public function __construct(protected  AdditionService $objService)
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

    public function store(AdditionRequest $request)
    {
        return $this->objService->store($request->all());

    }

    public function edit($id)
    {
        return $this->objService->edit($id);

    }

    public function update(AdditionRequest $request, $id)
    {

        return $this->objService->update($request->all(), $id);
    }

    public function destroy($id)
    {

        return $this->objService->delete($id);

    }

}
