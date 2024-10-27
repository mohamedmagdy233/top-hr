<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{ $route }}" >
    @csrf
        @method('PUT')
        <input type="hidden" value="{{$obj->id}}" name="id">
        <div class="form-group">
            <label for="name" class="form-control-label">{{trns('image')}}</label>
            <input type="file" class="dropify" name="image"
                   data-default-file="{{ asset('storage/' . $obj->image ?? 'assets/uploads/avatar.png') }}"
                   accept="image/png,image/webp , image/gif, image/jpeg,image/jpg" />

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
