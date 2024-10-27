<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest as ObjRequest;
use App\Models\User as ObjModel;
use App\Services\UserService as ObjService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected ObjService $objService) {}

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

    public function show(ObjModel $user,Request $request)
    {
        return $this->objService->show($user,$request);
    }

    public function store(ObjRequest $request)
    {
        $data = $request->validated();
        return $this->objService->store($data);
    }

    public function edit(ObjModel $user)
    {
        return $this->objService->edit($user);
    }
    public function incentives(ObjModel $user)
    {
        return $this->objService->incentives($user);
    }
    public function getIncentives(ObjModel $user, $date)
    {
        try {
            $monthDate = Carbon::parse($date);

            return $this->objService->getIncentives($user, $monthDate);
        } catch (\Exception $e) {
            return back()->withErrors(['Invalid date format.']);
        }
    }



    public function addIncentives(Request $request)
    {
        return $this->objService->addIncentives($request);
    }

    public function update(ObjRequest $request, $id)
    {
        $data = $request->validated();
        return $this->objService->update($id, $data);
    }

    public function deleteIncentive( $id)
    {
        return $this->objService->deleteIncentive($id);
    }
}
