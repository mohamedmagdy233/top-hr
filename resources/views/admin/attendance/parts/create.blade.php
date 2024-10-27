<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('group_name') }}</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('hours_count_per_day')}}</label>
                    <select class="form-control" name="hours" id="hours">
                        @for($i=1; $i<=24; $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i > 10 ? trns('hour') : trns('hours') }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('max_of_advances')}}</label>
                    <input type="number" class="form-control" name="advances" id="advances">
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
