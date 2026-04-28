<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4 class="h4 text-blue">Parent Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addParentCategory" class="btn btn-primary btn-sm "> Add Parent
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
                            <tr>
                                <td>1</td>
                                <td>P. Cat 1</td>
                                <td>4</td>
                                <td>
                                    <div class="table-action">
                                        <a href="#" class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i></a>
                                        <a href="#" class="text-danger mx-2">
                                            <i class="dw dw-delete-3"></i></a>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
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
                        <a href="#" class="btn btn-primary btn-sm ">Add Category</a>
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
                            <tr>
                                <td>1</td>
                                <td>category 1</td>
                                <td>Any</td>
                                <td>4</td>
                                <td>
                                    <div class="table-action">
                                        <a href="#" class="text-primary mx-2">
                                            <i class="dw dw-edit2"></i></a>
                                        <a href="#" class="text-danger mx-2">
                                            <i class="dw dw-delete-3"></i></a>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    <div wire:ignore.self class="modal fade" id="pcategory-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateParentCategoryMode ? 'Update Category' : 'Add Category' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit, sed do eiusmod tempor incididunt ut labore et
                        dolore magna aliqua. Ut enim ad minim veniam, quis
                        nostrud exercitation ullamco laboris nisi ut aliquip
                        ex ea commodo consequat. Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur. Excepteur sint occaecat
                        cupidatat non proident, sunt in culpa qui officia
                        deserunt mollit anim id est laborum.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
