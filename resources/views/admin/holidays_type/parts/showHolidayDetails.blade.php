<div class="modal-body">
<div class="row">

    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white"><h2 class="mb-0 number-font">{{ $user->holidays  +  $count}}</h2>
                        <p class="text-white mb-0"> {{ trns('total_holidays') }}</p></div>
                    <div class="mr-auto">
                        <i class="fe fe-sun text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white"><h2 class="mb-0 number-font">{{ $user->holidays}}</h2>
                        <p class="text-white mb-0"> {{ trns('reset') }}</p></div>
                    <div class="mr-auto">
                        <i class="fe fe-sun text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card bg-primary-gradient img-card box-success-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white"><h2 class="mb-0 number-font">{{ $count}}</h2>
                        <p class="text-white mb-0"> {{ trns('count_of_holidays') }}</p></div>
                    <div class="mr-auto">
                        <i class="fe fe-sun text-white fs-30 ml-2 mt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <td>{{ trns('name') }}</td>
                <td>{{ trns('holiday_type') }}</td>
                <td>{{ trns('count_of_days') }}</td>
                <td>{{ trns('note') }}</td>
                <td>{{ trns('status') }}</td>
            </tr>
            </thead>
            <tbody>
            @foreach($objs as $obj)
                <tr>
                    <td class="text-capitalize">{{ $obj->user->name }}</td>
                    <td class="text-capitalize">{{ $obj->holidayType->name }}</td>
                @php
                    $from = \Carbon\Carbon::parse($obj->from_day);
                    $to = \Carbon\Carbon::parse($obj->to_day);

                    if ($from->gt($to)) {
                        // Optional: Swap dates if from_day is after to_day
                        $temp = $from;
                        $from = $to;
                        $to = $temp;
                    }

                    $diff = $to->diffInDays($from) + 1;
                @endphp

                    <td class="text-capitalize">{{ $diff }}</td>

                <td class="text-capitalize">{{ $obj->note }}</td>
                    <td class="text-capitalize">
                        @if($obj->status == 0)
                            <span class="badge bg-success">{{ trans('pending') }}</span>
                        @elseif ($obj->status == 1)
                            <span class="badge bg-primary">{{ trans('accept') }}</span>
                        @else
                            <span class="badge bg-danger">{{ trans('reject') }}</span>
                        @endif
                    </td>



                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>


<script>
    $('.dropify').dropify();
</script>
