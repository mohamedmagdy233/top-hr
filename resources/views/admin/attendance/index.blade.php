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
                <div class="card-header justify-content-center">
                    <h3 class="card-title card-date"></h3>
                </div>
            </div>
        </div>


        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header text-white bg-success">
                    <h3 class="card-title">{{ trns('Attendance') }}</h3>
                </div>
                <div class="card-body">
                    @if($checkIn->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trns('employee_name') }}</th>
                                <th>{{ trns('Attendance') }}</th>
                                <th>{{ trns('note') }}</th>
                                <th>{{ trns('work_time') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($checkIn as $in)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $in->user->name }}</td>
                                    <td>{{ $in->check_in }}</td>
                                    <td>{{ $in->note }}</td>
                                    <td>{{ $in->diff_time }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3>{{ trns('no_attendance_data') }}</h3>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header text-white bg-danger">
                    <h3 class="card-title"> {{ trns('departure') }}</h3>
                </div>
                <div class="card-body">
                    @if($checkOut->count() > 0)
                        <table class="table table-bordered">
                            <thead>
                            <tr>

{{--                                <th>{{ trns('branch_name') }}</th>--}}
                                <th>#</th>
                                <th>{{ trns('employee_name') }}</th>
                                <th>{{ trns('Attendance') }}</th>
                                <th>{{ trns('departure') }}</th>
                                <th>{{ trns('note') }}</th>
                                <th>{{ trns('work_time') }}</th>
                                <th>{{ trns('is_force_check_out') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($checkOut as $out)
                                <tr>
{{--                                    <td>{{ $out->user->branch->name }}</td>--}}
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $out->user->name }}</td>
                                    <td>{{ $out->check_in }}</td>
                                    <td>{{ $out->check_out }}</td>
                                    <td>{{ $out->note }}</td>
                                    <td>{{ $out->workHour() }}</td>
                                    <td>{{ $out->force_checkout == 1 ? 'تم الخروج اجباريا' : '___'  }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h3>{{ trns('no_departure_data') }}</h3>
                    @endif
                </div>
            </div>
        </div>

    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        $(document).ready(function () {
            function updateDateTime() {
                var now = new Date();

                // Format the date and time as required
                var date = now.getDate() + '/' + (now.getMonth() + 1) + '/' + now.getFullYear();
                var day = now.toLocaleString('en-us', {weekday: 'long'});
                var time = now.toLocaleTimeString('en-us', {hour: '2-digit', minute: '2-digit', hour12: true});

                // Update the card title with the new date, day, and time
                $('.card-date').text(date + ' | ' + day + ' | ' + time);
            }

            // Initial call to set the date, day, and time
            updateDateTime();

            // Set the function to be called every minute (60000 milliseconds)
            setInterval(updateDateTime, 10000);
        });
    </script>

    <script>

    </script>
@endsection


