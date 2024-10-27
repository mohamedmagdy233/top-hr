<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">{{ trns('branch_name') }}</label>
                    <select class="form-control" name="branch_id">
                        <option disabled>{{ trns('select_branch') }}</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="group_id" class="form-control-label">{{ trns('group') }}</label>
                    <select class="form-control" name="group_id">
                        <option disabled>{{ trns('select_group') }}</option>
                        <option value="" selected>مدير الموارد البشريه</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('employee_name')}}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">{{  trns('employee_phone')}}</label>
                    <input type="text" class="form-control" name="phone" id="phone">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{  trns('employee_password')}}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="password_confirmation" class="form-control-label">{{  trns('employee_password_confirmation')}}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </div>
            </div>


            <div class="col-6" style="display:none;">
                <div class="form-group">
                    <label for="code" class="form-control-label">{{ trns('employee_code') }}</label>
                    <span class="form-control text-center">{{ $code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $code }}" id="code">
                </div>
            </div>


            <div class="col-6" style="display:none;">
                <div class="form-group" >
                    <label for="status"  class="form-control-label">{{  trns('employee_status')}}</label>
                    <select class="form-control" name="status" id="status">
                        <option selected value="1">{{ trns('active') }}</option>
                        <option value="0">{{ trns('inactive') }}</option>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="hour_price" class="form-control-label">{{  trns('employee_salary')}}</label>
                    <input type="number" class="form-control" name="salary" id="salary">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="hour_price" class="form-control-label">{{  trns('employee_holidays')}}</label>
                    <input type="number" class="form-control" name="holidays" id="holidays">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{  trns('employee_registration_date')}}</label>
                    <input type="date" class="form-control" name="registered_at" id="registered_at">
                </div>
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>

    </form>
</div>


<script>
    $('.dropify').dropify();
</script>
