<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Api\UserService as ObjService;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct(protected ObjService $objService) {}

    public function home(Request $request)
    {
        return $this->objService->home($request);
    }

    public function checkin(Request $request)
    {
        return $this->objService->checkin($request);
    }

    public function checkout(Request $request)
    {
        return $this->objService->checkout($request);
    }
    public function userMonths()
    {
        return $this->objService->userMonths();
    }
    public function orderAnAdvance(Request $request)
    {

        return $this->objService->orderAnAdvance($request);
    }

    public function AttendanceAndDeparture(Request $request)
    {
        return $this->objService->AttendanceAndDeparture($request);

    }

    public function getMyAnAdvance()
    {
        return $this->objService->getMyAnAdvance();
    }

    public function editEmployeeProfile(Request $request)
    {
        return $this->objService->editEmployeeProfile($request);
    }

    public function contact_us(Request $request)
    {
        return $this->objService->contact_us($request);
    }

    public function myEmployeeProfile()
    {
        return $this->objService->myEmployeeProfile();
    }
    public function getNotification()
    {
        return $this->objService->getNotification();
    }
    public function GetNotes()
    {
        return $this->objService->GetNotes();
    }

    public function myHolidays()
    {
        return $this->objService->myHolidays();

    }

    public function holidaysType()
    {

        return $this->objService->holidaysType();
    }

    public function orderHoliday(Request $request)
    {
        return $this->objService->orderHoliday($request);

    }


    // for hr

    public function homeHR(Request $request)
    {
        return $this->objService->homeHR($request);
    }

    public function getHolidaysForEmployee(Request $request)
    {
        return $this->objService->getHolidaysForEmployee($request);
    }

    public function AcceptOrRejectTheHoliday(Request $request)
    {
        return $this->objService->AcceptOrRejectTheHoliday($request);
    }

    public function getDeduction(Request $request)
    {
        return $this->objService->getDeduction($request);
    }


    //for test
    public function testFcm(Request $request)
    {
        return $this->objService->testFcm($request);
    }
    public function storeFcm(Request $request)
    {
        return $this->objService->storeFcm($request);
    }
}//end class
