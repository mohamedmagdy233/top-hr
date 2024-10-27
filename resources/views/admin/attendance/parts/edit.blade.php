<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}" >
    @csrf
        @method('PUT')
        <input type="hidden" value="{{$obj->id}}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{ trns('group_name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{$obj->name}}">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('hours_count_per_day')}}</label>
                    <select class="form-control" name="hours" id="hours">
                        @for($i=1; $i<=24; $i++)
                            <option value="{{ $i }}" @if($obj->hours == $i) selected @endif>{{ $i }} {{ $i > 10 ? trns('hour') : trns('hours') }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{  trns('max_of_advances')}}</label>
                    <input type="number" class="form-control" name="advances" id="advances" value="{{$obj->advances}}">
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
