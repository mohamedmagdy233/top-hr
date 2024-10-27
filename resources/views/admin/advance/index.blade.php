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
                    <div class="">
{{--                        <button class="btn btn-secondary btn-icon text-white addBtn">--}}
{{--									<span>--}}
{{--					                  					<i class="fe fe-plus"></i>--}}
{{--									</span> {{ trns('add new') }}--}}
{{--                        </button>--}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">{{ trns('employee_name') }}</th>
                                <th class="min-w-50px">{{  trns('group_name')}}</th>
                                <th class="min-w-50px">{{  trns('advance')}}</th>
                                <th class="min-w-50px">{{  trns('date_of_advance')}}</th>
                                <th class="min-w-50px">{{  trns('note')}}</th>
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
            {data: 'id', name: 'id'},
            {data: 'user_id', name: 'user_id'},
            {data: 'group_id', name: 'group_id'},
            {data: 'amount', name: 'amount'},
            {data: 'date', name: 'date'},
            {data: 'note', name: 'note'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route($route.'.index')}}', columns);
        // Delete Using Ajax
        deleteScript('{{route($route.'.destroy',':id')}}');
        // Add Using Ajax
        showAddModal('{{route($route.'.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route($route.'.edit',':id')}}');
        editScript();
    </script>

    <script>
        var audio = new Audio("{{ asset('assets/uploads/success.mp3') }}");
        $(document).ready(function() {
            // Event listener for 'statusBtn' buttons
            $(document).on('click', '.statusBtn', function() {
                let status = $(this).data('status');
                let id = $(this).data('id');

                // Perform AJAX request to update the status
                $.ajax({
                    url: "{{ route($route.'.updateStatus') }}", // Specify your server endpoint here
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        audio.play();
                        if (data.status === 200) {
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection


