<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdvanceDiscountService as ObjService;
use Illuminate\Http\Request;


class AdvanceDiscountController extends Controller
{
    public function __construct(protected ObjService $objService)
    {

    }

    public function index(Request $request){

        return $this->objService->index($request);
    }


    public function create(){
        return $this->objService->create();
    }

     public function store(Request $request)
     {
         return $this->objService->store($request->all());

     }

     public function edit($id)
     {
         return $this->objService->edit($id);

     }

     public function update(Request $request ,$id)
     {
         return $this->objService->update($request->all(),$id);

     }

     public function destroy($id)
     {
         return $this->objService->delete($id);
     }
}
