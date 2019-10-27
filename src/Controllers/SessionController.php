<?php
namespace TechlifyInc\LaravelRbac\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{

    /**
     * Destroy the session thereby logging out the user
     */
    public function destroy()
    {
        if (!Auth::check()) {
            return response([
                'message' => 'Invalid token or user already logged out. ',
                'success' => false
            ]);
        }
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
        ]);

        $accessToken->revoke();

        return response([
            'message' => 'User was logged out',
            'success' => true
        ]);
    }
}
