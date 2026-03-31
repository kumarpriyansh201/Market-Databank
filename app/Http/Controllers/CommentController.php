<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\MarketData;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, MarketData $marketData)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => $request->user()->id,
            'market_data_id' => $marketData->id,
            'body' => $validated['body'],
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
