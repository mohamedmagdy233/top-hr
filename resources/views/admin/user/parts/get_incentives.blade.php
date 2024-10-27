@extends('admin.layouts.master')

@section('title')
    {{ config('app.name') }} | {{ $pageTitle }}
@endsection

@section('page_name')
    {{ $pageTitle }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="">
                        <a href="{{ url()->previous() }}" class="btn btn-success text-white addBtn">
                            <span>
                                <i class="fe fe-arrow-{{ lang() == 'ar' ? 'right' : 'left'}}"></i>
                            </span>
                        </a>
                    </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>{{ trns('type') }}</td>
                                    <td>{{ trns('date') }}</td>
                                    <td>{{ trns('value') }}</td>
                                    <td>{{ trns('reason') }}</td>
                                    <td>{{ trns('actions') }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($IncentivesWith1 as $opration)
                                    <tr>
                                        <td>{{  trns('incentive') }}</td>
                                        <td>{{ $opration->date }}</td>
                                        <td>{{ $opration->value }}</td>
                                        <td>{{ $opration->reason }}</td>
                                        <td>
                                            <!-- Delete button -->
                                            <a class="btn btn-danger btn-sm" data-id="{{ $opration->id }}"
                                                data-toggle="modal" data-target="#deleteModal">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                                        aria-labelledby="deleteModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">
                                                        {{ trns('delete_operation') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ trns('are_you_sure_you_want_to_delete_this_operation') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ trns('cancel') }}</button>
                                                    <form id="delete-form" method="post"
                                                        action="{{ route('incentive.destroy', $opration->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="submit" class="btn btn-danger"
                                                            value=" {{ trns('delete') }}" />
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <td colspan="4">
                                        <p style="text-align: center;">{{ trns('no_data') }}</p>

                                    </td>
                        @endforelse
                            </tbody>
                        </table>

                        <!-- Delete Modal -->
                    </div>
                </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>{{ trns('type') }}</td>
                                    <td>{{ trns('date') }}</td>
                                    <td>{{ trns('value') }}</td>
                                    <td>{{ trns('reason') }}</td>
                                    <td>{{ trns('actions') }}</td>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($IncentivesWith0 as $opration)
                                    <tr>
                                        <td>{{  trns('deduction') }}</td>
                                        <td>{{ $opration->date }}</td>
                                        <td>{{ $opration->value }}</td>
                                        <td>{{ $opration->reason }}</td>
                                        <td>
                                            <!-- Delete button -->
                                            <a class="btn btn-danger btn-sm" data-id="{{ $opration->id }}"
                                               data-toggle="modal" data-target="#deleteModal">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                                         aria-labelledby="deleteModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">
                                                        {{ trns('delete_operation') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ trns('are_you_sure_you_want_to_delete_this_operation') }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ trns('cancel') }}</button>
                                                    <form id="delete-form" method="post"
                                                          action="{{ route('incentive.destroy', $opration->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="submit" class="btn btn-danger"
                                                               value=" {{ trns('delete') }}" />
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <td>{{ trns('no_data') }}</td>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Delete Modal -->
                        </div>
                    </div>

                </div>
        </div>
    </div>
@endsection

<!-- Master Layout File -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

{{--  /  <script> --}}
{{--        $(document).ready(function() { --}}
{{--            // Initialize any plugins or JavaScript functionality --}}
{{--            $('.dropify').dropify(); --}}

{{--            // Handle delete button click --}}
{{--            $('.btn-danger').on('click', function(e) { --}}
{{--                e.preventDefault(); --}}
{{--                var operationId = $(this).data('id'); --}}
{{--                $('#delete-form').attr('action', '/incentive/' + operationId); --}}
{{--                $('#deleteModal').modal('show'); --}}
{{--            }); --}}
{{--        }); --}}
{{--    </script> --}}
