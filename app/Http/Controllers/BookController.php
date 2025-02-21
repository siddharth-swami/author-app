<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{
    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|integer',
            'title' => 'required|string',
            'release_date' => 'required|date',
            'description' => 'nullable|string',
            'isbn' => 'required|string',
            'format' => 'required|string',
            'number_of_pages' => 'required|integer|min:1',
        ]);

        $token = Session::get('token'); 
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(env('THIRD_PARTY_API_URL') . '/books', [
            'author' => ['id' => (int)$request->author_id],
            'title' => $request->title,
            'release_date' => $request->release_date,
            'description' => $request->description,
            'isbn' => $request->isbn,
            'format' => $request->format,
            'number_of_pages' => (int)$request->number_of_pages,
        ]);
        if ($response->successful()) {
            return response()->json($response->json(), $response->status());
        } elseif ($response->status() == 422 || $response->status() == 403) {
            return back()->withErrors($response->json()['errors'])->withInput();
        } else {
            return back()->with('error', 'Something went wrong. Please try again.');
        }
        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $token = Session::get('token'); 
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->delete(env('THIRD_PARTY_API_URL') . "/books/{$id}");
        return response()->json($response->json(), $response->status());
    }
}
