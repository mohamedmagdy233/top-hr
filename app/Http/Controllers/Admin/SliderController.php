<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use Google\Service\AdMob\App;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Services\SliderService as ObjService;

class SliderController extends Controller
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

    public function store( SliderRequest $request)
    {
        return $this->service->store($request->all());

    }

    public function edit(Slider $slider)
    {
        return $this->service->edit($slider);

    }

    public function update(SliderRequest $request, $id)
    {
        return $this->service->update($id, $request->all());

    }

    public function destroy($id)
    {
        return $this->service->delete($id);

    }

    public function changeStatus(Request $request)
    {
        return $this->service->changeStatus($request);

    }
}
