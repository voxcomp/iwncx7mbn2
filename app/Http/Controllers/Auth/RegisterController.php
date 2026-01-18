<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Registrant;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $validationFactory;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Http\Factories\ValidationFactory $validationFactory)
    {
        $this->middleware('guest');
        $this->validationFactory = $validationFactory;
    }

    /**
     * Displays a validation message to the user.
     */
    public function needValidation(): View
    {
        return view('auth.validation');
    }

    /**
     * Show page after registration about validation step.
     *
     * @return \Illuminate\Http\Response
     */
    public function email()
    {
        if (auth()->check()) {
            return redirect('home');
        }

        return view('pages.registration');
    }

    /**
     * Get a validator for an incoming registration request step 1.
     */
    protected function validator_step1(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'fname' => 'required|string|max:50',
            'fname' => 'required|string|max:75',
            'username' => 'required|unique:users|string|min:5|max:20',
            'email' => 'required|string|email|max:150',
            'password' => 'required|string|min:6|max:25|confirmed',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator_step1($request->all())->validate();
        $data = [
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ];
        event(new Registered($user = $this->create($data)));

        $this->validationFactory->sendValidationMail($user);

        return \Redirect::route('login')->with('validationMessage', true);
    }

    public function resendValidation(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255|exists:users',
        ]);

        $user = User::where('username', $request->username)->first();
        $this->validationFactory->sendValidationMail($user, true);

        return \Redirect::route('login')->with('validationMessage', true);
    }

    /**
     * Handle a registration request for the application.
     */
    public function validateUser(string $token): RedirectResponse
    {
        if ($user = $this->validationFactory->validateUser($token)) {
            // add user to mailchimp

            auth()->login($user);

            return redirect('home')->with('message', __('auth.validationConfirm'));
        }
        abort(404);
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'fname' => 'required|string|max:50',
            'fname' => 'required|string|max:75',
            'username' => 'required|unique:users|string|min:5|max:20',
            'email' => 'required|string|email|max:150',
            'password' => 'required|string|min:6|max:25|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data): User
    {
        $user = User::create([
            'fname' => $data['fname'],
            'lname' => $data['lname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Registrant::where('email', $user->email)->update(['user_id' => $user->id]);
        if (empty($user->address)) {
            $registrant = Registrant::orderBy('created_at', 'DESC')->where('user_id', $user->id)->first();
            if (! is_null($registrant)) {
                $user->update([
                    'phone' => $registrant->phone,
                    'address' => $registrant->address,
                    'city' => $registrant->city,
                    'state' => $registrant->state,
                    'zip' => $registrant->zip,
                ]);
            }
        }

        return $user;
    }
}
