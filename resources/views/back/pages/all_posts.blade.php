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
