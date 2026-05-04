<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ParentCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    // Show Add Post Form
    public function addPost(Request $request)
    {
        $categories_html = '';

        $pcategories = ParentCategory::whereHas('children')
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get();

        if (count($pcategories) > 0) {
            foreach ($pcategories as $item) {
                $categories_html .= '<optgroup label="' . $item->name . '">';

                foreach ($item->children as $category) {
                    $categories_html .= '<option value="' . $category->id . '">' . $category->name . '</option>';
                }

                $categories_html .= '</optgroup>';
            }
        }

        if (count($categories) > 0) {
            foreach ($categories as $item) {
                $categories_html .= '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }

        $data = [
            'pageTitle' => 'Add New Post',
            'categories_html' => $categories_html,
        ];

        return view('back.pages.add-post', $data);
    }

    // Create Post
    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts,title',
            'content' => 'required',
            'category' => 'required|exists:categories,id',
            'featured_image' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $path = 'images/posts/';
            $file = $request->file('featured_image');
            $fileName = $file->getClientOriginalName();
            $new_fileName = time() . '_' . $fileName;

            $upload = $file->move(public_path($path), $new_fileName);

            if ($upload) {
                $post = new Post();
                $post->author_id = auth()->id();
                $post->category_id = $request->category;
                $post->title = $request->title;
                $post->content = $request->content;
                $post->featured_image = $new_fileName;
                $post->tags = $request->tags;
                $post->meta_keywords = $request->meta_keywords;
                $post->meta_description = $request->meta_description;
                $post->visibility = $request->visibility;
                $saved = $post->save();

                if ($saved) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Post created successfully',
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to create post',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to upload featured image',
                ]);
            }
        }
    }

    //all post
    public function allPosts(Request $request)
    {
        $data = [
            'pageTitle' => 'All Posts',
        ];

        return view('back.pages.all_posts', $data);
    }

    //edit post
    public function editPost(Request $request, $id = null)
    {
        $post = Post::findorfail($id);
        $categories_html = '';
        $pcategories = ParentCategory::whereHas('children')
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->orderBy('name', 'asc')
            ->get();

        if (count($pcategories) > 0) {
            foreach ($pcategories as $item) {
                $categories_html .= '<optgroup label="' . $item->name . '">';

                foreach ($item->children as $category) {
                    $selected = $category->id == $post->category_id ? 'selected' : '';
                    $categories_html .= '<option value="' . $category->id . '"' . $selected . '>' . $category->name . '</option>';
                }

                $categories_html .= '</optgroup>';
            }
        }

        if (count($categories) > 0) {
            foreach ($categories as $item) {
                $selected = $item->id == $post->category_id ? 'selected' : '';
                $categories_html .= '<option value="' . $item->id . '"' . $selected . '>' . $item->name . '</option>';
            }
        }

        $data = [
            'pageTitle' => 'Edit Post',
            'post' => $post,
            'categories_html' => $categories_html,
        ];

        return view('back.pages.edit_post', $data);
    }

    //Update Post
    public function updatePost(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        $featured_image_name = $post->featured_image;

        $request->validate([
            'title' => 'required|unique:posts,title,' . $request->post_id,
            'content' => 'required',
            'category' => 'required|exists:categories,id',
            'featured_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $old_featured_image = $post->featured_image;
            $path = 'images/posts/';
            $file = $request->file('featured_image');
            $fileName = $file->getClientOriginalName();
            $new_fileName = time() . '_' . $fileName;

            $upload = $file->move(public_path($path), $new_fileName);

            if ($upload) {
                if ($old_featured_image != null && File::exists(public_path($path . $old_featured_image))) {
                    File::delete(public_path($path . $old_featured_image));
                }
                $featured_image_name = $new_fileName;
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to upload featured image',
                ]);
            }
        }

        $post->category_id = $request->category;
        $post->title = $request->title;
        $post->slug = null;
        $post->content = $request->content;
        $post->featured_image = $featured_image_name;
        $post->tags = $request->tags;
        $post->meta_keywords = $request->meta_keywords;
        $post->meta_description = $request->meta_description;
        $post->visibility = $request->visibility;
        $saved = $post->save();

        if ($saved) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'redirect' => route('admin.posts')
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update post',
            ]);
        }
    }

    public function deletePost($id)
    {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found'
                ]);
            }

            // delete image
            $imagePath = public_path('images/posts/' . $post->featured_image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
