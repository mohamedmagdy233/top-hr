<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="branch_id" class="form-control-label">{{ trns('branch_name') }}</label>
                    <select class="form-control" name="branch_id">
                        <option disabled>{{ trns('select_branch') }}</option>
                        @foreach ($branches as $branch)
                            <option {{ $obj->branch_id == $branch->id ? 'selected' : '' }} value="{{ $branch->id }}">
                                {{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="group_id" class="form-control-label">{{ trns('group') }}</label>
                    <select class="form-control" name="group_id">
                        <option disabled>{{ trns('select_group') }}</option>
                        <option value="" @if ($obj->group_id == null) selected @endif>
                            المشرفين
                        </option>
                        <option value="1" @if ($obj->group_id == 1) selected @endif>
                            {{ trns('employee') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('employee_name') }}</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ $obj->name }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">{{ trns('employee_phone') }}</label>
                    <input type="text" class="form-control" name="phone" id="phone"
                        value="{{ $obj->phone }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password" class="form-control-label">{{ trns('employee_password') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="password_confirmation"
                        class="form-control-label">{{ trns('employee_password_confirmation') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </div>
            </div>


            <div class="col-6" style="display:none;">
                <div class="form-group">
                    <label for="code" class="form-control-label">{{ trns('employee_code') }}</label>
                    <span class="form-control text-center">{{ $obj->code }}</span>
                    <input hidden type="hidden" class="form-control" name="code" value="{{ $obj->code }}"
                        id="code">
                </div>
            </div>


            <div class="col-6" style="display:none;">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('employee_status') }}</label>
                    <select class="form-control" name="status" id="status">
                        <option selected value="1" {{ $obj->status == 1 ? 'selected' : '' }}>{{ trns('active') }}
                        </option>
                        <option value="0" {{ $obj->status == 0 ? 'selected' : '' }}>{{ trns('inactive') }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="hour_price" class="form-control-label">{{  trns('employee_salary')}}</label>
                    <input type="number" class="form-control" name="salary" id="salary" value="{{ $obj->salary }}">
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label for="hour_price" class="form-control-label">{{  trns('employee_holidays')}}</label>
                    <input type="number" class="form-control" name="holidays" id="holidays" value="{{ $totalDays }}">
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="status" class="form-control-label">{{ trns('employee_registration_date') }}</label>
                    <input type="date" value="{{ date('Y-m-d', strtotime($obj->registered_at)) }}"
                        class="form-control" name="registered_at" id="registered_at">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-success" id="updateButton">{{ trns('update') }}</button>
        </div>
    </form>
</div>
<script>
    $('.dropify').dropify()
</script>
