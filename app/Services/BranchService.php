<?php

namespace App\Services;

use App\Models\Branch as ObjModel;
use Yajra\DataTables\DataTables;

class BranchService extends BaseService
{
    protected string $folder = 'admin/branch';
    protected string $route = 'branches';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index($request)
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
                    $buttons .= '<button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                        </button>';


                    return $buttons;
                })->editColumn('address', function ($obj) {

                    return substr($obj->address, 0, 20) . '...';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index',[
                'route' => $this->route,
                'pageTitle' => trns('branches'),
            ]);
        }
    }


    public function create()
    {
        return view($this->folder . '/parts/create',[
            'route' => route($this->route . '.store'),
        ]);
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
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
        if ($this->updateData($id, $data)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }
}
