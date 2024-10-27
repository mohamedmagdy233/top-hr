<?php

namespace App\Services;

use App\Models\AdvanceDiscount;
use App\Models\AdvanceDiscount as ObjModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AdvanceDiscountService extends BaseService
{
    protected string $folder = 'admin/advance-discount';
    protected string $route = 'advance-discount';

    public function __construct(ObjModel $model,protected UserService $userService,protected AdvanceService $advanceService)
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
                })->editColumn('value', function ($obj) {

                    return number_format($obj->value) . ' EGP';

                })->addColumn('total', function ($obj) {

                    $total=$this->advanceService->total_for_user($obj->user_id);
                    $lastDayOfLastMonth = Carbon::parse($obj->date)->subMonthNoOverflow()->endOfMonth();

                    $total_advance_discount_until_last_month = $this->model
                        ->where('user_id', $obj->user_id)
                        ->whereDate('date', '<=', $lastDayOfLastMonth)
                        ->sum('value');

                    return ($total - $total_advance_discount_until_last_month) . ' EGP';


                })->addColumn('residual', function ($obj) {

                      $total=$this->advanceService->total_for_user($obj->user_id);
                      $totalUntilNow=$this->total_for_user_until_now($obj->user_id,$obj->date);

                      return $total - $totalUntilNow . ' EGP' ;

                })->editColumn('user_id', function ($obj) {

                    return $obj->user->name ;

                })->editColumn('note', function ($obj) {

                    return $obj->note ==null ? 'لم يتم ذكر السبب' : $obj->note;
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('advance_discount'),
            ]);
        }
    }

    public function total_for_user_until_now($user_id,$date)
    {

        $total=$this->model
            ->where('user_id',$user_id)
            ->where('date','<=',$date)
            ->sum('value');
        return $total;

    }



    public function create()
    {
        return view($this->folder . '/parts/create', [
            'route' => route($this->route . '.store'),
            'users'=>$this->userService->getUsers()
//            'advances'=>$this->userService->getUsers()
        ]);
    }


    public function store($data): \Illuminate\Http\JsonResponse
    {
        $total=$this->advanceService->total_for_user($data['user_id']);

        $value=$this->model->where('user_id',$data['user_id'])->sum('value');

        if ($value + $data['value'] > $total){

            return response()->json('error');

        }
//        $total=$total-$data['value'];
//        $total->save();


        $data['date'] = Carbon::now()->format('Y-m-d');

        $discountOfUserInMonth = $this->model
            ->where('user_id', $data['user_id'])
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->first();

        if ($discountOfUserInMonth) {
            $data['value'] = $discountOfUserInMonth['value'] + $data['value'];

            $model = $this->updateData($discountOfUserInMonth->id, $data);
        } else {
            $model = $this->createData($data);
        }

        if ($model) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit($id)
    {
        return view($this->folder . '/parts/edit', [
            'route' => route($this->route . '.update', $id),
            'obj' => $this->getById($id),
            'users'=>$this->userService->getUsers()

        ]);
    }

    public function update($data, $id )
    {
        $total=$this->advanceService->total_for_user($data['user_id']);
        $value=$this->model->where('user_id',$data['user_id'])->sum('value');

        if ($value + $data['value'] > $total){

            return response()->json('error');

        }

       $data['date']=Carbon::now()->format('Y-m-d');

            $model = $this->updateData($id,$data);
            if ($model) {
                return response()->json(['status' => 200]);
            } else {
                return response()->json(['status' => 405]);
            }
//        }else{
//            return response()->json(['status' => 409]);
//
//        }
    }
}
