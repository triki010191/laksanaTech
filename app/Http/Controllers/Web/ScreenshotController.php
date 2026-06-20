<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Support\LiveScreenshotUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScreenshotController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'url' => ['required', 'url', 'max:500'],
        ]);

        if ($validator->fails()) {
            abort(422, 'Invalid URL');
        }

        $targetUrl = $validator->validated()['url'];
        $path = 'screenshots/'.md5($targetUrl).'.img';

        if (Storage::disk('local')->exists($path)) {
            $cached = json_decode(Storage::disk('local')->get($path), true);

            if (is_array($cached) && isset($cached['body'], $cached['content_type'])) {
                return $this->imageResponse(
                    base64_decode($cached['body']),
                    $cached['content_type'],
                );
            }
        }

        foreach (LiveScreenshotUrl::externalProviders($targetUrl) as $providerUrl) {
            try {
                $response = Http::timeout(45)
                    ->withHeaders(['User-Agent' => 'LaksanaTech/1.0'])
                    ->get($providerUrl);

                if (! $response->successful()) {
                    continue;
                }

                $contentType = $response->header('Content-Type') ?? '';
                if (! str_starts_with($contentType, 'image/')) {
                    continue;
                }

                Storage::disk('local')->put($path, json_encode([
                    'body' => base64_encode($response->body()),
                    'content_type' => $contentType,
                ]));

                return $this->imageResponse($response->body(), $contentType);
            } catch (\Throwable) {
                continue;
            }
        }

        abort(502, 'Screenshot unavailable');
    }

    private function imageResponse(string $body, string $contentType): Response
    {
        return response($body, 200, [
            'Content-Type' => $contentType,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
