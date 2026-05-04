<div>

    <div class="pd-20 card-box mb-30">
        <div class="row mb-20">
            <div class="col-md-4">
                <label for="search"><b class="text-secondary">Search</b></label>
                <input wire:model.live="search" id="search" name="search" type="text" class="form-control"
                    placeholder="Search posts....">
            </div>

            @if (auth()->user()->type == 'superAdmin')
                <div class="col-md-2">
                    <label for="author_id"><b class="text-secondary">Author</b></label>
                    <select wire:model.live="author_id" name="author_id" id="author_id" class="form-control">
                        <option value="">Select Author</option>
                        @foreach (\App\Models\User::whereHas('posts')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-2">
                <label for="category"><b class="text-secondary">Category</b></label>
                <select wire:model.live="category" name="category" id="category" class="form-control">
                    <option value="">Select Category</option>
                    {!! $categories_html !!}
                </select>
            </div>

            <div class="col-md-2">
                <label for="visibility"><b class="text-secondary">Visibility</b></label>
                <select wire:model.live="visibility" name="visibility" id="visibility" class="form-control">
                    <option value="">Select Visibility</option>
                    <option value="0">Private</option>
                    <option value="1">Public</option>
                </select>
            </div>

            <div class="col-md-2">
                <label for="author_id"><b class="text-secondary">Sort By</b></label>
                <select wire:model.live="sortBy" name="author_id" id="author_id" class="form-control">
                    <option value="">Select Sort Order</option>
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>

        </div>
        <div class="table-responsive">
            <table class="table table-striped table auto table-sm">
                <thead class="bg-secondary text-white">
                    <th scope="col">#ID</th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Category</th>
                    <th scope="col">Visibility</th>
                    <th scope="col">Actions</th>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td scope="row">
                                {{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}
                            <td>
                                <a href=""><img src="{{ asset('images/posts/' . $post->featured_image) }}"
                                        alt="" width="100"></a>
                            </td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->author->name }}</td>
                            <td>{{ $post->category->name }}</td>
                            <td>
                                @if ($post->visibility == 1)
                                    <span class="badge badge-pill badge-success">Public</span>
                                @else
                                    <span class="badge badge-pill badge-secondary">Private</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('admin.edit_post', [$post->id]) }}" data-color="#265ed7"
                                        style="color: rgb(38,94,216)"><i class="icon-copy dw dw-edit2"></i></a>
                                    <a href="javascript:void(0);" class="deletePostBtn" data-id="{{ $post->id }}"
                                        style="color:red;">
                                        <i class="icon-copy dw dw-delete-3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No posts found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-block mt-1 text-center">
            {{ $posts->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
