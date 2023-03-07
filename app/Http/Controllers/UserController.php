<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Mail\EmailRegistration;
use Illuminate\Support\Facades\Mail;
use DB;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::paginate();

        return view('user.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        $categories = Category::pluck('name','id');
        $countries = UserController::getCountries();
        return view('user.create', compact('user','categories','countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(User::$rules);
        $user = User::create($request->all());
        $data = [];

        // Envio de Email para los usuarios Registrados
        UserController::sendEmailRegister($request->input('email'),$request->input('name'),$data);

        // Envio de Email para el adminstrador
        $email = env('ADMIN_EMAIL');
        $users = DB::table('users')->select(DB::raw('count(*) as user_count, country'))->groupBy('country')->get();
        $data = json_decode(json_encode($users),true);

        UserController::sendEmailAdmin($email ,$data);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $categories = Category::pluck('name','id');
        $countries = UserController::getCountries();
        return view('user.edit', compact('user','categories','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'category_id'=> 'required',
            'name' => 'required|regex:/^[a-zA-Z ]+$/|max:100|min:5',
            'lastname' => 'required|regex:/^[a-zA-Z ]+$/|max:100',
            'identification' => 'required|unique:users,identification,'.$user->id . ',id',
            'email' => 'required|max:150|email|unique:users,email,'.$user->id . ',id',
            'country' => 'required',
            'address' => 'required|max:180',
            'mobile' => 'required|numeric|min:10',
          ];
        request()->validate($rules);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function getCountries(){
        $url = env('URL_COUNTRIES_API', 'http://127.0.0.1');
        $response = Http::get($url.'/subregion/america?fields=name');
        $data = $response->json();

        $countries = array();
        foreach($data as $item){
            $countries[$item['name']['common']]= $item['name']['common'];
        }
        return $countries;
    }

    public function sendEmailRegister($email,$name,$data){
        Mail::send('emails.usersRegistration', $data, function ($message) use($email,$name){
            $message->from('noreply@prueba.com', 'Prueba Email');
            $message->to($email, $name);
            $message->subject('Bienvenido a prueba Laravel');
        });
    }

    public function sendEmailAdmin($email,$data){
        Mail::send('emails.adminEmail', compact('data'), function ($message) use($email){
            $message->from('noreply@prueba.com', 'Prueba Email Admin');
            $message->to($email);
            $message->subject('Usuarios por pais');
        });
    }
}
