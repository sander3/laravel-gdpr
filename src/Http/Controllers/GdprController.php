<?php

namespace Soved\Laravel\Gdpr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Soved\Laravel\Gdpr\Events\GdprDownloaded;
use Soved\Laravel\Gdpr\Http\Requests\GdprDownload;

class GdprController extends Controller
{
    /**
     * Download the GDPR compliant data portability JSON file.
     *
     * @param  \Soved\Laravel\Gdpr\Http\Requests\GdprDownload  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(GdprDownload $request)
    {
        $credentials = [
            $request->user()->getAuthIdentifierName() => $request->user()->getAuthIdentifier(),
            'password'                                => $request->input('password'),
        ];

        abort_unless(Auth::attempt($credentials), 403);

        $data = $request->user()->portable();

        event(new GdprDownloaded($request->user()));

        // Backward compatible streamDownload() behavior

        return response()->json(
            $data,
            200,
            [
                'Content-Disposition' => 'attachment; filename="user.json"',
            ]
        );
    }
}
