@extends('back.layout.pages-layout')

@section('pageTitle', 'Settings')

@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <h4>Settings</h4>

                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Settings
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-4">
        @livewire('admin.settings')
    </div>

@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            // ================= LOGO PREVIEW =================
            $(document).on('change', 'input[name="site_logo"]', function() {

                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_site_logo').attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            });

            // ================= FAVICON PREVIEW =================
            $(document).on('change', 'input[name="site_favicon"]', function() {

                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_favicon').attr('src', e.target.result);
                };

                reader.readAsDataURL(this.files[0]);
            });

            // ================= AJAX SUBMIT =================
            $(document).on('submit', '#updateLogoForm', function(e) {

                e.preventDefault();

                let form = this;
                let formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend: function() {
                        $(form).find('button').prop('disabled', true);
                    },

                    success: function(res) {

                        $(form).find('button').prop('disabled', false);

                        if (res.status) {

                            toastr.success(res.msg);

                            if (res.logo) {
                                $('#preview_site_logo').attr('src', res.logo);
                            }

                            if (res.favicon) {
                                $('#preview_favicon').attr('src', res.favicon);
                            }

                            form.reset();

                        } else {
                            toastr.error(res.msg);
                        }
                    },

                    error: function() {
                        $(form).find('button').prop('disabled', false);
                        toastr.error('Upload failed');
                    }
                });

            });

        });
    </script>
@endpush
