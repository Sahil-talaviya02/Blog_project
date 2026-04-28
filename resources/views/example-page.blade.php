@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title Here')

@section('content')
    <div class="card-box pd-20 mb-30">
        <h1 class="mb-4">Toast Notification Test</h1>
        <div class="btn-list">
            <button class="btn btn-success" onclick="Livewire.dispatch('showToast', {type: 'success', message: 'Success message!'})">Success Toast</button>
            <button class="btn btn-danger" onclick="Livewire.dispatch('showToast', {type: 'error', message: 'Error message!'})">Error Toast</button>
            <button class="btn btn-warning" onclick="Livewire.dispatch('showToast', {type: 'warning', message: 'Warning message!'})">Warning Toast</button>
            <button class="btn btn-info" onclick="Livewire.dispatch('showToast', {type: 'info', message: 'Info message!'})">Info Toast</button>
        </div>
    </div>
@endsection