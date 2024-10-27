<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">
            <div class="col-6">
                <label for="user_id" class="form-control-label">{{ trns('user_name') }}</label>
                <select class="form-control" name="user_id" id="userSelect" required>
                    <option value="" disabled selected>{{ trns('select_user') }}</option>
                    <option value="">{{ trns('all') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }}</label>
                    <input type="text" class="form-control" name="title" id="title" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="body" class="form-control-label">{{  trns('body')}}</label>
                    <textarea type="text" class="form-control" name="body" id="body" required>

                    </textarea>

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
