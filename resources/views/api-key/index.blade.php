{{-- Extends layout --}}
@extends('layouts.app')

{{-- Required css --}}
@push('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Switchery -->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/css/switchery.css') }}" media="print"
        onload="this.media='all'">
    <!-- Sweetalert2 -->
    <link href="{{ asset('admin/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" media="print"
        onload="this.media='all'">
@endpush

{{-- Toast --}}
@include('partials.toaster')

{{-- Content --}}
@section('content')
    <!-- row -->
    <div class="container-fluid">
        <div class="form-head d-flex mb-3 mb-md-4 align-items-start">
            <div class="mr-auto d-lg-block">

            </div>
            <div class="input-group search-area ml-auto d-inline-flex mr-2">
                <input type="text" class="form-control" placeholder="Search here">
                <div class="input-group-append">
                    <button type="button" class="input-group-text"><i class="flaticon-381-search-2"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="table-responsive">
                    <table id="permission-table" class="table shadow-hover  table-bordered mb-4 dataTablesCard fs-14">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox align-self-center">
                                        <div class="custom-control custom-checkbox ">
                                            <input type="checkbox" class="custom-control-input" id="checkAll"
                                                required="">
                                            <label class="custom-control-label" for="checkAll"></label>
                                        </div>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Affiliate Name</th>
                                <th>Key</th>
                                <th>InActive/Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lists as $list)
                                @php
                                    $id = $list->id;
                                    $encoded_id = base64_encode($id);
                                    $satus = $list->status;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="checkbox text-right align-self-center">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="list_{{ $id }}" required="">
                                                    <label class="custom-control-label"
                                                        for="list_{{ $id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>#AFK-000{{ $id }}</td>
                                    <td>{{ $affiliate->name }}</td>
                                    <td>{{ $list->key }}</td>
                                    <td>
                                        {{ $list->activeControl(false, true) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @if ($list->trashed())
                                                <a href="javascript:void(0)"
                                                    class="btn btn-warning shadow btn-xs sharp  mr-1 ajax-request"
                                                    title="Restore"
                                                    data-url="{{ route('api-key.restore', $encoded_id) }}"
                                                    data-type="restore" data-reload="true" data-text="">
                                                    <i class="fa fa-recycle" aria-hidden="true"></i>
                                                </a>

                                                <a href="javascript:void(0)"
                                                    class="btn btn-danger shadow btn-xs sharp ajax-request"
                                                    title="Prmanently Delete"
                                                    data-url="{{ route('api-key.permanent-delete', $encoded_id) }}"
                                                    data-type="delete" data-reload="true"
                                                    data-text="You will not be able to recover this data !!">
                                                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)"
                                                    class="btn btn-danger shadow btn-xs sharp ajax-request" title="Delete"
                                                    data-url="{{ route('api-key.delete', $encoded_id) }}"
                                                    data-type="delete" data-reload="true"
                                                    data-text="Only Admin can recover or delete permanently !!">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No record found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $lists->links('partials.pagination') }}
                </div>
            </div>
        </div>
    </div>

    <div id="edit-ajax">

    </div>
@endsection

{{-- Required js --}}
@push('js')
    <!-- Datatable -->
    {{-- <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script> --}}

    <!-- Switchery -->
    <script type="text/javascript" src="{{ asset('admin/js/switchery.js') }}"></script>

    <!-- Sweetalert2 -->
    <script src="{{ asset('admin/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script>
        function passwordShowHide() {
            let password = $('.password');
            if (password.prop('type') == 'text') {
                $
                password.prop("type", "password");
            } else {
                password.prop("type", "text");
            }
        }

        (function($) {
            $('.switchery').each(function(index, item) {
                var _switch = new Switchery(item, {
                    size: 'small',
                    // color:'#369dc9',
                    secondaryColor: '#bc1616',
                    speed: '0.1s',
                });

                $(item).on('change', function(ev) {
                    var url = $(this).data('url');
                    if (url && url.length) {
                        ajax_request($(this), url);
                    }
                });
            });

            $('.ajax-request').each(function(index, item) {
                $(item).on('click', function(ev) {
                    var url = $(this).data('url');
                    var type = $(this).data('type');
                    var text = $(this).data('text');
                    var reload = $(this).data('reload');

                    if (type == 'delete') {
                        swal({
                            title: "Are you sure to delete ?",
                            text: text,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, delete it !!",
                        }).then((result) => {
                            if (result.value) {
                                if (url && url.length) {
                                    ajax_request($(this), url, true);
                                }
                            }
                        })
                    } else {
                        if (url && url.length) {
                            ajax_request($(this), url, reload, type);
                        }
                    }
                });
            });

            function ajax_request(_this, url, reload = false, type = "") {
                (function(_this) {
                    $.ajax({
                        url: url,
                        success: function(data) {
                            if (data.success) {

                                if (type == "edit") {
                                    $("#edit-ajax").empty().append(data.data);
                                    $("#edit-ajax-modal").modal("show");
                                    if ($("#edit-ajax-select").length) {
                                        $("#edit-ajax-select").selectpicker();
                                    }
                                }
                                toaster(data.message, 'Success', 'success');

                                if (reload == true) {
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                }
                            } else {
                                toaster(data.message, 'Error', 'danger');
                            }
                        },
                        error: function(err) {
                            toaster('Something Went Wrong', 'Warning', 'warning');
                        }
                    });
                }(_this));
            }

            $('.phone').bind('copy paste', function(e) {
                e.preventDefault();
            });

        })(jQuery);
    </script>
@endpush
