

<form class="form" method="POST" id="edit-role-form" action="{{route('user.role.update', $id)}}">
    @csrf
    <div class="modal-header bg-gray-100">
        <h5 class="modal-title" id="exampleModalLabel">{{trans('display.role')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close"></i>
        </button>
    </div>

    <div class="card-body m-4">
        <div class="form-group row">
            <label class="col-form-label text-right col-lg-3 col-sm-12">Дүр нэмэх: </label>
            <div class="col-md-9 col-sm-12">
                <select class="form-control select2" id="role_id" name="role_list[]" multiple="multiple">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{in_array( $role->id, $selectedRoles) ? 'selected': ''}}>{{ $role->name  }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
        <button type="submit" class="btn btn-primary font-weight-bold">{{trans('display.general_save')}}</button>
    </div>
</form>

<script>

    $(document).ready(function() {
            $('#role_id').select2({
            // placeholder: 'Дүр нэмэх'
        });
    });

</script>
