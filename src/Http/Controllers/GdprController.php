<?php

namespace Soved\Laravel\Gdpr\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Soved\Laravel\Gdpr\Events\GdprDownloaded;
use Soved\Laravel\Gdpr\Http\Requests\GdprDownload;

class GdprController extends Controller
{
    /**
     * Download the GDPR compliant data portability JSON file.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(GdprDownload $request)
    {
        if (! $this->validateRequest($request)) {
            return $this->sendFailedLoginResponse();
        }

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

    /**
     * Validate the request.
     *
     * @return bool
     */
    protected function validateRequest(FormRequest $request)
    {
        if (config('gdpr.re-authenticate', true)) {
            return $this->hasValidCredentials($request);
        }

        return Auth::check();
    }

    /**
     * Validate a user's credentials.
     *
     * @return bool
     */
    protected function hasValidCredentials(FormRequest $request)
    {
        $credentials = [
            $request->user()->getAuthIdentifierName() => $request->user()->getAuthIdentifier(),
            'password'                                => $request->input('password'),
        ];

        return Auth::validate($credentials);
    }

    /**
     * Get the failed login response.
     *
     * @return void
     */
    protected function sendFailedLoginResponse()
    {
        abort(403, 'Unauthorized.');
    }
}
