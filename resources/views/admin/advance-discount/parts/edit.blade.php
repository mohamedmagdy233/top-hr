<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}" >
    @csrf
        @method('PUT')
        <input type="hidden" value="{{$obj->id}}" name="id">
        <div class="row">
            <div class="col-6">
                <label for="user_id" class="form-control-label">{{ trns('user_name') }}</label>
                <select class="form-control" name="user_id" id="userSelect">
                    <option value="" disabled selected>{{ trns('select_user') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($user->id == $obj->user_id ) selected @endif >{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-6">
                <label for="value" class="form-control-label">{{ trns('advance_amount') }}</label>
                <input class="form-control" name="value"  type="text"  value=" {{$obj->value}}" >
            </div>

            <div class="col-6">
                <label for="advance_id" class="form-control-label">{{ trns('note') }}</label>
                <input class="form-control" name="note"  type="text" value=" {{$obj->note}}" >
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
