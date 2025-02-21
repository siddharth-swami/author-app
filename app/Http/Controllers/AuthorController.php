<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthorController extends Controller
{
    public function show($id)
    {
        return view('authors.show', compact('id'));
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'biography' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'place_of_birth' => 'required|string',
        ]);

        $validatedData['birthday'] = date('Y-m-d\TH:i:s.000\Z', strtotime($validatedData['birthday']));
        $response = Http::withToken(session('token'))->post(env('THIRD_PARTY_API_URL') . '/authors', $validatedData);
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Author added successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to add author', 'errors' => $response->json()], 422);
        }
    }

    public function destroy($id)
    {
        $apiUrl = env('THIRD_PARTY_API_URL') . "/authors/{$id}";
        $apiToken = session('token');
        $response = Http::withToken($apiToken)->delete($apiUrl);

        if ($response->successful()) {
            return response()->json(['message' => 'Author deleted successfully.'], 200);
        } else {
            return response()->json(['error' => 'Failed to delete author.'], $response->status());
        }
    }
}
