<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Parent Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addParentCategory()" class="btn btn-primary btn-sm "> Add Parent
                            Category</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-sm table-striped">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Name</th>
                            <th>N. Of Category</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @forelse ($parentCategories as $item)
                                <tr>
                                    <td>{{ ($parentCategories->currentPage() - 1) * $parentCategories->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->children->count() }}</td>
                                    <td>
                                        <div class="table-action">
                                            <a href="javascript:;" wire:click="editParentCategory({{ $item->id }})"
                                                class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i></a>
                                            <a href="javascript:;" wire:click="confirmDelete({{ $item->id }})"
                                                class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Parent Categories Found</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $parentCategories->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addCategory()" class="btn btn-primary btn-sm ">Add
                            Category</a>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-sm table-striped">
                        <thead class="bg-secondary text-white">
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Category</th>
                            <th>N. Of Posts</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parentCategory->name ?? 'N/A' }}
                                    </td>
                                    <td>{{ $category->posts_count ?? '-' }}</td>
                                    <td>
                                        <div class="table-action">
                                            <a href="javascript:;" wire:click="editCategory({{ $category->id }})"
                                                class="text-primary mx-2">
                                                <i class="dw dw-edit2"></i></a>
                                            <a href="javascript:;"
                                                wire:click="confirmDeleteCategory({{ $category->id }})"
                                                class="text-danger mx-2">
                                                <i class="dw dw-delete-3"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No categories found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $categories->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    {{-- parent category modal --}}
    <div wire:ignore.self class="modal fade" id="pcategory-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content"
                wire:submit="{{ $isUpdateParentCategoryMode ? 'updateParentCategory()' : 'createParentCategory()' }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateParentCategoryMode ? 'Update Parent Category' : 'Add Parent Category' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateParentCategoryMode)
                        <input type="hidden" name="pcategory_id" wire:model="pcategory_id" />
                    @endif
                    <div class="form-group">
                        <label for="pcategory_name">Parent Category Name</label>
                        <input type="text" wire:model="pcategory_name" class="form-control"
                            placeholder="Enter Parent Category Name" />
                    </div>
                    @error('pcategory_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateParentCategoryMode ? 'Save changes' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- category modals --}}
    <div wire:ignore.self class="modal fade" id="category-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content"
                wire:submit="{{ $isUpdateCategoryMode ? 'updateCategory()' : 'createCategory()' }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateCategoryMode ? 'Update Category' : 'Add Category' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateCategoryMode)
                        <input type="hidden" name="category_id" wire:model="category_id" />
                    @endif

                    <div class="form-group">
                        <label for="parent_category">Parent Category</label>
                        <select wire:model="category_parent_id" class="form-control">
                            <option value="">-- Select Parent Category --</option>

                            @foreach ($allParentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}">
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_parent_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" wire:model="category_name" class="form-control"
                            placeholder="Enter Category Name" />
                    </div>
                    @error('category_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateCategoryMode ? 'Save changes' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
