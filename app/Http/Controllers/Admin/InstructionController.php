<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\InstructionRequest;
use App\Models\Instruction;
use  App\Services\instructionService as ObjService;

use Illuminate\Http\Request;

class InstructionController extends Controller
{

    public function __construct( protected ObjService $service)
    {

    }

    public function index(Request $request)
    {
        return $this->service->index($request);

    }

    public function create()
    {
        return $this->service->create();

    }

    public function store( InstructionRequest $request)
    {
        return $this->service->store($request->all());

    }

    public function edit(Instruction $instruction)
    {
        return $this->service->edit($instruction);

    }

    public function update(InstructionRequest $request, $id)
    {
        return $this->service->update($id, $request->all());

    }

    public function destroy($id)
    {
        return $this->service->delete($id);

    }
}
