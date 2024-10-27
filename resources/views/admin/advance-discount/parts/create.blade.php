<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <label for="user_id" class="form-control-label">{{ trns('user_name') }}</label>
                <select class="form-control" name="user_id" id="userSelect">
                    <option value="" disabled selected>{{ trns('select_user') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

                <div class="col-6">
                    <label for="value" class="form-control-label">{{ trns('advance_amount') }}</label>
                    <input class="form-control" name="value"  type="text" >
                </div>

                <div class="col-6">
                    <label for="advance_id" class="form-control-label">{{ trns('note') }}</label>
                    <input class="form-control" name="note"  type="text" >
                </div>


            </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>
    </form>
</div>
