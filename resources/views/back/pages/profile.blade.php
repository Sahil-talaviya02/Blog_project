@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')

@section('content')

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('admin.profile')
@endsection

@push('scripts')
    <script>
        $('#profilePictureFile').on('change', function() {

            let file = this.files[0];
            let formData = new FormData();

            formData.append('profilePictureFile', file);

            $.ajax({
                url: "{{ route('admin.update_profile_picture') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status === 1) {
                        $('#profilePicturePreview').attr('src', response.url + '?' + Date.now());
                        showToast('success', response.msg);
                        Livewire.dispatch('updateTopUserInfo', []);
                        Livewire.dispatch('updateProfile', []);
                    } else {
                        showToast('error', response.msg);
                    }
                },
                error: function(err) {
                    showToast('error', 'Upload failed');
                }
            });

        });
    </script>
@endpush
