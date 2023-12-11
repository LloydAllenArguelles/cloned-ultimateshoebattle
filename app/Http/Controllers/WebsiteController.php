<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ShoeReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function main() {
        return view('pages.website.landing');
    }
    
    public function home() {
        return view('pages.website.home');
    }
    
    public function comparison() {
        return view('pages.website.comparison');
    }

    public function inDemand() {
        return view('pages.website.in-demand');
    }

    public function mostSearched() {
        return view('pages.website.most-searched');
    }

    public function mostViewed() {
        return view('pages.website.most-viewed');
    }

    public function reviews() {
        return view('pages.website.reviews');
    }

    public function stats() {
        return view('pages.website.stats');
    }

    public function storeReviews(Request $request) {
        Review::create([
            'name' => $request->name,
            'rate' => $request->rate,
            'review' => $request->review,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return ['status' => 200, 'message' => 'success'];
    }

    public function getReviews() {
        $reviews = Review::orderBy('created_at', 'DESC')
        ->get();
        $average = DB::table('reviews')->average('rate');

        return [
            'data' => $reviews,
            'average' => $average,
            'status' => 200,
            'message' => 'success'
        ];
    }

    public function getAverageRating(Request $request) {
        $data = [];
        $ids = $request->ids;

        for ($i = 0; $i < count($request->ids); $i++) {
            $average = DB::table('shoe_reviews')
            ->where('shoe_id', $ids[$i])
            ->average('rate');

            $data[$i]['id'] = $ids[$i];
            $data[$i]['average'] = $average;
        }

        return [
            'data' => $data,
            'status' => 200,
            'message' => 'success'
        ];
    }

    public function getShoeReviews(Request $request) {
        $reviews = ShoeReview::where('shoe_id', $request->id)
        ->where('shoe_name', $request->name)
        ->where('shoe_brand', $request->brand)
        ->orderBy('created_at', 'DESC')
        ->limit(6)
        ->get();

        $num_reviews = ShoeReview::where('shoe_id', $request->id)
        ->where('shoe_name', $request->name)
        ->where('shoe_brand', $request->brand)
        ->count();

        $average = DB::table('shoe_reviews')
        ->where('shoe_id', $request->id)
        ->where('shoe_name', $request->name)
        ->where('shoe_brand', $request->brand)
        ->average('rate');

        return [
            'data' => $reviews,
            'average' => $average,
            'num_reviews' => $num_reviews,
            'status' => 200,
            'message' => 'success'
        ];
    }

    public function getShoeReviewsComparison(Request $request) {
        $data = [];
        $shoes = $request->shoes;

        for ($i = 0; $i < count($shoes); $i++) {
            $data[$i]['id'] = $shoes[$i]['id'];

            $data[$i]['reviews'] = ShoeReview::where('shoe_id', $shoes[$i]['id'])
            ->where('shoe_name', $shoes[$i]['shoeName'])
            ->where('shoe_brand', $shoes[$i]['brand'])
            ->orderBy('created_at', 'DESC')
            ->limit(4)
            ->get();

            $data[$i]['num_reviews'] = ShoeReview::where('shoe_id', $shoes[$i]['id'])
            ->where('shoe_name', $shoes[$i]['shoeName'])
            ->where('shoe_brand', $shoes[$i]['brand'])
            ->count();

            $data[$i]['average'] = DB::table('shoe_reviews')
            ->where('shoe_id', $shoes[$i]['id'])
            ->where('shoe_name', $shoes[$i]['shoeName'])
            ->where('shoe_brand', $shoes[$i]['brand'])
            ->average('rate');
        }

        return [
            'data' => $data,
            'status' => 200,
            'message' => 'success'
        ];
    }

    public function shoeReviews(Request $request) {
        ShoeReview::create([
            'name' => $request->name,
            'rate' => $request->rate,
            'review' => $request->review,
            'shoe_id' => $request->shoe_id,
            'shoe_name' => $request->shoe_name,
            'shoe_brand' => $request->shoe_brand
        ]);
    }
}
