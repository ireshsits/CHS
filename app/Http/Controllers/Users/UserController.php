<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repositories\Users\UserRepository;

class UserController extends Controller{
    protected $manageUserRepo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->manageUserRepo = new UserRepository();
    }

    public function getUsersForManagerSearch(Request $request)
    {
        return json_encode(array(
            "result" => "success",
            'items' => $this->manageUserRepo->getUsersForManagerSearch($request->all())
        ));
    }
    public function getAllUsers(Request $request) {
        return $this->manageUserRepo->getAllUsers($request->all());
    }
}
