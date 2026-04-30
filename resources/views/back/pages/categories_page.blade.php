@extends('back.layout.pages-layout')

@section('pageTitle', 'Categories Page')

@section('content')
    @livewire('admin.categories')
@endsection

@push('scripts')
    <script>
        // ================= MODALS =================

        window.addEventListener('showParentCategoryModalForm', () => {
            $('#pcategory-modal').modal('show');
        });

        window.addEventListener('hideParentCategoryModalForm', () => {
            $('#pcategory-modal').modal('hide');
        });

        window.addEventListener('showCategoryModalForm', () => {
            $('#category-modal').modal('show');
        });

        window.addEventListener('hideCategoryModalForm', () => {
            $('#category-modal').modal('hide');
        });


        // ================= DELETE CONFIRM =================
        document.addEventListener('livewire:init', () => {

            // ================= PARENT DELETE =================
            Livewire.on('confirmDelete', ({
                id
            }) => {
                if (!confirm('Are you sure you want to delete this parent category?')) {
                    return; // ❌ STOP if cancel
                }

                Livewire.dispatch('deleteParentCategory', {
                    id
                });
            });

            // ================= CATEGORY DELETE =================
            Livewire.on('confirmDeleteCategory', ({
                id
            }) => {
                if (!confirm('Are you sure you want to delete this category?')) {
                    return; // ❌ STOP if cancel
                }

                Livewire.dispatch('deleteCategory', {
                    id
                });
            });

        });
    </script>
@endpush
