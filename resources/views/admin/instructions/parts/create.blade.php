<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }}</label>
                    <input type="text" class="form-control" name="title" id="title">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{  trns('description')}}</label>
                    <textarea type="text" class="form-control" name="description" id="description" rows="7">

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
