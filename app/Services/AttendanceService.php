<?php

namespace App\Services;

use App\Models\Attendance as ObjModel;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService extends BaseService
{
    protected string $folder = 'admin/attendance';
    protected string $route = 'attendances';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index($request)
    {

        $checkIn = $this->model->query()
            ->where('date', '=', date('Y-m-d'))
            ->where('check_out', '=', null)
            ->get()
            ->map(function ($record) {
                // Format check_in time to hh:mm
                $record->check_in = Carbon::parse($record->check_in)->format('H:i');
                return $record;
            });

        $checkOut = $this->model->query()
            ->where('date', '=', date('Y-m-d'))
            ->where('check_out', '!=', null)
            ->get()
            ->map(function ($record) {
                // Format check_out time to hh:mm
                $record->check_out = Carbon::parse($record->check_out)->format('H:i');
                return $record;
            });


        return view($this->folder . '/index', [
            'route' => $this->route,
            'pageTitle' => trns('attendances'),
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
        ]);
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
}
