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
        $vote = $this->getVote($imageUpload->id);
        return $vote;
    }

    public function store(ImageUpload $imageUpload, $score) {
        $getVote = $this->getVote($imageUpload->id);
        if($getVote) return ['error' => 'Vote already exists'];
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
        $vote = $this->getVote($imageUpload->id);
        if($vote) {
            $vote->update([
                'score' => $score
            ]);
            $imageUpload->increment('upvote', $score*2);
        }
        return $imageUpload;
    }

    public function destroy(ImageUpload $imageUpload, $score) {
        $vote = $this->getVote($imageUpload->id);
        $vote->delete();
        $imageUpload->decrement('upvote', $score);
        request()->session()->put('vote', $vote);
        return $imageUpload;
    }


}
