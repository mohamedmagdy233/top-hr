<?php

namespace App\Services;

use App\Models\Expense as ObjModel;
use App\Models\User;
use App\Traits\FirebaseNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ExpenseService extends BaseService
{
    use FirebaseNotification;
    protected string $folder = 'admin/expense';
    protected string $route = 'expenses';

    public function __construct(ObjModel $model,protected UserService $userService,protected TreasuryService $treasuryService)
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            // Start building the query from the model
            $query = $this->model->query();

            // Check if a filter (month) is provided in the request
            if ($request->has('filter') && !empty($request->filter)) {
                try {
                    // Assuming the filter is the month number (1-12)
                    $filterMonth = $request->filter;
                    $currentYear = date('Y'); // Use the current year for filtering

                    // Apply the year and month filters
                    $query->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $filterMonth);
                } catch (\Exception $e) {
                    // Handle exception if any issues arise (e.g., invalid date)
                    // You may choose to log the error or return an appropriate response
                }
            }

            // Pass the filtered or full query to DataTables
            return DataTables::of($query)
                ->addColumn('action', function ($obj) {
                    $buttons = '
                    <button type="button" data-id="' . $obj->id . '" class="btn btn-pill btn-info-light editBtn">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
                    return $buttons;
                })
                ->editColumn('image', function ($obj) {
                    if ($obj->image != null) {
                        return '<img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('storage/' . $obj->image) . '">';
                    } else {
                        return '<img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('assets/uploads/avatar.png') . '">';
                    }
                })
                ->editColumn('created_at', function ($obj) {
                    return $obj->created_at == null ? '' : \Carbon\Carbon::parse($obj->created_at)->format('Y-m-d');
                })
                ->editColumn('value', function ($obj) {
                    return number_format($obj->value) . ' EGP';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            // For non-AJAX requests, return the view with necessary data
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('expenses'),
            ]);
        }
    }


    public function create()
    {
        return view($this->folder . '/parts/create', [
            'route' => route($this->route . '.store'),
        ]);
    }

    public function store( $request): \Illuminate\Http\JsonResponse
    {
        if ($request->has('image')) {
        $image = $request->file('image')->store('expenses', 'public');
    } else {
        $image = null;
    }

        $values = $request->input('value')==null ? 0 : $request->input('value');
        $reasons = $request->input('reason');

        if (is_array($values) && is_array($reasons) && count($values) === count($reasons)) {
            DB::beginTransaction();

            try {
                foreach ($values as $index => $value) {
                    $reason = $reasons[$index];

                    if (!is_numeric($value)) {
                        DB::rollBack();
                        return response()->json(['status' => 400, 'message' => 'Invalid value provided']);
                    }

                    $total = $this->treasuryService->model->first();
                    if (!$total) {
                        $total = $this->treasuryService->createData(['value' => 0]);
//                        $total->value =0;
                    }
                    $total->value=$total->value==null ? 0 : $total->value;


                    $total->value -= $value;
                    $total->save();

                    $data = [
                        'value' => $value,
                        'reason' => $reason,
                        'image' => $image,
                        'date' => $request->date,

                    ];



                    $model = $this->createData($data);

                    if (!$model) {

                        DB::rollBack();
                        return response()->json(['status' => 405, 'message' => 'حدث خطا']);
                    }
                    $notificationData =[
                        'title'=>'مصروف',
                        'body' => "تم سحب مبلغ نقدي بقيمة {$data['value']} جنيه" . ' - ' . " {$data['reason']}",
                    ];
                    //test
                    $hrUser = User::whereNull('group_id')
                        ->where('status', '=', 1)
                        ->get();

                    foreach ($hrUser as $hr) {
                        $this->sendFcm($notificationData, $hr->id);
                    }
                }

                DB::commit();

                return response()->json(['status' => 200, 'message' => 'Data saved successfully']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => 500, 'message' => 'An error occurred', 'error' => $e->getMessage()]);
            }
        } else {
            // Return error response if input arrays are invalid
            return response()->json(['status' => 400, 'message' => 'Invalid input data']);
        }
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
        $expenseValue=$this->getById($id);
        $total= $this->treasuryService->model->first();
//        if ($data['value'] <= $total->value + $expenseValue->value ) {

            $total->value = $total->value + $expenseValue->value - $data['value'];
            $total->save();

        if (isset($data['image'])) {
            if ($expenseValue->image) {
                Storage::delete('/public/' . $expenseValue->image);
            }

            $data['image'] = $data['image']->store('expenses', 'public');
        } else {
            $data['image'] = null;
        }




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
