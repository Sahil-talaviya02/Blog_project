@extends('back.layout.pages-layout')

@section('pageTitle')
    {{ isset($pageTitle) ? $pageTitle : 'Add Post' }}
@endsection

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">

        <!-- LEFT SIDE -->
        <div>
            <h4 class="mb-1">Add Post</h4>

            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Add Post
                    </li>
                </ol>
            </nav>
        </div>

        <!-- RIGHT SIDE -->
        <div>
            <a href="{{ route('admin.posts') }}" class="btn btn-primary">
                View All Posts
            </a>
        </div>

    </div>

    <form action="{{ route('admin.create_post') }}" method="POST" autocomplete="off" enctype="multipart/form-data"
        id="addPostForm">
        @csrf
        <div class="row">
            <div class="col-md-8">

                <div class="card card-box mb-3">
                    <div class="card-body">

                        <div class="form-group mb-4">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                                placeholder="Enter title" required>
                            <span class="text-danger error-text title_error"></span>
                        </div>

                        <div class="form-group mb-4">
                            <label for="content">Content</label>
                            <textarea class="form-control" name="content" rows="10" cols="30" id="post_content"
                                placeholder="Enter post content here..."></textarea>
                            <span class="text-danger error-text content_error"></span>
                        </div>
                    </div>
                </div>

                <div class="card card-box mb-3">
                    <div class="card-header weight-500">SEO</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="post_url">Post Meta Keywords: (Separate keywords with commas)</label>
                            <input type="text" name="meta_keywords" class="form-control"
                                placeholder="Enter post meta keywords">
                        </div>
                        <div class="form-group">
                            <label for="post_url">Post Meta Description:</label>
                            <textarea name="meta_description" id="meta_description" cols="30" rows="10" class="form-control"
                                placeholder="Enter post meta description"></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="card card-box mb-3">
                    <div class="card-body">

                        <div class="form-group">
                            <label for="post_url">Post Category:</label>
                            <select name="category" id=""
                                class="custom-select form-control">{!! $categories_html !!}</select>
                            <span class="text-danger error-text category_error"></span>
                        </div>

                        <div class="form-group">
                            <label for="post_url">Post Featured Image:</label>
                            <input type="file" name="featured_image" class="form-control-file form-control"
                                height="auto">
                            <span class="text-danger error-text featured_image_error"></span>
                        </div>

                        <div class="d-block mb-3" style="width: 250px;">
                            <img src="" alt="Post Image" class="img-thumbnail" id="featured_image_preview"
                                data-ijabo-default-img="">
                        </div>

                        <div class="form-group">
                            <label for="post_url">Post Tags:</label>
                            <input type="text" name="tags" class="form-control" placeholder="Enter post tags"
                                data-role="tagsinput">
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for=""><b>Visibility</b></label>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio1" class="custom-control-input"
                                    value="1" checked>
                                <label class="custom-control-label" for="customRadio1">Public</label>
                            </div>
                            <div class="custom-control custom-radio mb-5">
                                <input type="radio" name="visibility" id="customRadio2" class="custom-control-input"
                                    value="0">
                                <label class="custom-control-label" for="customRadio2">Private</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Create Post</button>
        </div>
    </form>
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('back/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('back/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Preview the featured image
            $(document).on('change', 'input[type="file"][name="featured_image"]', function() {
                const file = this.files[0];
                const preview = document.getElementById('featured_image_preview');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        //create a post
        $("#addPostForm").on('submit', function(e) {
            e.preventDefault();
            let form = this;
            let formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    if (response.status == 'success') {
                        $(form)[0].reset();
                        $("#featured_image_preview").attr('src', '');
                        $('input[name="tags"]').tagsinput('removeAll');
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(response) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        $(form).find('span.' + key + '_error').text(value[0]);
                    });
                    toastr.error('Something went wrong. Please try again later.');
                }
            });
        })
    </script>
@endpush
