<?php

namespace App\Http\Controllers;

use App\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    public function saveToDatabase(Request $request)
    {
        $randomStr = Str::uuid(Str::random(4));
        $sign = base64_decode($request->signature);
        $file = Storage::disk('public')->put('signature/' . $randomStr . '.png', $sign);

        $signature = Signature::create([
            'user_id' => 1,
            'image_url' => Storage::disk('public')->url('signature/' . $randomStr . '.png')
        ]);

        return response()->json($signature, 200);
    }
}
