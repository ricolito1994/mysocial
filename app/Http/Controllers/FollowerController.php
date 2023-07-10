<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function followers ($userId) 
    {
        try {
            $followers = User::find($userId)->followers()->get();
            return response()->json(['followers'=> $followers], 200);
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function suggestedFollowers () 
    {
        try {
            $userId = Auth::id();

            $usersNotFollowed = User::whereNotIn('id', function ($query) use ($userId) {
                $query->select('follower_id')
                    ->from('follower')
                    ->where('follower_id', $userId);
            })
            ->where('id', '<>', $userId) 
            ->inRandomOrder()
            ->limit(5) 
            ->get();
            return response()->json(['suggestedUsers'=> $usersNotFollowed], 200);
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function followUser ($followUserId) 
    {
        try {
            Follower::create([
                'user_id' => $followUserId,
                'follower_id' => Auth::id(),
            ]);
            return response()->json(['message'=> 'Follow success.'], 200);
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

    public function unfollowUser ($followUserId) 
    {
        try {
            $follower = Follower::where([
                'user_id' => $followUserId,
                'follower_id' => Auth::id(),
            ])->first();
            $follower->delete();
            return response()->json(['message'=> 'You have unfollowed this user.'], 200);
        } catch (Exception $e) {
            return response()->json(['err' => $e], 500);
        }
    }

}
