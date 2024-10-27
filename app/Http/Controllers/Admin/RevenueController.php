<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevenueRequest;
use App\Models\Revenue;
use App\Models\Treasury;
use App\Services\RevenueService as ObjService;
use Illuminate\Http\Request;

class RevenueController extends Controller
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

    public function store(RevenueRequest $request)
    {
        return $this->objService->store($request->all());
    }

    public function edit($id)
    {
        return $this->objService->edit($id);
    }

    public function update(RevenueRequest $request, $id)
    {
        return $this->objService->update($request->all(), $id);
    }

    public function destroy($id)
    {
        $total=Treasury::first();
        $Revenue =Revenue::find($id);
        $total->value = $total->value - $Revenue->value;
        $total->save();
        return $this->objService->delete($id);

    }
}
