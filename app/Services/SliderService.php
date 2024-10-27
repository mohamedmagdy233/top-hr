<?php

namespace App\Services;

use App\Models\Slider as ObjModel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SliderService extends BaseService
{
    protected string $folder = 'admin/sliders';
    protected string $route = 'sliders';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index( $request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    $buttons .= '
                    <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                        <i class="fa fa-edit"></i>
                    </button>
                ';
                    $buttons .= '
                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
                    return $buttons;
                })
                ->editColumn('image', function ($obj) {
                    if ($obj->image != null) {
                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('storage/' . $obj->image) . '">
                    ';
                    } else {
                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('assets/uploads/avatar.png') . '">
                    ';
                    }
                }) ->editColumn('active', function ($obj) {
                    return '
                    <div class="form-check form-switch">
                       <input style="margin-left: 0px;" class="tgl tgl-ios statusBtn form-check-input" data-id="' . $obj->id . '" name="statusBtn" id="statusUser-' . $obj->id . '" type="checkbox" ' . ($obj->active == 1 ? 'checked' : '') . '/>
                       <label class="tgl-btn" dir="ltr" for="statusUser-' . $obj->id . '"></label>
                    </div>';


                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('sliders'),
            ]);
        }
    }


    public function create()
    {
        return view($this->folder . '/parts/create', [
            'route' => route($this->route . '.store'),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        $data['image'] = $data['image']->store('sliders', 'public');
        $model = $this->createData($data);
        if ($model) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit($obj)
    {
        return view($this->folder . '/parts/edit',[
            'route' => route($this->route . '.update', $obj->id),
            'obj' => $obj
        ]);
    }

    public function update($id, $data)
    {
        $slider = $this->getById($id);
        Storage::disk('public')->delete($slider->image);


        $data['image'] = $data['image']->store('sliders', 'public');

        if ($this->updateData($id, $data)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

}
