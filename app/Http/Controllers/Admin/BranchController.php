<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch as ObjModel;
use App\Services\BranchService as ObjService;
use App\Http\Requests\BranchRequest as ObjRequest;

class BranchController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function destroy($id)
    {
        return $this->objService->delete($id);
    }

    public function create()
    {
        return $this->objService->create();
    }

    public function store(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $branch)
    {
        return $this->objService->edit($branch);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($id, $data);
    }


}
