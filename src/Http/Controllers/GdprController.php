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
     * @param  \Soved\Laravel\Gdpr\Http\Requests\GdprDownload  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(GdprDownload $request)
    {
        if (!$this->attemptLogin($request)) {
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
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Foundation\Http\FormRequest  $request
     * @return bool
     */
    protected function attemptLogin(FormRequest $request)
    {
        $credentials = [
            $request->user()->getAuthIdentifierName() => $request->user()->getAuthIdentifier(),
            'password'                                => $request->input('password'),
        ];

        return Auth::attempt($credentials);
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
