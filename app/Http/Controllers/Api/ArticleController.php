<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::with('author');

        if ($request->has('published_only')) {
            $query->published();
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json($articles);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = $request->user()?->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($request->is_published) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        return response()->json([
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $article
        ], 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        // Increment view count
        $article->increment('views');
        
        return response()->json($article->load('author'));
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'sometimes|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        if (isset($validated['title'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        if ($request->is_published && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return response()->json([
            'message' => 'Artikel berhasil diperbarui',
            'data' => $article
        ]);
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return response()->json([
            'message' => 'Artikel berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get published articles for frontend
     */
    public function publicIndex(Request $request)
    {
        $articles = Article::published()
            ->orderBy('published_at', 'desc')
            ->limit($request->limit ?? 10)
            ->get();

        return response()->json($articles);
    }

    /**
     * Public endpoint - get single article by slug
     */
    public function publicShow($slug)
    {
        $article = Article::where('slug', $slug)->published()->firstOrFail();
        $article->increment('views');
        
        return response()->json($article);
    }
}
