@extends('admin/layouts/master')

@section('title')
    {{ config()->get('app.name') ?? ''}} | {{ trns('settings') }}
@endsection
@section('page_name')
    الاعدادات
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ trns('settings') }} {{ config()->get('app.name') ?? ''}}</h3>
                </div>
                <div class="card-body">
                    <form id="updateForm" method="POST" enctype="multipart/form-data"
                          action="{{route('settingUpdate',1)}}">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="logo" class="form-control-label">{{ trns('logo') }}</label>
                                    <input type="file" id="testDrop" class="dropify" name="logo"
                                           data-default-file="{{ isset($setting) ? getFile($setting->logo) : getFile(null)  }}"/>
                                </div>
                                <div class="col-6">
                                    <label for="favicon" class="form-control-label">{{ trns('favicon') }}</label>
                                    <input type="file" id="testDrop" class="dropify" name="favicon"
                                           data-default-file="{{ isset($setting) ? getFile($setting->favicon) : getFile(null) }}"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">

                                    <label for="title_ar" class="form-control-label">{{ trns('title_arabic') }}</label>
                                    <input type="text" class="form-control" name="title_ar" id="title_ar"
                                           value="{{isset($setting) ? $setting->title_ar : ''}}">
                                </div>
                                <div class="col-6">
                                    <label for="title_en" class="form-control-label">{{ trns('title_english') }}</label>
                                    <input type="text" class="form-control" name="title_en" id="title_en"
                                           value="{{isset($setting) ? $setting->title_en : ''}}">
                                </div>

                                <div class="col-12">
                                    <label for="alert" class="form-control-label">تعليمات</label>
                                    <textarea type="text" class="form-control" name="alert" id="alert">
                                         {{isset($setting) ? $setting->alert : ''}}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    id="updateButton">{{ trns('update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')

    <script>
        editScript();
    </script>
@endsection


