@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ trns('employee_details') }}
@endsection

@section('page_name')
    {{ trns('employee_details') }}
@endsection
@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printable,
            .printable * {
                visibility: visible;
            }

            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .print-btn {
                visibility: hidden;
            }
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <a href="{{ route('users.index') }}" class="btn btn-success text-white addBtn">
                                <span>
                                    <i class="fe fe-arrow-{{ lang() == 'ar' ? 'right' : 'left' }}"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-6">
                            <div class="wideget-user text-center">
                                <div class="wideget-user-desc">
                                    <div class="wideget-user-img">
                                        <img class="" src="{{ asset('storage/'.$obj->image ?? 'assets/uploads/avatar.png') }}" alt="img">
                                    </div>
                                    <div class="user-wrap">
                                        <h4 class="mb-1 text-capitalize">{{ $obj->name }}</h4>
                                        <h6 class="text-muted mb-4">
                                            @if ($obj->attendances->count() > 0)
                                                {{ trns('last_seen') }} :
                                                {{ Carbon\Carbon::parse($obj->attendances->first()->check_in)->diffForHumans() }}
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 d-flex justify-content-end">
                            <div>
                                <button type="button" data-id="{{ $obj->id }}" class="dropdown-item btn editBtn">
                                    <i class="ml-2 fa fa-edit text-primary"></i>
                                    {{ trns('edit') }}
                                </button>
                                <button type="button" data-id="{{ $obj->id }}"
                                        class="dropdown-item btn incentiveBtn">
                                    <i class="ml-2 fas fa-money-bill text-info"></i>
                                    {{ trns('incentive_/_deduction') }}
                                </button>
                                {{--                                <button--}}
                                {{--                                    onclick="window.location.href='{{ route('users.getIncentives', $obj->id) }}'"--}}
                                {{--                                    class="dropdown-item btn">--}}
                                {{--                                    <i class="fas fa-wave-square text-info"></i>--}}
                                {{--                                    {{ trns('oprations') }}--}}
                                {{--                                </button>--}}


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-1" class="tab-action "
                                                data-toggle="tab">{{ trns('Personal Information') }}</a>

                                </li>

                                <li class=""><a href="#tab-2" class="tab-action"
                                                data-toggle="tab">الحضور والانصراف التفصيلي</a>
                                </li>

                                <li class=""><a href="#tab-5" class="tab-action active show" data-toggle="tab">
                                الحضور والانصراف الاجمالي
                                    </a>
                                </li>
                                <li class=""><a href="#tab-3" class="tab-action"
                                                data-toggle="tab">{{ trns('payroll_record') }}</a></li>
                                <li class=""><a href="#tab-4" class="tab-action"
                                                data-toggle="tab">{{ trns('advances') }}</a></li>


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane  " id="tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <div>
                                        <h5><strong>{{ trns('Personal Information') }}</strong></h5>
                                    </div>
                                    <button onclick="printDiv('tab-1')"
                                            class="btn btn-primary print-btn">{{ trns('print') }}</button>

                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>{{ trns('group_name') }} :</strong>
                                                {{ $obj->group ? $obj->group->name : trns('human_resource') }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>{{ trns('Name') }} :</strong>
                                                {{ $obj->name }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('phone') }} :</strong> {{ $obj->phone }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('employee_code') }} :</strong> {{ $obj->code }}
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('employee_price_hour') }} :</strong>
                                                {{ $obj->hour_price }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('employee_status') }} :</strong>
                                                {{ $obj->status ? trns('active') : trns('inactive') }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>{{ trns('created_at_date') }} :</strong>
                                                {{ $obj->created_at->diffForHumans() }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-2">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('Attendance and departure') }}</strong></h5>
                                    <button onclick="printDiv('tab-2')"
                                            class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>
                                <div class="table-responsive">
                                    @if ($obj->attendances()->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('date') }}</td>

                                                <td class="text-center">
                                                    {{ trns('Attendance') }}
                                                </td>

                                                <td class="text-center">
                                                    {{ trns('departure') }}
                                                </td>

                                                <td class="text-center">
                                                    {{ trns('note') }}
                                                </td>


                                                <td class="text-center">{{ trns('work_time') }}</td>
                                                <td class="text-center">
                                                    {{ trns('image_check_in') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ trns('image_check_out') }}
                                                </td>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->attendances()->orderBy('date', 'asc')->get() as $attendance)

                                                <tr>
                                                    <td class="text-capitalize">
                                                        {{ Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}
                                                    </td>

                                                    <td class="text-capitalize text-center">

                                                        {{ Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}

                                                    </td>


                                                    <td class="text-capitalize text-center">

                                                        {{ Carbon\Carbon::parse($attendance->check_out)->format('h:i A') }}
                                                    </td>

                                                    <td class="text-capitalize text-center">
                                                        {{ $attendance->note==null ? '' : $attendance->note }}
                                                    </td>

                                                    <td class="text-capitalize text-center">
                                                        {{ $attendance->workHour() }}
                                                    </td>



                                                    <td class="text-center">
                                                        @if ($attendance->image)
                                                            <img alt="image" onclick="window.open(this.src);"
                                                                 class="avatar avatar-md rounded-circle"
                                                                 src="{{ asset($attendance->image) }}">
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($attendance->check_out_image)
                                                            <img alt="image" onclick="window.open(this.src);"
                                                                 class="avatar avatar-md rounded-circle"
                                                                 src="{{ asset($attendance->check_out_image) }}">
                                                        @endif
                                                    </td>


                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane active show" id="tab-5">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('Attendance_and_departure_total') }}</strong></h5>
                                    <button onclick="printDiv('tab-2')" class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>

                                <!-- Month filter form -->
                                <form method="GET" action="{{ route('users.show', $obj->id) }}">
                                    <div class="form-group">
                                        <label for="month">اختر شهر</label>
                                        <select name="month" id="month" class="form-control">
                                            <option value="all">{{ trns('All') }}</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ Carbon\Carbon::create(null, $m)->format('Y-m') }}"
                                                    {{ request('month') == Carbon\Carbon::create(null, $m)->format('Y-m') ? 'selected' : '' }}>
                                                    {{ Carbon\Carbon::create(null, $m)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">ابحث</button>
                                </form>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>{{ trns('date') }}</td>
                                            <td class="text-center">{{ trns('first_Attendance') }}</td>
                                            <td class="text-center">{{ trns('last_departure') }}</td>
                                            <td class="text-center">{{ trns('work_time') }}</td>
                                            <td class="text-center">{{ trns('image_check_in') }}</td>
                                            <td class="text-center">{{ trns('image_check_out') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($dailyRecords as $date => $total)
                                            <tr>
                                                <td class="text-capitalize">
                                                    {{ Carbon\Carbon::parse($date)->format('d-m-Y') }}
                                                </td>
                                                <td class="text-center">
                                                    @if ($total['firstCheckIn'])
                                                        {{ Carbon\Carbon::parse($total['firstCheckIn']->check_in)->format('h:i A') }}
                                                    @else
                                                        {{ trns('no_data') }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($total['lastCheckOut'])
                                                        {{ Carbon\Carbon::parse($total['lastCheckOut']->check_out)->format('h:i A') }}
                                                    @else
                                                        {{ trns('no_data') }}
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $total['diff_time'] }}</td>
                                                <td class="text-center">
                                                    @if ($total['firstCheckIn'] && $total['firstCheckIn']->image)
                                                        <img alt="image" onclick="window.open(this.src);" class="avatar avatar-md rounded-circle" src="{{ asset($total['firstCheckIn']->image) }}">
                                                    @else

                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($total['lastCheckOut'] && $total['lastCheckOut']->check_out_image)
                                                        <img alt="image" onclick="window.open(this.src);" class="avatar avatar-md rounded-circle" src="{{ asset($total['lastCheckOut']->check_out_image) }}">
                                                    @else

                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="tab-3">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('payroll_record') }}</strong></h5>
                                    <button onclick="printDiv('tab-3')"
                                            class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <td>{{ trns('month') }}</td>
                                            <td>{{ trns('work_days') }}</td>
                                            <td>{{ trns('additions') }}</td>
                                            <td>{{ trns('basic_salary') }}</td>
                                            <td>{{ trns('incentive') }}</td>
                                            <td>{{ trns('advances') }}</td>
                                            <td>{{ trns('deduction') }}</td>
                                            <td>{{ trns('net_salary') }}</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($monthData as $data)
                                            <tr>
                                                    <?php
                                                    $formattedDate = Carbon\Carbon::parse($data['month'])->format('Y-m');
                                                    ?>
                                                <td class="text-capitalize text-center">
                                                    <a
                                                        href="{{ route('users.getIncentives', ['user' => $obj->id, 'date' => $formattedDate]) }}"
                                                        class="dropdown-item btn">
                                                                <span class="badge bg-success">
                                                                    {{ Carbon\Carbon::parse($data['month'])->format('F Y') }}
                                                                </span>
                                                    </a>
                                                </td>


                                                <td class="text-capitalize text-center">{{ $data['days_worked'] }}</td>

                                                <td class="text-capitalize text-center">{{$data['additions']}}</td>
                                                <td class="text-capitalize text-center">{{$obj->salary}}</td>
                                                <td class="text-capitalize  text-center">{{ $data['incentivesWith1'] }}</td>
                                                <td class="text-capitalize text-center">{{ $data['advances'] }}</td>
                                                <td class="text-capitalize text-center">{{ $data['incentivesWith0'] }}</td>
                                                <td class="text-capitalize text-center">{{ $data['netSalary'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-pane" id="tab-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading d-flex justify-content-between">
                                    <h5><strong>{{ trns('advances') }}</strong></h5>
                                    <button onclick="printDiv('tab-4')"
                                            class="btn btn-primary print-btn">{{ trns('print') }}</button>
                                </div>
                                <div class="table-responsive">
                                    @if ($obj->advances->count() > 0)
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <td>{{ trns('date') }}</td>
                                                <td>{{ trns('advance') }}</td>
                                                <td>{{ trns('notes') }}</td>
                                                <td>{{ trns('status') }}</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($obj->advances as $advance)
                                                <tr>
                                                    <td class="text-capitalize">{{ $advance->date }}</td>
                                                    <td class="text-capitalize">{{ $advance->amount }}</td>
                                                    <td class="text-capitalize">{{ $advance->note }}</td>
                                                    <td class="text-capitalize">
                                                        @if ($advance->approved == 1)
                                                            <span
                                                                class="badge bg-success">{{ trns('was_approved') }}</span>
                                                        @elseif ($advance->approved == 2)
                                                            <span
                                                                class="badge bg-danger">{{ trns('rejected') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning">{{ trns('pending') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <h4>{{ trns('no_data') }} </h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>


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

    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $('.tab-action').on('click', function (e) {
            $('.tab-action').removeClass('active show');
            $('.tab-pane').removeClass('active show');

            $(this).addClass('active show');
            let href = $(this).attr('href');
            $(`${href}`).addClass('active show');
        });


        function printDiv(divId) {
            var divContent = document.getElementById(divId).innerHTML;
            var printWindow = window.open('', '_blank', 'height=1200,width=1200');

            // Start writing the HTML structure
            printWindow.document.write('<html><head><title>Print</title>');

            // Copy all stylesheets from the original document to the new window
            Array.prototype.forEach.call(document.querySelectorAll("link[rel='stylesheet'], style"), function (link) {
                printWindow.document.write(link.outerHTML);
            });

            // Write internal styles specifically for printing
            printWindow.document.write(
                '<style>@media print { body * { visibility: hidden; } .printable, .printable * { visibility: visible; } .printable { position: absolute; left: 0; top: 0; width: 100%; }} .print-btn{ display: none; }</style>'
            );

            // Close the head tag and start the body
            printWindow.document.write('</head><body>');

            // Add the content to be printed
            printWindow.document.write('<div class="printable">' + divContent + '</div>');

            // Close the HTML structure
            printWindow.document.write('</body></html>');

            // Close the document to complete the writing process
            printWindow.document.close();

            // Ensure the styles and scripts are loaded before printing
            printWindow.onload = function () {
                // Delay printing to ensure styles are applied
                setTimeout(function () {
                    printWindow.focus(); // Focus the new window
                    printWindow.print(); // Trigger the print dialog
                    printWindow.close(); // Close the new window after printing
                }, 250); // Adjust delay as necessary
            };
        }


        // Add Using Ajax
        showEditModal('{{ route($route . '.edit', ':id') }}');
        editScript();


        function showIncentiveModal(routeOfEdit) {
            $(document).on('click', '.incentiveBtn', function () {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function () {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        function showGetIncentiveModal(routeOfEdit) {
            $(document).on('click', '.getIncentiveBtn', function () {
                var id = $(this).data('id')
                var url = routeOfEdit;
                url = url.replace(':id', id)
                $('#modal-body').html(loader)
                $('#editOrCreate').modal('show')

                setTimeout(function () {
                    $('#modal-body').load(url)
                }, 500)
            })
        }

        showIncentiveModal('{{ route($route . '.incentives', ':id') }}');
        {{--showGetIncentiveModal('{{ route($route . '.getIncentives', ':id') }}');--}}
    </script>
@endsection
