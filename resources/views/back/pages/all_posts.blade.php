@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'All Posts')
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">

        <!-- LEFT SIDE -->
        <div>
            <h4 class="mb-1">Posts</h4>

            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        List
                    </li>
                </ol>
            </nav>
        </div>

        <!-- RIGHT SIDE -->
        <div>
            <a href="{{ route('admin.add_post') }}" class="btn btn-primary">
                Add Post
            </a>
        </div>
    </div>
    @livewire('admin.posts')
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            // Setup CSRF for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            // Delete button click
            $(document).on('click', '.deletePostBtn', function(e) {
                e.preventDefault();

                let button = $(this);
                let id = button.data('id');
                let row = button.closest('tr');

                // Confirm before delete
                if (!confirm("Are you sure you want to delete this post?")) {
                    return;
                }

                $.ajax({
                    url: '/admin/post/delete/' + id,
                    type: 'DELETE',

                    success: function(res) {
                        if (res.status === 'success') {
                            toastr.success(res.message);

                            // Remove row smoothly
                            row.fadeOut(400, function() {
                                $(this).remove();
                            });

                        } else {
                            toastr.error(res.message || 'Delete failed');
                        }
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText); // debug error

                        toastr.error('Something went wrong!');
                    }
                });

            });

        });
    </script>
@endpush
