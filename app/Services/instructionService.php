<?php

namespace App\Services;

use App\Models\Instruction as ObjModel;
use App\Models\User;
use App\Traits\FirebaseNotification;
use Yajra\DataTables\DataTables;

class instructionService extends BaseService
{
    use FirebaseNotification;
    protected string $folder = 'admin/instructions';
    protected string $route = 'instructions';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }
    //test


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
                    $buttons .= '
                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
                    return $buttons;
                })
                ->editColumn('created_at', function ($obj) {
                    return $obj->created_at->format('Y-m-d');
                })

                ->addIndexColumn()
                ->escapeColumns([])  // Disable automatic escaping for columns like action buttons
                ->make(true);

        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('Instructions'),
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

        $model = $this->createData($data);
        if ($model) {
            $notificationData =[
                'title'=>'تعليمات',
                'body' => "تم اضافه تعليمات جديده يرجي الاطلاع عليها",
            ];
            //test
            $users = User::where('status', 1)
                ->get();


            foreach ($users as $user) {
                $this->sendFcm($notificationData, $user->id);
            }

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
            $notificationData =[
                'title'=>'تعليمات',
                'body' => "تم اضافه تعليمات جديده يرجي الاطلاع عليها",
            ];
            //test
            $users = User::where('status', 1)
                ->get();


            foreach ($users as $user) {
                $this->sendFcm($notificationData, $user->id);
            }
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

}
