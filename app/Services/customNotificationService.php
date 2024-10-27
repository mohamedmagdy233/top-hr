<?php

namespace App\Services;

use App\Models\CustomNotification as ObjModel;
use App\Models\User;
use App\Traits\FirebaseNotification;
use Yajra\DataTables\DataTables;

class customNotificationService extends BaseService
{
    use FirebaseNotification;

    protected string $folder = 'admin/customNotification';
    protected string $route = 'customNotification';

    public function __construct(ObjModel $model)
    {
        parent::__construct($model);
    }

    public function index( $request)
    {
        if ($request->ajax()) {
            $obj = $this->getDataTable();
            return DataTables::of($obj)
                ->addColumn('action', function ($obj) {
                    $buttons = '';

                    $buttons .= '
                    <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
                        data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
                    return $buttons;
                })->editColumn('created_at', function ($obj) {

                    return $obj->created_at->format('Y-m-d');
                })->editColumn('user_id', function ($obj) {

                    return $obj->user_id==null ? trns('all') : $obj->user->name;
                })


                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('custom_notification'),
            ]);
        }
    }

    public function create()
    {
        return view($this->folder . '/parts/create', [
            'route' => route($this->route . '.store'),
            'users' => User::whereNotNull('group_id')->where('status', 1)->get()
        ]);

    }

    public function store($data): \Illuminate\Http\JsonResponse
    {
        $model = $this->createData($data);
        if ($model) {

            $notificationData = [
                'title' => $data['title'],
                'body' => $data['body'],
            ];

            if ($data['user_id']  ==null) {

                $users = User::where('status', 1)
                    ->get();




                foreach ($users as $user) {
                    $this->sendFcm($notificationData, $user->id);
                }

            }else{



                $this->sendFcm($notificationData, $data['user_id']);

            }

            return response()->json(['status' => 200]);
        } else {
            return response()->json(['status' => 405]);
        }
    }






}
