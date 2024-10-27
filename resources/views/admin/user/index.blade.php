@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') }} | {{ $pageTitle }}
@endsection
@section('page_name')
    {{ $pageTitle }}
@endsection
@section('content')
<style>
    /* Style for the dropdown container */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

</style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ $pageTitle }} {{ config()->get('app.name') }}</h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> {{ trns('add new') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
{{--                                <th class="min-w-25px">#</th>--}}
                                <th class="min-w-50px">{{ trns('created_at') }}</th>
                                <th class="min-w-50px">{{ trns('employee_name') }}</th>
                                <th class="min-w-50px">{{ trns('phone') }}</th>
                                <th class="min-w-50px">{{  trns('group_name')}}</th>
                                <th class="min-w-50px">{{ trns('salary') }}</th>
                                <th class="min-w-50px">{{  trns('employee_holidays')}}</th>
{{--                                <th class="min-w-50px">{{  trns('status')}}</th>--}}
                                <th class="min-w-125px">{{ trns('image') }}</th>

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
                        <p>{{ trns('are_you_sure_you_want_to_delete_this_obj') }} <span id="title"
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
                        <h5 class="modal-title" id="example-Modal3">{{ trns('object_details') }}</h5>
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
            {data: 'created_at', name: 'created_at'},
            {data: 'name', name: 'name'},
            {data: 'phone', name: 'phone'},
            {data: 'group_id', name: 'group_id'},
            {data: 'salary', name: 'salary'},
            {data: 'holidays', name: 'holidays'},
            // {data: 'status', name: 'status'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{ route($route . '.index') }}', columns);
        // Delete Using Ajax
        deleteScript('{{ route($route . '.destroy', ':id') }}');
        // Add Using Ajax
        showAddModal('{{ route($route . '.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();

        function showIncentiveModal(routeOfEdit) {
            $(document).on('click', '.incentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        function showGetIncentiveModal(routeOfEdit) {
            $(document).on('click', '.getIncentiveBtn', function() {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function() {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        showIncentiveModal('{{ route($route . '.incentives', ':id') }}');
        {{--showGetIncentiveModal('{{ route($route . '.getIncentives', ':id') }}');--}}
    </script>
@endsection
