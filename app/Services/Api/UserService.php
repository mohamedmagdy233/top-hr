<?php

namespace App\Services\Api;

use App\Http\Resources\AttendanceResource;
use App\Http\Resources\UserResource;
use App\Models\Addition;
use App\Models\Advance;
use App\Models\AdvanceDiscount;
use App\Models\Attendance;
use App\Models\ContactUs;
use App\Models\Group;
use App\Models\Holiday;
use App\Models\HolidayType;
use App\Models\Incentive;
use App\Models\Instruction;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User as ObjModel;
use App\Models\User;
use App\Services\BaseService;
use App\Services\SettingService;
use App\Traits\FirebaseNotification;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserService extends BaseService
{
    use FirebaseNotification;

    public function __construct(ObjModel $model, protected SettingService $settingService)
    {
        parent::__construct($model);
    }

    public function home($request)
    {
        $user = Auth::user();
        $user['token'] = $request->bearerToken();

        $userId = $user->id;
        $today = now()->toDateString();

        // Retrieve today's attendance
        $dayAttendances = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Initialize variables for total diff_time, first check_in, and last check_out
        $totalDiffTime = 0;
        $firstCheckIn = null;  //
        $lastCheckOut = '00:00';

        if ($dayAttendances->isNotEmpty()) {
            foreach ($dayAttendances as $dayAttendance) {
                $checkIn = $dayAttendance->check_in
                    ? date('H:i', strtotime($dayAttendance->check_in))
                    : '00:00';

                $checkOut = $dayAttendance->check_out
                    ? date('H:i', strtotime($dayAttendance->check_out))
                    : '00:00';

                $dayAttendance->check_in = $checkIn;
                $dayAttendance->check_out = $checkOut;

                $dayAttendance->diff_time = $checkOut != '00:00'
                    ? $this->calculateTimeDifference($checkIn, $checkOut)
                    : 0;

                // Update total diff_time
                $totalDiffTime += $dayAttendance->diff_time;

                // Update first check_in if it is not '00:00' and is earlier than the current firstCheckIn
                if ($checkIn != '00:00' && ($firstCheckIn === null || $checkIn < $firstCheckIn)) {
                    $firstCheckIn = $checkIn;
                }

                // Update last check_out
                if ($checkOut > $lastCheckOut) {
                    $lastCheckOut = $checkOut;
                }
            }
        }

        // Set firstCheckIn to '00:00' if no valid check-in times were found
        if ($firstCheckIn === null) {
            $firstCheckIn = '00:00';
        }

        // Calculate total diff_time in hours and minutes
        $totalHours = intdiv($totalDiffTime, 60);
        $totalMinutes = $totalDiffTime % 60;

        // Current date to determine the cutoff for months
        $currentDate = now()->format('Y-m-d');

        // Retrieve months and calculate total months
        $months = Attendance::selectRaw("DATE_FORMAT(date, '%Y-%m') as month")
            ->where('user_id', $userId)
            ->where('date', '<=', $currentDate)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->toArray();

        $totalMonths = count($months);
        $totalMonths = $totalMonths == 0 ? 0 : $totalMonths;

        // Count approved advances
        $approvedAdvancesCount = Advance::where('user_id', $userId)
            ->where('approved', 1)
            ->count();

        // Retrieve user stats and calculate hours and minutes
        $userStats = Attendance::selectRaw("DATE_FORMAT(date, '%Y-%m') as month, SUM(diff_time) as total_minutes")
            ->where('user_id', $userId)
            ->groupBy('month')
            ->get();

        $userStats->map(function ($item) {
            $item['total_hours'] = floor($item['total_minutes'] / 60);
            $item['total_minutes'] = (int)$item['total_minutes'];
            $item['month'] = Carbon::parse($item['month'])->format('m');
        });

        // Retrieve the latest attendance record for today
        $dayAttendancesLatest = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->latest('created_at')
            ->first();

        if ($dayAttendancesLatest) {
            $dayAttendancesLatest->check_in = $dayAttendancesLatest->check_in
                ? date('H:i', strtotime($dayAttendancesLatest->check_in))
                : '00:00';
            $dayAttendancesLatest->check_out = $dayAttendancesLatest->check_out
                ? date('H:i', strtotime($dayAttendancesLatest->check_out))
                : '00:00';

            $diffTimeMinutes = $dayAttendancesLatest->check_out != '00:00'
                ? $this->calculateTimeDifference($dayAttendancesLatest->check_in, $dayAttendancesLatest->check_out)
                : 0;

            // Convert diff_time to HH:mm format
            $diffHours = intdiv($diffTimeMinutes, 60);
            $diffMinutes = $diffTimeMinutes % 60;
            $dayAttendancesLatest->diff_time = sprintf('%02d:%02d', $diffHours, $diffMinutes);
        } else {
            $dayAttendancesLatest = new \stdClass();
            $dayAttendancesLatest->check_in = '00:00';
            $dayAttendancesLatest->check_out = '00:00';
            $dayAttendancesLatest->diff_time = '00:00';
        }

        $count_of_user_holidays = Holiday::where('user_id', $userId)->where('status', 1)->count();
        $count_of_user_days = 0;

        $days_of_user_holidays = Holiday::where('user_id', $userId)->where('status', 1)->get();
        foreach ($days_of_user_holidays as $day) {

            $from = Carbon::parse($day->from_day);
            $to = Carbon::parse($day->to_day);
            $diff = $from->diffInDays($to) + 1;
            $count_of_user_days += $diff;

        }
        $sliders = Slider::where('active', 1)
            ->select('image', 'id')  // Only fetch 'image' and 'id'
            ->get()
            ->map(function ($slider) {
                // Prepend the base URL to the image path
                $slider->image = url('storage/' . $slider->image);
                return $slider;
            });


        // Compile all data
        $data = [
            'user' => new UserResource($user),
            'day_attendance' => $dayAttendancesLatest,
            'total_diff_time' => sprintf('%02d:%02d', $totalHours, $totalMinutes),
            'first_check_in' => $firstCheckIn,
            'last_check_out' => $lastCheckOut,
            'total_months' => $totalMonths,
            'months' => $months,
            'advance' => $approvedAdvancesCount,
            'available_holidays' =>$user->holidays ,
//            'count_of_user_holidays' => $count_of_user_holidays,
            'count_of_user_holidays' => \str($count_of_user_days),
            'all_holidays' => $user->holidays + $count_of_user_days ,

            'userStats' => $userStats,
            'sliders'=>$sliders
        ];

        return $this->jsonData('تم تحميل البيانات', $data);
    }

    // end of home

    public function checkin($request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'image' => 'required|image',
        ], [
            'image.required' => 'يجب تحميل صورة الدخول',
            'image.image' => 'يجب ان يكون الصورة صورة',
            'lat.required' => 'يجب تحميل موقع الدخول',
            'long.required' => 'يجب تحميل موقع الدخول'
        ]);

        $checkAttend = Attendance::query()
            ->where('user_id', $user->id)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->where('check_out', null)
            ->first();

        if ($validator->fails()) {
            return $this->jsonData($validator->errors(), null, 422);
        }


        if ($checkAttend) {
            return $this->jsonData('يجب تسجيل الخروج قبل الدخول', null, 422);
        }

        $image = null;
        if ($request->hasFile('image')) {
            $image = $this->saveImage($request->image, 'users', 'image', 50);
        }

        $newCheckIn = new Attendance();
        $newCheckIn->user_id = $user->id;
        $newCheckIn->date = Carbon::now()->format('Y-m-d');
        $newCheckIn->lat = $request->lat;
        $newCheckIn->long = $request->long;
        $newCheckIn->check_in = now()->toTimeString();
        $newCheckIn->image = $image;
        $newCheckIn->note = $request->note ?? null;
        $newCheckIn->save();

        $notificationData = [
            'title' => 'تسجيل حضور',
            'body' => 'قام ' . $newCheckIn->user->name . ' بتسجيل حضور في ' . now()->format('g:i A'),
        ];

        $hrUser = User::whereNull('group_id')
            ->where('status', '=', 1)
            ->get();

            foreach ($hrUser as $hr) {
                $this->sendFcm($notificationData, $hr->id);
            }


        return $this->jsonData('حمد الله على السلامة يا ' . $user->name . '، يومك سعيد', new AttendanceResource($newCheckIn), 201);

    } // end of checkin

    public function checkout($request)
    {

        $vaildator = Validator::make($request->all(), [
            'check_out_lat' => 'required',
            'check_out_long' => 'required',
            'check_out_image' => 'required|image',
        ], [
            'check_out_image.required' => 'يجب تحميل صورة الخروج',
            'check_out_image.image' => 'يجب ان يكون الصورة صورة',
            'check_out_lat.required' => 'يجب تحميل موقع الخروج',
            'check_out_long.required' => 'يجب تحميل موقع الخروج',
        ]);
        if ($vaildator->fails()) {
            return $this->jsonData($vaildator->errors(), null, 422);
        }


        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', now()->format('Y-m-d'))
            ->whereNull('check_out')
            ->first();

        // Handle the case where there is no check-in record
        if (!$attendance) {
            return $this->jsonData('يجب تسجيل الدخول قبل الخروج', null, 422);
        }

        $attendance->check_out = now()->toTimeString();
        $attendance->check_out_lat = $request->check_out_lat;
        $attendance->check_out_long = $request->check_out_long;
        $attendance->note = $request->note ?? null;

        $attendance->check_out_image = $this->saveImage($request->check_out_image, 'users', 'check_out_image', 50);
        $attendance->diff_time = $this->calculateTimeDifference($attendance->check_in, $attendance->check_out);

        $attendance->save();

        $diff_time = $attendance->diff_time;
        $GroupHours = $attendance->user->group->hours;
        $GroupAdvances = $attendance->user->group->advances;

        if ($diff_time < $GroupHours) {

            $value = ($GroupAdvances * $GroupHours) - ($GroupAdvances * ($diff_time / 60));

            $incentive = Incentive::create([
                'user_id' => $attendance->user_id,
                'incentive' => 0,
                'date' => Carbon::now()->format('Y-m-d'),
                'value' => $value

            ]);
        }

        $attendance->diff_time = $this->calculateTimeDifferenceInHHMM($attendance->check_in, $attendance->check_out);

        $notificationData = [
            'title' => 'تسجيل انصراف',
            'body' => 'قام ' . $attendance->user->name . ' بتسجيل انصراف في ' . now()->format('g:i A'),
        ];

        $hrUser = User::whereNull('group_id')
            ->where('status', '=', 1)
            ->get();
//
        foreach ($hrUser as $hr) {
            $this->sendFcm($notificationData, $hr->id);
        }


        return $this->jsonData('مع السلامة يا ' . $attendance->user->name . ' نشوفك علي خير', new AttendanceResource($attendance));
    }

    /**
     * Calculate the difference in time between two timestamps.
     *
     * @param string $checkIn Time of check-in
     * @param string $checkOut Time of check-out
     * @return int Difference in minutes
     */
    private function calculateTimeDifference($checkIn, $checkOut)
    {
        $checkInTime = Carbon::createFromTimeString($checkIn);
        $checkOutTime = Carbon::createFromTimeString($checkOut);

        return $checkOutTime->diffInMinutes($checkInTime);
    }

    private function calculateTimeDifferenceInHHMM($checkIn, $checkOut)
    {
        $checkInTime = Carbon::createFromTimeString($checkIn);
        $checkOutTime = Carbon::createFromTimeString($checkOut);

        // Calculate the difference in minutes
        $diffTimeInMinutes = $checkOutTime->diffInMinutes($checkInTime);

        // Convert the difference to hours and minutes
        $hours = floor($diffTimeInMinutes / 60);
        $minutes = $diffTimeInMinutes % 60;

        // Format as hh:mm (single colon)
        $formattedTime = sprintf('%02d:%02d', $hours, $minutes);

        return $formattedTime;
    }


//    public function userMonths()
//    {
//        $user = ObjModel::find(auth()->id());
//        if (!$user) {
//            return $this->jsonData('لا يوجد مستخدم', null, false);
//        }
//
//
//        $currentDate = Carbon::now();
//
//        $startOfLastMonth = Carbon::parse($user->registered_at)->copy();
//        $endOfLastMonth = $currentDate->copy()->subMonth()->endOfMonth();
////        return $endOfLastMonth;
//
//
//        $userDays = $this->usersDays($user); //عدد ايام العمل في الشهر
//
//
//        // الخصم الي تم عليه طول الشهر
//        $incentives0 = $user->incentives()
//            ->where('incentive', 0)
//            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
//            ->sum('value');
//        // الحافز الي تم عليه طول الشهر
//
//        $incentives1 = $user->incentives()
//            ->where('incentive', 1)
//            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
//            ->sum('value');
//
//
//        // ايام الغياب
//        $holidays = $user->holidays()
//            ->where('from_day', $startOfLastMonth)
//            ->where('to_day', $endOfLastMonth)
//            ->where('status', 1)
//            ->count();
//
//        // السلف الي تم عليه طول الشهر
////
////        $advances = $user->advances()
////            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
////            ->where('approved', 1)
////            ->sum('amount');
//
//        $advances =AdvanceDiscount::where('user_id',$user->id)
//            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth])
//            ->sum('value');
//
//        $additions=Addition::where('user_id', $user->id)
//            ->where('created_at','<=', $endOfLastMonth)
//            ->sum('value');
////        return $endOfLastMonth;
//
//
//        // صاقي المرتب
//        $netSalary = $user->salary + $incentives1 - $incentives0 - $advances +$additions;
//
//
////        // Calculate attendance details
////        $daysWithAttendance = $user->attendances()
////            ->whereBetween('date', [$startOfMonth, $endOfMonth])
////            ->distinct('date')
////            ->count('date');
////
////        $allDates = collect($startOfMonth->daysUntil($endOfMonth)->toArray())
////            ->map(fn($date) => $date->format('Y-m-d'));
////
////        $attendances = $user->attendances()
////            ->whereBetween('date', [$registeredAt, $currentDate])
////            ->pluck('date')
////            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'));
////
////        $daysWithoutRecord = $allDates->diff($attendances);
////        $daysWithoutRecordCount = $daysWithoutRecord->count();
////
////        // Group hour data
////        $group = Group::find($user->group_id);
////        $groupHours = $group ? $group->hours : 0;
////
////
////        $salary = $hoursWorked * $user->hour_price;
////
////        // Calculate the formatted diff_time in HH:mm
////        $diffTimeMinutes = $hoursWorked * 60;
////        $diffHours = intdiv($diffTimeMinutes, 60);
////        $diffMinutes = $diffTimeMinutes % 60;
////        $formattedDiffTime = sprintf('%02d:%02d', $diffHours, $diffMinutes);
//
////        if (Carbon::now()->format('Y-m') == $startOfLastMonth->format('Y-m')) {
////            $data = null;
////        } else {
//
//
//            $data = [
//                'current_date' => $currentDate->format('Y-m'),
//                'userDays' => $userDays,
//                'incentives0' => $incentives0,
//                'incentives1' => $incentives1,
//                'salary' => $user->salary,
//                'net_salary' => number_format($netSalary, 2),
//                'advances' => $advances,
//            ];
//
////        }
//
//
//        return $this->jsonData('تم الحصول على البيانات', $data);
//    }

    public function userMonths()
    {

        $obj = Auth::user();
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
            $netSalary = $userDays > 0 ? ($obj->salary + $additions + $incentives1 - $incentives0 - $advances) : 0;

            // Collect data for the current month
            $monthData[] = [
                'current_date' => $monthKey,
                'userDays' => $userDays,
                'incentives0' => $incentives0,
                'incentives1' => $incentives1,
                'netSalary' => number_format($netSalary, 2),
                'salary' => $obj->salary + $additions,
                'advances' => $advances,
                'additions' => $additions
            ];

            // Move to the next month
            $startDate->addMonth();
        }

        // The response now includes all the months' data, not just the latest month
        return response()->json([
            'status' => 200,
            'message' => 'تم الحصول على البيانات',
            'data' => $monthData // Return all the monthly data
        ]);
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

    public function orderAnAdvance($request)
    {
        $validation = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable',
        ], [
            'amount.required' => 'قيمه السلفه مطلوبه',
            'amount.numeric' => 'قيمه السلفه يجب ان تكون رقم',

        ]);

        //test

        if ($validation->fails()) {
            return $this->jsonData($validation->errors()->first(), null, 422);
        }


        $user = Auth()->user();


        $user_id = Auth()->user()->id;

        $order = Advance::create([
            'user_id' => $user_id,
            'amount' => $request->amount,
            'note' => $request->note ?? null,
            'date' => Carbon::now(),
            'approved' => 0,
        ]);

        $hrUser = User::whereNull('group_id')
            ->where('branch_id', $user->branch_id)
            ->where('status', '=', 1)
            ->get();

        $notificationData = [
            'title' => 'طلب سلفه',
            'body' => 'تم طلب سلفه جديده عن طريق  : ' . $user->name . ' بقيمه : ' . $request->amount . ' جنيه',
        ];

        foreach ($hrUser as $hr) {
            $this->sendFcm($notificationData, $hr->id);
        }

//        $this->sendFcm($notificationData, $hrUser->id);

        return $this->jsonData('تم ارسال طلبك وسوف يتم مراجعته', $order);
    }


    public function getMyAnAdvance()
    {


        $user_id = Auth()->user()->id;
        $advances = Advance::query()
            ->where('user_id', $user_id)
            ->latest()
            ->get()
            ->makeHidden(['updated_at']);

        foreach ($advances as $advance) {

            if ($advance->approved == 0) {


                $advance->approved = 'جديدة';
            } elseif ($advance->approved == 1) {
                $advance->approved = 'تم الموافقة';
            } else {

                $advance->approved = 'تم الرفض';
            }
        }


        return $this->jsonData('تم الحصول على السلف ', $advances);
    }


    public function editEmployeeProfile($request)
    {
        $user = Auth()->user();

        $name = $request->name ?? $user->name;
        //        $current_password = $request->password;
        $new_password = $request->new_password;
        $image = $request->image;

        if ($image) {
            if ($user->image && $user->image != 'avatar.png') {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $image->store('uploads/users', 'public');
        }

        //        if ($current_password && Hash::check($current_password, $user->password)) {

        if ($new_password) {
            $user->password = Hash::make($new_password);
        }

        $user->name = $name;
        $user->save();

        $user = $user->makeHidden(['password', 'remember_token', 'created_at', 'updated_at', 'branch_id', 'group_id']);
        $user->image = asset('storage/' . $user->image);


        return $this->jsonData('تم تعديل الملف الشخصي بنجاح', $user);

        //        }
        //        else {
        //            return $this->jsonData('كلمة المرور الحالية غير صحيحة', null, 401);
        //        }
    }

    public function contact_us($request)
    {
        $validation = Validator::make($request->all(), [
            'body' => 'required',
        ],
        [
            'body.required' => 'يجب تعبئة الحقل',
        ]
        );

        $user = Auth()->user();
        $contact_us=new ContactUs();
        $contact_us->user_id=$user->id;
        $contact_us->body=$request->body;
        $contact_us->save();

        $notificationData = [
            'title' => 'تواصل معنا',
            'body' => 'قام ' . $user->name . ' بارسال شكواه في  ' . now()->format('g:i A'),
        ];

        $hrUser = User::whereNull('group_id')
            ->where('status', '=', 1)
            ->get();

        foreach ($hrUser as $hr) {
            $this->sendFcm($notificationData, $hr->id);
        }

        return $this->jsonData('تم الارسال بنجاح', null);

    }


    public function myEmployeeProfile()
    {

        $user = Auth()->user();
        $selectedAttributes = $user->only(['name', 'image', 'registered_at']);

        $selectedAttributes['image'] = asset('storage/' . $user->image);
        $selectedAttributes['group'] = $user->group->name;
        $selectedAttributes['registered_at'] = Carbon::parse($user->registered_at)->format('d-m-Y');

        return $this->jsonData('تم تعديل الملف الشخصي بنجاح', $selectedAttributes);
    }


    public function myHolidays()
    {

        $user = Auth()->user();
        $holidays = $user->holidays()->orderBy('created_at', 'DESC')->get();
        foreach ($holidays as $holiday) {
            $holiday->from_day = Carbon::parse($holiday->from_day)->format('Y-m-d');
            $holiday->to_day = Carbon::parse($holiday->to_day)->format('Y-m-d');
            $holiday->holiday_type_id = $holiday->holidayType->name;
        }


        return $this->jsonData('تم تحميل العطلات بنجاح', $holidays);

    }

    public function holidaysType()
    {

        $holidaysType = HolidayType::select('id', 'name')->get();
        return $this->jsonData('تم تحميل  نواع العطلات بنجاح', $holidaysType);

    }

    public function orderHoliday($request)
    {
        $validator = Validator::make($request->all(), [
            'from_day' => 'required|date',
            'to_day' => 'required|date|after_or_equal:from_day',
            'holiday_type_id' => 'required|exists:holiday_types,id',
            'note' => 'nullable',
        ]);


        if ($validator->fails()) {

            return response()->json($validator->errors(), 422);
        }
        $form_day = Carbon::parse($request->from_day)->format('Y-m-d');
        $to_day = Carbon::parse($request->to_day)->format('Y-m-d');


        $user = Auth()->user();
        $holiday = new Holiday();
        $holiday->user_id = $user->id;
        $holiday->from_day = $form_day;
        $holiday->to_day = $to_day;
        $holiday->holiday_type_id = $request->holiday_type_id;
        $holiday->status = 0;
        $holiday->note = $request->note;
        $holiday->save();

        $diffInDays = Carbon::parse($request->from_day)->diffInDays(Carbon::parse($request->to_day)) + 1;
        $diffInDays = ($diffInDays === 0) ? 1 : $diffInDays;

//        return $diffInDays;


        $notificationData = [
            'title' => 'طلب اجازه',
            'body' => 'تم طلب الاجازه من قبل ' . $user->name . ' لمده ' . $diffInDays . ' يوم',
        ];

        $hrUser = User::whereNull('group_id')
            ->where('branch_id', $user->branch_id)
            ->where('status', '=', 1)
            ->get();

        foreach ($hrUser as $hr) {
            $this->sendFcm($notificationData, $hr->id);
        }

//        $this->sendFcm($notificationData, $hrUser->id);


        return $this->jsonData('تم طلب الاجازه في انتظار الموافقة', $holiday);


    }


    public function getNotification()
    {

        $user = Auth()->user();
        $notifications = $user->notifications()->latest()->get(['id', 'title', 'body', 'created_at']);

        return $this->jsonData('تم تحميل الاشعارات بنجاح', $notifications);
    }

    public function GetNotes()
    {

        $GetNotes=Instruction::select('id','title','description','created_at')
            ->orderBy('created_at', 'DESC')
            ->get();

        return $this->jsonData('تم تحميل الملاحظات بنجاح', $GetNotes);
    }


    public function homeHR($request)
    {
        $validator = Validator::make($request->all(), [
            'filter' => 'nullable|in:0,1,2',
        ], [
            'filter.in' => 'القيمة المدخلة غير صحيحة',
        ]);

        if ($validator->fails()) {
            return $this->jsonData('خطأ في البيانات', null, 422);
        }

        $hr = auth()->user();
        $from = request()->input('from');
        $to = request()->input('to');

        if (!$from && !$to) {
            $from = Carbon::today()->startOfDay(); // Start of today
            $to = Carbon::today()->endOfDay();     // End of today
        } else {
            request()->validate([
                'from' => 'nullable|date',
                'to' => 'nullable|date|after_or_equal:from',
            ]);
        }

        $filter = $request->filter;
        $registeredAt = Carbon::parse($hr->registered_at)->format('d-m-Y');

        $hrName = $hr->name;
        $hrImage = asset('storage/' . $hr->image);

        $attendances = Attendance::whereBetween('created_at', [$from, $to])->get();

        if (!$request->has('filter') || $request->filter == '' || $request->filter == null) {
            $employees = collect();

            // Absence
            $absenceEmployees = ObjModel::whereNotIn('id', $attendances->pluck('user_id'))
                ->where('group_id', '!=', null)
                ->get()
                ->each(function ($employee) {
                    $employee->filter = 'غياب';
                });

            // Present
            $presentEmployees = $attendances
                ->where('check_in', '!=', null)
                ->where('check_out', null)
                ->map(function ($employee) {
                    $employee->filter = 'حضور';
                    return $employee;
                });

            // Departure
            $departureEmployees = $attendances->where('check_in', '!=', null)
                ->where('check_out', '!=', null)
                ->map(function ($employee) {
                    $employee->filter = 'انصراف';
                    return $employee;
                });

            // Combine all filtered employees into one collection
            $employees = $employees->concat($absenceEmployees)
                ->concat($presentEmployees)
                ->concat($departureEmployees);
        } else {
            if ($filter == 0) { // غياب (Absence)
                $employees = ObjModel::whereNotIn('id', $attendances->pluck('user_id'))
                    ->where('group_id', '!=', null)
                    ->get();

                $employees->each(function ($employee) {
                    $employee->filter = 'غياب';
                });
            } elseif ($filter == 1) { // حضور (Present)
                $employees = $attendances->where('check_in', '!=', null)
                    ->where('check_out', null);

                $employees = $employees->map(function ($employee) {
                    $employee->filter = 'حضور';
                    return $employee;
                })->values(); // Ensure it returns as a plain array, not keyed by employee ID
            } else { // انصراف (Departure)
                $employees = $attendances->where('check_in', '!=', null)
                    ->where('check_out', '!=', null);

                $employees = $employees->map(function ($employee) {
                    $employee->filter = 'انصراف';
                    return $employee;
                })->values(); // Ensure it returns as a plain array, not keyed by employee ID
            }
        }

        // Format the employees data as an array of objects
        $filteredEmployees = $employees->map(function ($employee) {
            // Calculate the formatted diff_time in HH:mm
            $diffTimeMinutes = $employee->diff_time != null ? $employee->diff_time : 0;
            $diffHours = intdiv($diffTimeMinutes, 60);
            $diffMinutes = $diffTimeMinutes % 60;
            $formattedDiffTime = sprintf('%02d:%02d', $diffHours, $diffMinutes);

            return [
                'id' => $employee->id,
                'check_in' => $employee->check_in != null ? $employee->check_in : '00:00:00',
                'check_out' => $employee->check_out != null ? $employee->check_out : '00:00:00',
                'filter' => $employee->filter,
                'diff_time' => $formattedDiffTime, // Updated diff_time format
                'name' => $employee->user->name ?? $employee->name,
                'user_id' => isset($employee->user) ?  $employee->user->id :  $employee->id,
                'user_image' => asset('storage/' . (optional($employee->user)->image ?? $employee->image)),
                'image' => asset($employee->image),
            ];
        });
        if (request()->input('key')) {

            $key = request()->input('key');
            $users = User::where('name', 'like', '%' . $key . '%')->pluck('id');
            $filteredEmployees = $filteredEmployees->whereIn('user_id', $users)->values();

//            $filteredEmployees = $filteredEmployees->get()->values();


        }
        $sliders = Slider::where('active', 1)
            ->select('image', 'id')  // Only fetch 'image' and 'id'
            ->get()
            ->map(function ($slider) {
                // Prepend the base URL to the image path
                $slider->image = url('storage/' . $slider->image);
                return $slider;
            });

        $data = [
            'hrName' => $hrName,
            'hrImage' => $hrImage,
            'registeredAt' => $registeredAt,
            'employees' => $filteredEmployees, // Will be an array of objects
            'sliders'=>$sliders
        ];

        return $this->jsonData('تم تحميل البيانات بنجاح', $data);
    }

    public function getHolidaysForEmployee($request)
    {
        $getAdvances = Holiday::where('user_id', $request->user_id)
            ->latest()
            ->get();
        if ($getAdvances->isEmpty()) {
            return $this->jsonData('لا يوجد بيانات', null);
        }
//        $getAdvances->map(function ($advance) {
//            if ($advance->status == 1) {
//
//                $advance->status = 'مقبولة';
//            } elseif ($advance->approved == 2) {
//
//                $advance->status = 'مرفوضه';
//            }
//            $advance->holiday_type_id = $advance->holidayType->name;
//        });
        foreach ($getAdvances as $getAdvance) {
            $getAdvance->holiday_type_id = $getAdvance->holidayType->name;
        }

        $getAdvances->makeHidden(['created_at', 'updated_at']);

        return $this->jsonData('تم تحميل البيانات بنجاح', $getAdvances);
    }

    public function AcceptOrRejectTheHoliday($request)
    {

        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required|exists:holidays,id',
            'status' => 'required|in:1,2',
        ]);


        if ($validator->fails()) {
            return $this->jsonData('خطأ في البيانات', null, 422);
        }

        $holiday = Holiday::where('id', $request->holiday_id)->first();

        $holiday->status = $request->status;
        $holiday->save();


        // use firebase to send notification to employee

        if ($request->status == 2) {

            $fcmD = [
                'title' => 'الاجازه',
                'body' => 'نأسف تم رفض الاجازه ',
            ];
            $this->sendFcm($fcmD, $holiday->user_id);

            return $this->jsonData('نأسف تم رفض الاجازه ', null);
        }

        $fromDay = Carbon::parse($holiday->from_day);
        $toDay = Carbon::parse($holiday->to_day);

        $diffInDays = $fromDay->diffInDays($toDay) + 1;
        $diffInDays = ($diffInDays === 0) ? 1 : $diffInDays;

        $user = User::find($holiday->user_id);
        $user->holidays = $user->holidays - $diffInDays;
        $user->save();
        $fcmD = [
            'title' => 'الاجازه',
            'body' => 'تم الموافقه علي الاجازه بنجاح',
        ];
        $this->sendFcm($fcmD, $holiday->user_id);

        return $this->jsonData('تم الموافقه علي الاجازه بنجاح', null);
    }


    public function getDeduction($request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id',

            ],
            [
                'user_id.exists' => 'الرقم المدخل غير موجود',
            ]
        );
        if ($validator->fails()) {
            return $this->jsonData('خطأ في البيانات', null, 422);
        }

        $deduction = Incentive::where('user_id', $request->user_id)
            ->where('incentive', 0)->get();

        if ($deduction->isEmpty()) {
            return $this->jsonData('لا يوجد بيانات', null);
        }


        foreach ($deduction as $item) {

            $totalHours = $item->user->group->hours;
            $hours = floor($totalHours);
            $minutes = floor(($totalHours - $hours) * 60);
            $seconds = (($totalHours - $hours) * 3600) % 60;

            $item->originalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            $workTime = Attendance::query()
                ->where('user_id', $request->user_id)
                ->whereNotNull('check_out')
                ->where('date', $item->date)
                ->first();

            if ($workTime) {

                $totalMinutes = $workTime->diff_time;
                $hours = floor($totalMinutes / 60);
                $minutes = floor($totalMinutes % 60);
                $seconds = ($totalMinutes * 60) % 60;
                $item->WorkingTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            } else {

                $item->WorkingTime = '00:00:00';
            }
        }


        $deduction = $deduction->makeHidden(['created_at', 'updated_at', 'user', 'incentive']); //test hidden

        return $this->jsonData('تم تحميل البيانات بنجاح', $deduction);
    }

    public function testFcm($request)
    {
        $data = [
            'title' => $request->title,
            'body' => $request->body,
        ];
        $user_id = $request->user_id;

        return $this->jsonData('تم تحديث البيانات بنجاح', $this->sendFcm($data, $user_id));
    }

    public function storeFcm($request)
    {
        $user = User::find(auth()->user()->id);
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return $this->jsonData('تم تحديث البيانات بنجاح', null);
    }

    public function AttendanceAndDeparture($request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'from' => 'nullable',
                'to' => 'nullable',

            ],
            [
                'user_id.exists' => 'الرقم المدخل غير موجود',
            ]
        );
        if ($validator->fails()) {
            return $this->jsonData('خطأ في البيانات', null, 422);
        }
        $dayTranslations = [
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت'
        ];

        $user = User::find($request->user_id);
        $from = Carbon::parse($request->from)->format('Y-m-d') == null ? Carbon::now()->format('Y-m-d') : Carbon::parse($request->from)->format('Y-m-d');
        $to = Carbon::parse($request->to)->format('Y-m-d') == null ? Carbon::now()->format('Y-m-d') : Carbon::parse($request->to)->format('Y-m-d');

        $attendances = $user->attendances()->whereBetween('date', [$from, $to])->get();
        foreach ($attendances as $attendance) {

            $attendance->date = $dayTranslations[Carbon::parse($attendance->date)->format('l')];
            $attendance->image = asset($attendance->image);
            $attendance->check_out_image = $attendance->check_out_image !== null ? asset($attendance->check_out_image) : null;

            // Calculate hours and minutes
            $hours = floor($attendance->diff_time / 60);
            $minutes = $attendance->diff_time % 60;

            // Format as hh:mm
            $attendance->diff_time = sprintf('%02d:%02d', $hours, $minutes);;
        }

        return $this->jsonData('تم تحميل البيانات بنجاح', $attendances);

    }
}
