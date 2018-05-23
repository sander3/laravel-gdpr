<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Dialect\Gdpr\Http\Requests\GdprDownload;

class GdprController extends Controller
{
    /**
     * Download the GDPR compliant data portability JSON file.
     *
     * @param  \Dialect\Package\Gdpr\Http\Requests\GdprDownload  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function download(GdprDownload $request)
    {
        $credentials = [
            $request->user()->getAuthIdentifierName() => $request->user()->getAuthIdentifier(),
            'password'                                => $request->input('password'),
        ];

        abort_unless(Auth::attempt($credentials), 403);

        return response()->json(
            $request->user()->portable(),
            200,
            [
                'Content-Disposition' => 'attachment; filename="user.json"',
            ]
        );
    }

    /**
     * Shows The GDPR terms to the user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTerms()
    {
        return view('users.gdpr');
    }

    /**
     * Saves the users acceptance of terms and the time of acceptance.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function termsAccepted()
    {
        $user = Auth::user();

        $user->update([
            'accepted_gdpr' => true,
        ]);

        return redirect()->to('/');
    }

	/**
	 * Saves the users denial of terms and the time of denial.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function termsDenied()
	{
		$user = Auth::user();

		$user->update([
			'accepted_gdpr' => false,
		]);

		return redirect()->to('/');
	}

	/**
	 * Anonymizes the user and sets the boolean.
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function anonymize($id) {
		$user = User::findOrFail($id);

		$user->anonymize();

		$user->update([
			'isAnonymized' => true
		]);

		return redirect()->back();
	}
}
