<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        @method('PUT')
        <input type="hidden" value="{{ $obj->id }}" name="id">
        <h2>{{ trns('incentive') }} / {{ trns('deduction') }} {{ trns('for_employee')}}</h2>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="type" class="form-control-label">{{ trns('opration_type') }}</label>
                    <select class="form-control" name="incentive" id="incentive">
                        <option disabled selected value="">{{ trns('select') }}</option>
                        <option value="0">{{ trns('deduction') }}</option>
                        <option value="1">{{ trns('incentive')}}</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="type" class="form-control-label">{{ trns('value') }}</label>
                    <input type="number" class="form-control" name="value" id="type"
                        value="{{ $obj->value }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="type" class="form-control-label">{{ trns('reason') }}</label>
                    <input type="text" class="form-control" name="reason" id="reason"
                           value="{{ $obj->reason }}">
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
