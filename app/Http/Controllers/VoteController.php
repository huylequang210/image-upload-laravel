<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\ImageUpload;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    private function getVote($imgId) {
        return Vote::where([
            ['upload_image_id', '=', $imgId],
            ['user_id', '=', Auth::id()]
        ])->first();
    }

    public function index(ImageUpload $imageUpload) {
        $vote = Vote::myVote($imageUpload->id, Auth::id())->first();
        return $vote;
    }

    public function store(ImageUpload $imageUpload, $score) {
        $getVote = Vote::myVote($imageUpload->id, Auth::id())->first();
        if($getVote) {
            return response()->json(['error' => 'Not allow'], 403);
        }
        $vote =  Vote::create([
            'user_id' => Auth::id(),
            'upload_image_id' => $imageUpload->id,
            'score' => $score
        ]);
        $imageUpload->increment('upvote', $score);
        request()->session()->put('vote', $vote);
        return $imageUpload;
    }

    public function update(ImageUpload $imageUpload, $score) {
        $vote = Vote::myVote($imageUpload->id, Auth::id())->first();
        if($vote->score == $score) {
            return response()->json(['error' => 'Not allow'], 403);
        }
        if($vote) {
            $vote->update([
                'score' => $score
            ]);
            $imageUpload->increment('upvote', $score*2);
        }
        return $imageUpload;
    }

    public function destroy(ImageUpload $imageUpload, $score) {
        $vote = Vote::myVote($imageUpload->id, Auth::id())->first();
        $vote->delete();
        $imageUpload->decrement('upvote', $score);
        return $imageUpload;
    }


}
