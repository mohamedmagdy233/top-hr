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

                        <!-- Add new button -->
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> {{ trns('add') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-50px">{{ trns('image') }}</th>
                                <th class="min-w-50px">{{ trns('status') }}</th>
                                <th class="min-w-50px rounded-end">{{ trns('actions') }}</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <p>{{ trns('are_you_sure_you_want_to_delete_this_obj') }} <span id="title" class="text-danger"></span>?</p>
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
        var columns=  [
            {data: 'image', name: 'image'},
            {data: 'active', name: 'active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];


        // Show data using AJAX
        showData('{{ route($route.'.index') }}', columns);

        // Delete using AJAX
        deleteScript('{{ route($route.'.destroy', ':id') }}');

        // Add using AJAX
        showAddModal('{{ route($route.'.create') }}');
        addScript();

        // Edit using AJAX
        showEditModal('{{ route($route.'.edit', ':id') }}');
        editScript();
    </script>

    <script>
        $(document).on('click', '.statusBtn', function() {
            var id = $(this).data('id');
            var val = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('changeStatus') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                },
                success: function(data) {
                    if (data.status === 200) {
                        if (val !== 0) {
                            toastr.success('Success', "{{ trns('active') }}");
                        } else {
                            toastr.warning('Success', "{{ trns('inactive') }}");}
                    } else {
                        toastr.error('Error', "{{trns('something_went_wrong')}}");
                    }
                },
                error: function() {
                    toastr.error('Error', "{{trns('something_went_wrong')}}");
                }
            });
        });
    </script>
@endsection
