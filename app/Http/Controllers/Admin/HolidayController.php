<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HolidayRequest;
use App\Services\HolidayService as ObjService;
use Illuminate\Http\Request;


class HolidayController extends Controller
{

    public function __construct(protected ObjService $objService)
    {

    }


    public function index(Request $request){

        return $this->objService->index($request);
    }


    public function showHoliday(Request $request)
    {
        return $this->objService->showHoliday($request);
    }

    public function create()
    {

        return $this->objService->create();

    }

    public function showHolidayDetails($id)
    {

        return $this->objService->showHolidayDetails($id);
    }


    public function store(HolidayRequest $request){
        return $this->objService->store($request->all());
    }

    public function edit($id)
    {
//        return response()->json($holidayType);
        return $this->objService->edit($id);
    }


    public function update(HolidayRequest $request, $id)
    {
        return $this->objService->update($request->all(), $id);
    }


    public function destroy($id)
    {
        return $this->objService->delete($id);
    }
}
