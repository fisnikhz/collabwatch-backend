<?php
namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\API\APIController;
use App\Models\User;
use Illuminate\Http\Request;

class FindUserController extends APIController
{
    public function __invoke(Request $request)
    {
        $name = $request->get('name');

        $users = User::where('name', 'like', '%' . $name . '%')->with('media')->get();

        return $this->respondWithSuccess($users);
    }
}
