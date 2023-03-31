<!-- Start Edit Affiliate Modal -->
<div class="modal fade" id="edit-ajax-modal" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Affiliate</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>

            <form method="post" action="{{ route('affiliate.update') }}" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')
                <input type="hidden" name="id" value="{{ base64_encode($affiliate->id) }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control input-default name" name="name" required
                                    placeholder="Affiliate Name" value="{{ old('name', $affiliate->name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control input-default company_name" name="company_name" required
                                    placeholder="Company Name"
                                    value="{{ old('company_name', $affiliate->company_name) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control input-default address" name="address" required
                                    placeholder="Affiliate Address" value="{{ old('address', $affiliate->address) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control input-default phone"
                                    id="phone" name="phone" required placeholder="Phone"
                                    maxlength="10" onkeypress="return /[0-9]/i.test(event.key)"
                                    value="{{ old('phone',  $affiliate->phone) }}">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- End Edit Affiliate Modal -->
