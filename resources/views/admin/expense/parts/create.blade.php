<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ $route }}">
        @csrf
        <div id="inputRows">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-control-label">{{trns('image')}}</label>
                        <input type="file" class="dropify" name="image"
                               data-default-file="{{ asset('assets/uploads/avatar.png') }}"
                               accept="image/png,image/webp , image/gif, image/jpeg,image/jpg" />

                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="form-control-label">{{ trns('value') }}</label>
                        <input type="text" class="form-control" name="value[]">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-control-label">{{ trns('reason') }}</label>
                        <textarea rows="1" class="form-control" name="reason[]"></textarea>
                    </div>
                </div>

                <div class="col-12">

                    <div class="form-group">
                        <label for="created_at" class="form-control-label">{{ trns('created_at') }}</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                </div>
{{--                <div class="col-2">--}}
{{--                    <div class="form-group" style="margin-top: 27px;">--}}
{{--                        <!-- Changed id to class for addRowButton -->--}}
{{--                        <button type="button" class="btn btn-success addRowButton">+</button>--}}
{{--                        <!-- Added removeRowButton -->--}}
{{--                        <button type="button" class="btn btn-danger removeRowButton">-</button>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trns('close') }}</button>
            <button type="submit" class="btn btn-primary" id="addButton">{{ trns('save') }}</button>
        </div>
    </form>
</div>

<!-- JavaScript to Add and Remove Rows -->
<script>
    $(document).ready(function(){
        // Initially hide the remove button if there's only one row
        if ($('#inputRows .row').length == 1) {
            $('#inputRows .row .removeRowButton').hide();
        }

        // Event delegation for the addRowButton
        $('#inputRows').on('click', '.addRowButton', function(){
            // Clone the last row
            var $lastRow = $('#inputRows .row:last');
            var $newRow = $lastRow.clone();

            // Clear input values in the cloned row
            $newRow.find('input').val('');
            $newRow.find('textarea').val('');

            // Hide the addRowButton in the previous row
            $lastRow.find('.addRowButton').hide();

            // Show the removeRowButton in the previous row
            $lastRow.find('.removeRowButton').show();

            // Append the new row
            $('#inputRows').append($newRow);

            // Show the addRowButton in the new last row
            $newRow.find('.addRowButton').show();

            // Hide the removeRowButton if only one row exists
            if ($('#inputRows .row').length == 1) {
                $newRow.find('.removeRowButton').hide();
            } else {
                $newRow.find('.removeRowButton').show();
            }
        });

        // Event delegation for the removeRowButton
        $('#inputRows').on('click', '.removeRowButton', function(){
            // Prevent removal if there's only one row
            if ($('#inputRows .row').length == 1) {
                return;
            }

            var $currentRow = $(this).closest('.row');

            // If the current row is the last row
            if ($currentRow.is(':last-child')) {
                // Show the addRowButton in the previous row
                $currentRow.prev().find('.addRowButton').show();
            }

            // Remove the current row
            $currentRow.remove();

            // Hide the removeRowButton if only one row remains
            if ($('#inputRows .row').length == 1) {
                $('#inputRows .row .removeRowButton').hide();
            }
        });
    });
</script>

<script>
    $('.dropify').dropify()
</script>
