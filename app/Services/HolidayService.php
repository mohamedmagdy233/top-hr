<?php

namespace App\Services;

use App\Models\Holiday;
use App\Models\HolidayType as ObjModel;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class HolidayService extends BaseService
{
    protected string $folder = 'admin/holidays_type';
    protected string $route = 'holidays_type';

    public function __construct(ObjModel $model ,protected  UserService $userService)
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
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('holidays_type'),
            ]);
        }
    }


    public function showHoliday($request)
    {
        if ($request->ajax()) {
            $obj = $this->userService->getAll();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';
                    $buttons .= '
                            <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-eye"></i>
                            </button>
                       ';




                    return $buttons;
//                })
//                ->addColumn('user', function ($obj) {
//
//                    return $this->users($obj->user_id);
//
//                }) ->editColumn('user_id', function ($obj) {
//
//                    return $obj->user->name;
//                })->editColumn('holiday_type_id', function ($obj) {
//                    return $obj->holidayType->name;
//                })->editColumn('from_day', function ($obj) {
//                    return Carbon::parse($obj->from_day)->format('F d, Y');
//                })->editColumn('to_day',function ($obj){
//
//                    return Carbon::parse($obj->to_day)->format('F d, Y');
//                })->editColumn('note', function ($obj) {
//                    return substr($obj->note, 0, 20) . '...';
//                })->editColumn('status', function ($obj) {
//
//                    if($obj->status == 0){
//                        return '<span class="badge bg-success">'. trns('pending').'</span>';
//                    }elseif ($obj->status == 1){
//                        return '<span class="badge bg-primary">'. trns('accept') .'</span>';
//                    }else{
//                        return '<span class="badge bg-danger">'. trns('reject') .'</span>';
//                    }
                })

                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/showHoliday', [
                'route' => $this->route,
                'pageTitle' => trns('holidays'),
            ]);
        }
    }

    public function users($id){

       $user= $this->userService->getById($id);




       return $user->name;

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

    public function sumUserHoliday($user_id)
    {
        // Retrieve all records for the given user
        $total = Holiday::where('user_id', $user_id)->get();

        $totalDays = 0;

        foreach ($total as $item) {
            $fromDay = Carbon::parse($item->from_day);
            $toDay = Carbon::parse($item->to_day);

            $diffInDays = $fromDay->diffInDays($toDay);

            $diffInDays = ($diffInDays === 0) ? 1 : $diffInDays;

            $totalDays += $diffInDays;
        }

        return $totalDays;
    }


    public function edit($id)
    {
        return view($this->folder . '/parts/edit', [
            'route' => route($this->route . '.update', $id),
            'obj' => $this->getById($id),
        ]);
    }

    public function showHolidayDetails($id)
    {
        return view($this->folder . '/parts/showHolidayDetails', [

            'objs' => Holiday::Where('user_id', $id)->get(),
            'user' => $this->userService->getById($id),
            'count' => Holiday::Where('user_id', $id)
                ->where('status', 1)
                ->count(),
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
