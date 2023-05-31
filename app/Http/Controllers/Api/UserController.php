<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;

class UserController extends Controller
{
    public function usersList(Request $request)
    {
        try {
            $type = (string) $request->has('type') ? $request->input('type') : null;
            $point = (int) $request->has('point') ? $request->input('point') : null;

            $users = User::all();

            if (is_null($type) && is_null($point)) {
                $users = $users->load(['badges', 'likes', 'comments', 'posts']);

                return response()->json([
                    'data' => $users,
                    'next_badge' => $this->nextBadge($request->user())
                ], 200);
            }

            if (is_null($point) && !is_null($type)) {
                $users = $users->filter(function (object $user, int $key) use ($type) {
                    return $user->badges->where('name', $type)->first();
                });

                if ($users->isNotEmpty()) {
                    $users->load(['badges', 'likes', 'comments', 'posts']);

                    return response()->json([
                        'data' => $users,
                        'next_badge' => $this->nextBadge($request->user())
                    ], 200);
                } else {
                    return response()->json([
                        'message' => "no records found",
                    ], 200);
                }
            }

            if (!is_null($point) && is_null($type)) {
                $users = $users->filter(function (object $user, int $key) use ($point) {
                    return $user->points = $point;
                });

                if ($users->isNotEmpty()) {
                    $users->load(['badges', 'likes', 'comments', 'posts']);

                    return response()->json([
                        'data' => $users,
                        'next_badge' => $this->nextBadge($request->user())
                    ], 200);
                } else {
                    return response()->json([
                        'message' => "no records found",
                    ], 200);
                }
            }

            if (!is_null($point) && !is_null($type)) {
                $users = $users->filter(function (object $user, int $key) use ($point, $type) {
                    return ($user->points = $point && $user->badges->where('name', $type));
                });

                if ($users->isNotEmpty()) {
                    $users->load(['badges', 'likes', 'comments', 'posts']);

                    return response()->json([
                        'data' => $users,
                        'next_badge' => $this->nextBadge($request->user())
                    ], 200);
                } else {
                    return response()->json([
                        'message' => "no records found",
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function nextBadge($user)
    {
        $badges = count($user->badges);

        if ($badges === 0) {
            return "Beginner";
        }

        if ($badges === 1) {
            return "Top Fan";
        }

        if ($badges === 2) {
            return "Super Fan";
        }
    }
}