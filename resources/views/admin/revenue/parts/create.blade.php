<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">

            <div class="col-12">
                <div class="form-group">
                    <label for="name" class="form-control-label">{{trns('image')}}</label>
                    <input type="file" class="dropify" name="image"
                           data-default-file="{{ asset('assets/uploads/avatar.png') }}"
                           accept="image/png,image/webp , image/gif, image/jpeg,image/jpg" />

                </div>
            </div>


            <div class="col-12">
                <div class="form-group">
                    <label for="reason" class="form-control-label">{{ trns('value') }}</label>

                    <input type="text" class="form-control" name="value" id="value">
                </div>
            </div>
            <div class="col-12">

                <div class="form-group">
                    <label for="reason" class="form-control-label">{{ trns('reason') }}</label>
                    <textarea type="text" class="form-control" name="reason" id="reason"> </textarea>
                </div>
            </div>

            <div class="col-12">

                <div class="form-group">
                    <label for="created_at" class="form-control-label">{{ trns('created_at') }}</label>
                    <input type="date" class="form-control" name="created_at" id="created_at">
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
