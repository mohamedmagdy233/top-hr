@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | {{ $pageTitle }}
@endsection
@section('page_name')
    {{ $pageTitle }}
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ $pageTitle }} {{ config()->get('app.name') }}</h3>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
{{--                                <th class="min-w-25px">#</th>--}}
                                <th class="min-w-50px">{{ trns('name') }}</th>
{{--                                <th class="min-w-50px">{{ trns('holiday_type') }}</th>--}}
{{--                                <th class="min-w-50px">{{ trns('from_day') }}</th>--}}
{{--                                <th class="min-w-50px">{{ trns('to_day') }}</th>--}}
{{--                                <th class="min-w-50px">{{ trns('note') }}</th>--}}
{{--                                <th class="min-w-50px">{{ trns('status') }}</th>--}}
                                <th class="min-w-50px rounded-end">{{ trns('actions') }}</th>

                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ trns('delete') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>{{  trns('are_you_sure_you_want_to_delete_this_obj')}} <span id="title"
                                                                                        class="text-danger"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" id="dismiss_delete_modal">
                            {{ trns('close') }}
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">{{ trns('delete') }} !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">{{  trns('object_details')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [
            // {data: 'id', name: 'id'},

            {data: 'name', name: 'name'},
            // {data: 'holiday_type_id', name: 'holiday_type_id'},
            // {data: 'from_day', name: 'from_day'},
            // {data: 'to_day', name: 'to_day'},
            // {data: 'note', name: 'note'},
            // {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]
        showData('{{route('showHoliday')}}', columns);

        editScript();
        showEditModal('{{route('showHolidayDetails',':id')}}');
        editScript();
    </script>
@endsection


