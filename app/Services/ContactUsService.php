<?php

namespace App\Services;

use App\Models\ContactUs as ObjModel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ContactUsService extends BaseService
{
    protected string $folder = 'admin/contact-us';
    protected string $route = 'contact-us';

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

                    return $obj->user->name;
                })


                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'route' => $this->route,
                'pageTitle' => trns('contact us'),
            ]);
        }
    }




}
