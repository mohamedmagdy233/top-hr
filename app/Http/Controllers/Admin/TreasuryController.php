<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\TreasuryRequest;
use Illuminate\Http\Request;
use  App\Services\TreasuryService as ObjService;

class TreasuryController extends Controller
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

    public function store(TreasuryRequest $request)
    {
        return $this->objService->store($request->all());
    }


}
