<?php

namespace App\Services;

use App\Models\Advance as ObjModel;
use App\Models\Advance;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AdvanceService extends BaseService
{
    use FirebaseNotification;

    protected string $folder = 'admin/advance';
    protected string $route = 'advances';

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
                    $success = $reject = '';
                    if ($obj->approved == 1) {
                        $success = '<button type="button" data-status="1" data-id="' . $obj->id . '" class="ml-2 btn btn-pill btn-success-light disabled">
                            <i class="fa fa-check"></i> ' . trns('was_approved') . '
                            </button>';
                        //                        $reject = '<button type="button"  data-status="2" data-id="' . $obj->id . '" class="btn btn-pill btn-danger-light statusBtn">
                        //                            <i class="fa fa-times"></i> ' . trns('reject') . '
                        //                            </button>';
                    } elseif ($obj->approved == 2) {
                        //                        $success = '<button type="button" data-status="1" data-id="' . $obj->id . '" class="ml-2 btn btn-pill btn-success-light statusBtn">
                        //                            <i class="fa fa-check"></i> ' . trns('approve') . '
                        //                            </button>';
                        $reject = '<button type="button"  data-status="2" data-id="' . $obj->id . '" class="btn btn-pill btn-danger-light disabled">
                            <i class="fa fa-times"></i> ' . trns('reject') . '
                            </button>';
                    } else {
                        $success = '<button type="button" data-status="1" data-id="' . $obj->id . '" class="ml-2 btn btn-pill btn-success-light statusBtn">
                            <i class="fa fa-check"></i> ' . trns('approve') . '
                            </button>';
                        $reject = '<button type="button"  data-status="2" data-id="' . $obj->id . '" class="btn btn-pill btn-danger-light statusBtn">
                            <i class="fa fa-times"></i> ' . trns('reject') . '
                            </button>';
                    }

                    return $success . ' ' . $reject;
                })
                ->editColumn('group_id', function ($obj) {
                    return $obj->user->group->name;
                })
                ->editColumn('user_id', function ($obj) {
                    return $obj->user->name;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('advances'),
            ]);
        }
    }

    public function total_for_user($user_id)
    {
       $total= $this->model->where('user_id',$user_id)->sum('amount');
       return $total;


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
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit($obj)
    {
        return view($this->folder . '/parts/edit', [
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

    public function updateStatus($request)
    {
        $advance = Advance::find($request->id);

        if ($this->updateData($request->id, ['approved' => $request->status])) {
            if ($request->status == 1) {

                $fcmD = [
                    'title' => 'السلفة',
                    'body' => 'تم الموافقه علي السلفه بنجاح',
                ];
                $this->sendFcm($fcmD, $advance->user_id);
            } elseif ($request->status == 2) {

                $fcmD = [
                    'title' => 'السلفة',
                    'body' => 'نأسف تم رفض سلفتك',
                ];
                $this->sendFcm($fcmD, $advance->user_id);
            }
            return response()->json(['status' => 200, 'message' => trns('status_updated')]);
        } else {
            return response()->json(['status' => 405, 'message' => trns('something_went_wrong')]);
        }
    }
}
