@extends('admin/layouts/master')
@section('title')
    {{ config()->get('app.name') }} | {{ trns('dashboard') }}
@endsection
@section('page_name')
    {{ trns('dashboard') }}
@endsection
@section('content')

    <div class="row">


        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2
                                class="mb-0 number-font">{{



                $x2 = \App\Models\Revenue::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('value') ?? 0
     }}</h2>
                            <p class="text-white mb-0"> {{ trns('total_revenue') }}</p></div>
                        <div class="mr-auto">
                            <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2
                                class="mb-0 number-font">{{ $x2=\App\Models\Treasury::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('value') ?? 0 }}</h2>
                            <p class="text-white mb-0"> {{ trns('Treasury') }}</p></div>
                        <div class="mr-auto">
                            <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card bg-primary-gradient img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2
                                class="mb-0 number-font">{{ $x2 = \App\Models\Expense::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('value') ?? 0 }}</h2>
                            <p class="text-white mb-0"> {{ trns('total_expense') }}</p></div>
                        <div class="mr-auto">
                            <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>

   <div class="card p-5 mt-5">
       <div class="row">
           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2
                                   class="mb-0 number-font">{{$x5= \App\Models\User::where('group_id',null)->count() }}</h2>
                               <p class="text-white mb-0"> {{ trns('HR_count') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fe-users text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>


           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2
                                   class="mb-0 number-font">{{$x5= \App\Models\User::where('group_id','!=',null)->count() }}</h2>
                               <p class="text-white mb-0"> {{ trns('employees_count') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fe-users text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2
                                   class="mb-0 number-font">{{$x1= \App\Models\User::sum('salary') }}</h2>
                               <p class="text-white mb-0"> {{ trns('total_employee_salaries') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>




           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2
                                   class="mb-0 number-font">{{ $x2=\App\Models\Incentive::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->where('incentive',0)->sum('value') }}</h2>
                               <p class="text-white mb-0"> {{ trns('total_deduction') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>


           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2
                                   class="mb-0 number-font">{{$x3= \App\Models\Incentive::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->where('incentive',1)->sum('value') }}</h2>
                               <p class="text-white mb-0"> {{ trns('total_incentives') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
               <div class="card bg-info-gradient img-card box-success-shadow">
                   <div class="card-body">
                       <div class="d-flex">
                           <div class="text-white"><h2 class="mb-0 number-font">{{$x4= \App\Models\Advance::whereBetween('created_at', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->count() }}</h2>
                               <p class="text-white mb-0"> {{ trns('advance_count') }}</p></div>
                           <div class="mr-auto">
                               <i class="fe fa-jedi-order text-white fs-30 ml-2 mt-2"></i>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

@endsection
@section('js')


@endsection

