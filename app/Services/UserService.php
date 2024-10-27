<?php

namespace App\Services;

use App\Models\Addition;
use App\Models\Advance;
use App\Models\AdvanceDiscount;
use App\Models\Attendance;
use App\Models\Branch;
use App\Models\Group;
use App\Models\Holiday;
use App\Models\Incentive;
use App\Models\User as ObjModel;
use App\Models\User;
use App\Traits\FirebaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserService extends BaseService
{
    use FirebaseNotification;

    protected string $folder = 'admin/user';
    protected string $route = 'users';

    public function __construct(ObjModel $model )
    {
        parent::__construct($model);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj =  $this->model
                ->orderByRaw("CASE WHEN group_id IS NULL THEN 0 ELSE 1 END, group_id DESC")
                ->get();

            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {

                    $buttons = '';
                    $buttons .= '
                            <li><button type="button" data-id="' . $obj->id . '" class="dropdown-item btn editBtn">
                                <i class="fa fa-edit text-primary"></i>
                                  ' . trns('edit') . '
                            </button></li>';
                    if ($obj->group_id != null) {

//                        $buttons .= '
//                       <li><button type="button" data-id="' . $obj->id . '" class="dropdown-item btn incentiveBtn">
//                            <i class="fas fa-money-bill text-info"></i>
//                              ' . trns('incentive_/_deduction') . '
//                        </button></li>';
//                        $buttons .= '
//                        <li>
//                            <button
//                                onclick="window.location.href=\'' . route('users.getIncentives', $obj->id) . '\'"
//                                class="dropdown-item btn">
//                                <i class="fas fa-wave-square text-info"></i>
//                                ' . trns('oprations') . '
//                            </button>
//                        </li>';




                        $buttons .= '
                    <li><button type="button" class="dropdown-item btn" onclick="window.location.href=\'' . route('users.show', $obj->id) . '\'">
                        <i class="fas fa-user text-success"></i>
                            ' . trns('employee_details') . '
                    </button></li>';
                    }



                    $buttons .= '<li><button class="dropdown-item btn" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash text-danger"></i>
                        ' . trns('delete') . '
                        </button></li>';

                    $dropdowns = '<div class="dropdown" style="display: inline-block;">
                                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>' . trns('actions') . '</span>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">'
                        . $buttons .
                        '</ul>

                                        </div>';

                    return $dropdowns;
                })->editColumn('image', function ($obj) {
                    if ($obj->image != null) {
                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('storage/' . $obj->image) . '">
                    ';
                    } else {
                        return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="' . asset('assets/uploads/avatar.png') . '">
                    ';
                    }
                })
                ->editColumn('salary', function ($obj) {
                    return $obj->salary?? $obj->salary . ' جنيه';

                }) ->editColumn('created_at', function ($obj) {

                    return $obj->created_at->format('Y-m-d');

                })->editColumn('holidays', function ($obj) {
                    return $this->sumUserHoliday($obj->id)  +  $obj->holidays;
                })
                ->addColumn('group_id', function ($obj) {

                    return $obj->group_id == null ? 'مدير الموارد البشريه' : $obj->group->name;
                })
                ->addColumn('status', function ($obj) {
                    return $obj->status == 1 ? trns('active') : trns('inactive');
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('employees'),
            ]);
        }
    }
    public function getUsers()
    {
        return $this->model->whereNotNull('group_id')->get();

    }

    public function sumUserHoliday($user_id)
    {
        // Retrieve all records for the given user
        $total = Holiday::where('user_id', $user_id)->where('status',1)->get();

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

    public function create()
    {
        $branches = Branch::get();
        $groups = Group::get();
        $code = $this->generateCode();
        return view($this->folder . '/parts/create', [
            'route' => route($this->route . '.store'),
            'branches' => $branches,
            'groups' => $groups,
            'code' => $code
        ]);
    }

    public function show($obj,$request)
    {
//        $currentDate = Carbon::now();
//        $endOfLastMonth = $currentDate->copy()->subMonth()->endOfMonth();
//        $registered_at = Carbon::parse($obj->registered_at);
//
//        $startDate = $registered_at->copy()->startOfMonth();
//        $endDate = $endOfLastMonth;
//
//        $monthData = [];
//
//        while ($startDate->lte($endDate)) {
//            $monthStart = $startDate->copy()->startOfMonth();
//            $monthEnd = $startDate->copy()->endOfMonth();
//            $monthKey = $startDate->format('Y-m');
//
//            // عدد ايام العمل في الشهر (Adjust usersDays method to accept date range)
//            $userDays = $this->usersDays($obj, $monthStart, $monthEnd);
//
//            // الخصم الي تم عليه طول الشهر
//            $incentives0 = $obj->incentives()
//                ->where('incentive', 0)
//                ->whereBetween('date', [$monthStart, $monthEnd])
//                ->sum('value');
//
//            // الحافز الي تم عليه طول الشهر
//            $incentives1 = $obj->incentives()
//                ->where('incentive', 1)
//                ->whereBetween('date', [$monthStart, $monthEnd])
//                ->sum('value');
//
//            // ايام الغياب
//            $holidays = $obj->holidays()
//                ->whereBetween('from_day', [$monthStart, $monthEnd])
//                ->whereBetween('to_day', [$monthStart, $monthEnd])
//                ->where('status', 1)
//                ->count();
//
//            // السلف الي تم عليه طول الشهر
//            $advances =AdvanceDiscount::where('user_id',$obj->id)
//                ->whereBetween('date', [$monthStart, $monthEnd])
//                ->sum('value');
//
//            $additions=Addition::where('user_id', $obj->id)
//                ->where('created_at','<=', $monthEnd)
//                ->sum('value');
//
//            // صافي المرتب
//            $netSalary = $obj->salary + $additions+ $incentives1 - $incentives0 - $advances;
//
//
//
//            // Collect data for the current month
//            $monthData[] = [
//                'month' => $monthKey,
//                'days_worked' => $userDays,
//                'incentivesWith0' => $incentives0,
//                'incentivesWith1' => $incentives1,
//                'netSalary' => $netSalary,
//                'holidays' => $holidays,
//                'advances' => $advances,
//                'additions' => $additions
//            ];
//
//            // Move to the next month
//            $startDate->addMonth();
//        }

        $currentDate = Carbon::now();
        $endOfLastMonth = $currentDate->copy()->endOfMonth();
        $registered_at = Carbon::parse($obj->registered_at); // Assuming $obj is defined or passed in

        $startDate = $registered_at->copy()->startOfMonth();
        $endDate = $endOfLastMonth;

        $monthData = [];

        while ($startDate->lte($endDate)) {
            $monthStart = $startDate->copy()->startOfMonth();
            $monthEnd = $startDate->copy()->endOfMonth();
            $monthKey = $startDate->format('Y-m');

            // Check if there are any records for the current month
            $userDays = $this->usersDays($obj, $monthStart, $monthEnd) ?? 0;

            $incentives0 = $obj->incentives()
                ->where('incentive', 0)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('value') ?? 0;

            $incentives1 = $obj->incentives()
                ->where('incentive', 1)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('value') ?? 0;

            $holidays = $obj->holidays()
                ->whereBetween('from_day', [$monthStart, $monthEnd])
                ->whereBetween('to_day', [$monthStart, $monthEnd])
                ->where('status', 1)
                ->count() ?? 0;

            $advances = AdvanceDiscount::where('user_id', $obj->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('value') ?? 0;

            $additions = Addition::where('user_id', $obj->id)
                ->where('created_at', '<=', $monthEnd)
                ->sum('value') ?? 0;

            // If there's no record for the month, return default values for salary
            $netSalary = $obj->salary + $additions + $incentives1 - $incentives0 - $advances;

            // Collect data for the current month
            $monthData[] = [
                'month' => $monthKey,
                'days_worked' => $userDays,
                'incentivesWith0' => $incentives0,
                'incentivesWith1' => $incentives1,
                'netSalary' => number_format($netSalary, 0),
                'salary' => $obj->salary + $additions,
                'advances' => $advances,
                'additions' => $additions,
                'holidays' => $holidays,
            ];

            // Move to the next month
            $startDate->addMonth();
        }




        $dailyRecords = $this->userActionsInDays($obj,$request->month);

        // Pass the month data to the view
        return view($this->folder . '/parts/show', [
            'obj' => $obj,
            'route' => $this->route,
            'monthData' => $monthData,
            'dailyRecords'=>$dailyRecords,
        ]);
    }

    public function userActionsInDays($obj, $month = null)
    {
        // Filter attendances by selected month if provided
        $attendances = $obj->attendances()
            ->whereNotNull('check_in');

        if ($month && $month !== 'all') {
            // Filter by the provided month, assuming 'date' is in Y-m-d format
            $attendances->whereMonth('date', Carbon::parse($month)->month);
        }

        $attendances = $attendances
            ->get()
            ->groupBy(function($attendance) {
                return Carbon::parse($attendance->date)->format('Y-m-d'); // Group by day
            });

        $dailyRecords = [];

        foreach ($attendances as $date => $dayAttendances) {
            $firstCheckIn = $dayAttendances->sortBy('check_in')->first();
            $lastCheckOut = $dayAttendances->sortByDesc('check_out')->first();
            $diff_time = $dayAttendances->sum('diff_time');
            $hours = floor($diff_time / 60);
            $minutes = $diff_time % 60;
            $formattedTime = sprintf('%02d:%02d', $hours, $minutes);

            $dailyRecords[$date] = [
                'firstCheckIn' => $firstCheckIn,
                'lastCheckOut' => $lastCheckOut,
                'diff_time' => $formattedTime
            ];
        }





        return $dailyRecords;
    }
    public function usersDays($obj, $monthStart, $monthEnd)
    {
        $currentDate = Carbon::now();
        $startOfLastMonth = $monthStart->copy()->startOfMonth();
        $endOfLastMonth = $monthEnd->copy()->endOfMonth();
        $attendances = $obj->attendances()
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->count();


        return $attendances;
    }

    public function userHours($obj, $date)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $attendances = $obj->attendances()
            ->whereDate('date', '<=', $date)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('diff_time');

        return $attendances / 60;
    }
    public function userWorkHours($obj, $date)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $attendances = $obj->attendances()
            ->whereDate('date', '<=', $date)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('diff_time');

        $hours = intdiv($attendances, 60);
        $minutes = $attendances % 60;
        $attendances = sprintf('%02d:%02d', $hours, $minutes);
        return $attendances;
    }

    public function store($data): \Illuminate\Http\JsonResponse
    {

        $data['password'] = Hash::make($data['password']);


        $model = $this->createData($data);
        if ($model) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    public function edit($obj)
    {
        $branches = Branch::get();
        $groups = Group::get();
        return view($this->folder . '/parts/edit', [
            'route' => route($this->route . '.update', $obj->id),
            'obj' => $obj,
            'totalDays' => $this->sumUserHoliday($obj->id)+$obj->holidays,
            'branches' => $branches,
            'groups' => $groups
        ]);
    }

    public function update($id, $data)
    {

        if ($data['password'] && $data['password'] != null) {

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($this->updateData($id, $data)) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }

    protected function generateCode(): string
    {
        do {
            $code = Str::random(11);
        } while ($this->firstWhere(['code' => $code]));

        return $code;
    }


    public function incentives($obj)
    {
        return view($this->folder . '/parts/incentive', [
            'route' => route($this->route . '.addIncentives', $obj->id),
            'obj' => $obj,
        ]);
    }

    public function getIncentives($obj,$monthDate)
    {

        $year = $monthDate->year; // 2024
        $month = $monthDate->month; // 7

        $IncentivesWith0 = Incentive::where('user_id', $obj->id)
            ->where('incentive', 0)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();


        $IncentivesWith1 = Incentive::where('user_id', $obj->id)
            ->where('incentive', 1)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();




        return view($this->folder . '/parts/get_incentives', [
            'route' => route($this->route . '.addIncentives', $obj->id),
            'obj' => $obj,
            'IncentivesWith0' => $IncentivesWith0,
            'IncentivesWith1' => $IncentivesWith1,
            'pageTitle' => trns('incentives'),
        ]);
    }

    public function addIncentives($request)
    {
        $newIncentive = new Incentive();
        $newIncentive->user_id = $request->id;
        $newIncentive->incentive = $request->incentive;
        $newIncentive->value = $request->value;
        $newIncentive->reason = $request->reason;
        $newIncentive->date = Carbon::now()->format('Y-m-d');

        $user = User::where('id', $request->id)->first();

        if ($request->incentive == 1) {
            $notificationData = [
                'title' => 'حافز',
                'body' => 'تم اضافة حافز  بقيمه  ' . $request->value . ' جنيه '.'بسبب  ' . $request->reason
            ];

            $this->sendFcm($notificationData, $user->id);
        } else {
            $notificationData = [
                'title' => 'خصم',
                'body' => '  تم  اضافه خصم بمقدار   ' . $request->value . ' جنيه '.'بسبب  ' . $request->reason
            ];

            $this->sendFcm($notificationData, $user->id);
        }

        if ($newIncentive->save()) {
            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }


    public function deleteIncentive($id)
    {
        $incentive = Incentive::find($id);

        if (!$incentive) {
            return redirect()->back()->with('error', 'الحافز غير موجود');
        }

//        $incentiveDate = Carbon::parse($incentive->date);
//        $currentDate = Carbon::now();
//
//        if ($incentiveDate->format('Y-m') != $currentDate->format('Y-m')) {
//            return redirect()->back()->with('error', 'لا يمكن حذف الحافز او الخصم  لأنه ليس من الشهر الجاري');
//        }


        if ($incentive->delete()) {
            return redirect()->back()->with('success', 'تم الحذف بنجاح');
        } else {
            return  redirect()->back()->with('error', 'حدث خطأ ما');
        }
    }


    public function userDays($user)
    {
        $minute_price = $user->hour_price / 60;
        $default_hours = $user->group->hours;

        $currentDate = Carbon::now()->format('Y-m-d');

        $days = Attendance::selectRaw("DATE_FORMAT(date, '%Y-%m-%d') as day, SUM(diff_time) as total_minutes")
            ->where('user_id', $user->id)
            ->where('date', '<', $currentDate)
            ->groupBy('day')
            ->get();
        //        return response()->json($days) ;

        $days->map(function ($day) use ($minute_price, $user) {
            $day_format = date('Y-m-d', strtotime($day->day)); // Ensure the day format matches

            $advances = Advance::query()
                ->where('user_id', $user->id)
                ->where('approved', '1')
                ->whereDate('date', $day_format)
                ->get();

            $advance_price = $advances->sum('amount'); // Assuming 'amount' is the column to sum up

            $day->day = Carbon::parse($day->day)->format('d M Y');
            $price = $minute_price * $day->total_minutes;
            $total_price = $price - $advance_price;

            // Calculate hours and minutes from total minutes
            $hours = intdiv($day->total_minutes, 60);
            $minutes = $day->total_minutes % 60;
            $day->work_hour = sprintf('%02d:%02d', $hours, $minutes); // Format as hh:mm

            $day->total_price = number_format(round($total_price), 2);
        });

        $data = [];

        foreach ($days as $key => $day) {
            $day_value = Carbon::createFromFormat('d M Y', $day->day)->format('Y-m-d');

            // Correctly parse start and end of the day for the given day
            $startOfDay = Carbon::createFromFormat('Y-m-d', $day_value)->startOfDay();
            $endOfDay = Carbon::createFromFormat('Y-m-d', $day_value)->endOfDay();

            // Get attendance for the employee on the specific day
            $attendanceDays = Attendance::query()
                ->where('user_id', $user->id)
                ->whereDate('date', $day_value)
                ->select('date')
                ->pluck('date')
                ->toArray();

            // Calculate absence
            $absenceDays = empty($attendanceDays) ? 1 : 0;

            // Incentive calculation for the day
            $incentive = Incentive::query()
                ->where('incentive', '=', 1)
                ->whereDate('date', $day_value)
                ->sum('value');

            $deduction = Incentive::query()
                ->where('incentive', '=', 0)
                ->whereDate('date', $day_value)
                ->sum('value');

            // Filling the data array
            $data[$key]['day'] = $day->day;
            $data[$key]['work_hour'] = $day->work_hour;
            $data[$key]['absence'] = $absenceDays;
            $data[$key]['incentive'] = $incentive;
            $data[$key]['deduction'] = $deduction;
            $data[$key]['salary'] = $default_hours * $user->hour_price;
            $data[$key]['default_hours'] = $default_hours;
            $data[$key]['net_salary'] = $minute_price * $day->total_minutes;
        }

        return $data;
    }
}
