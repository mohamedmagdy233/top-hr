<?php

namespace App\Services;

use App\Models\Treasury as ObjModel;
use Yajra\DataTables\DataTables;

class TreasuryService extends BaseService
{
    protected string $folder = 'admin/treasury';
    protected string $route = 'treasures';

    public function __construct(ObjModel $model,protected UserService $userService)
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
                })->editColumn('updated_at', function ($obj) {

                    return $obj->updated_at->diffForHumans();
                })->editColumn('value', function ($obj) {

                    return number_format($obj->value) . ' EGP';
                })->editColumn('reason', function ($obj) {

                    return $obj->reason ==null ? 'لم يتم ذكر السبب' : $obj->reason;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('Treasury'),
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
//        return response()->json($data);
        $model = $this->model->first();
        if (!$model){

            $this->createData($data);
        }else{

            $model['value'] = $model->value + $data['value'];
            $model['reason']=$data['reason'];
            $model->save();
        }



            return response()->json(['status' => 200]);

    }

    public function edit($id)
    {
        return view($this->folder . '/parts/edit', [
            'route' => route($this->route . '.update', $id),
            'obj' => $this->getById($id),
        ]);
    }

    public function update($data, $id )
    {
        if ($this->updateData($id, $data)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }
}
