<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}" >
    @csrf
        @method('PUT')
        <input type="hidden" value="{{$obj->id}}" name="id">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="title" class="form-control-label">{{ trns('title') }}</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $obj->title }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="description" class="form-control-label">{{  trns('description')}}</label>
                    <textarea type="text" class="form-control" name="description" id="description" rows="7">

                    {{ $obj->description }}
                    </textarea>

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
