<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if ($category = $request->category) {
            return PostResource::collection(Category::where('name', $category)->firstOrFail()->posts()->latest()->paginate(4));
        } elseif ($search = $request->search) {
            return PostResource::collection(Post::where('title', 'LIKE', '%' . $search . '%')
                ->orwhere('body', 'LIKE', '%' . $search . '%')->latest()->get());
        }
        return PostResource::collection(Post::latest()->paginate(4));
    }
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'title' => 'required|unique:posts|min:10|max:70',
                'body' => 'required | min:10 | max:70',
                'file' => 'required | image | mimes:jpeg,png,jpg',
                'category_id' => 'required | exists:categories,id',
            ]
        );
        $data = $request->except('file');
        $data['imagePath'] = (new Post())->uploadAndSaveImage($request->file);
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);
        return Post::create($data);
    }
    public function show(Post $post)
    {
        if (Auth::id() != $post->user_id) {
            return abort(403);
        }
        return new PostResource($post);
    }
    public function update(Post $post, Request $request)
    {
        if (Auth::id() != $post->user_id) {
            return abort(403);
        }
        $data = $request->validate([
            'title' => 'required|min:10|max:70|unique:posts,title,' . $post->id . 'id',
            'body' => 'required | min:10 | max:70',
            'file' => 'sometimes|nullable | image | mimes:jpeg,png,jpg',
            'category_id' => 'required | exists:categories,id',
        ]);
        $data = $request->except('file');
        if ($request->has('file')) {
            $post->removeImage();
            $data['imagePath'] = $post->uploadAndSaveImage($request->file);
        }
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);
        return $post->update($data);
    }
    public function delete(Post $post)
    {
        $post->removeImage();
        $post->delete();
    }
}
