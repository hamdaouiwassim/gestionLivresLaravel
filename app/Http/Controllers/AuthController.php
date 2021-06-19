<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Parrainage;
use Auth;
use Illuminate\Support\Carbon;
use Validator;
use Hash;
class AuthController extends Controller
{
    //
    public function register(Request $request){

     
        $validatedData = Validator::make($request->all(),[
            'nom' => 'required|string|max:25',
            'prenom' => 'required|string|max:25',
            'adresse' => 'required|string|max:25',
            'email' => 'required|string|email',
            'password' => 'required|string',
          
        ]);

        
        if ($validatedData->fails())
        {
            return response()->json($validatedData->errors(), 201);
            //return redirect()->back()->withErrors();
        }
        

  
            $user = new User();
            $user->name = $request->prenom.' '.$request->nom ;
            $user->email = $request->email;
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->adresse = $request->adresse;
            $user->password = bcrypt($request->password);
    
            if ( $user->save() ){
                    return response()->json([
                        'message' => 'Utilisateur cree avec succes',
                        'status_code' => 200
    
                    ], 200);
            }else{
                return response()->json([
                    'message' => 'Creation echouee',
                    'status_code' => 500
    
                ], 500);
    
            }



      

       

    }

    public function login(Request $request){
        $validatedData = Validator::make($request->all(),[
         
            'email' => 'required|string|email',
            'password' => 'required|string',
          
        ]);

        
        if ($validatedData->fails())
        {
            return response()->json($validatedData->errors(), 201);
            //return redirect()->back()->withErrors();
        }
        

       if (!Auth::attempt(['email' => $request->email, 'password' => $request->password]) ){
           return response()->json([
               'message' => 'Invalid username/password',
               'status_code' => 401
           ], 401);
       }
       //$user = $request->user();
       $user = Auth::user();
       //return response()->json($user, 200);

       if ( $user->role == "admin"){
        
            $tokenData = $user->createToken('Personal Acces Token', ['admin']);
            //return response()->json($tokenData, 200);
       }else{
            $tokenData = $user->createToken('Personal Acces Token', ['user']);
       }

       $token = $tokenData->token;
       
       if ($request->remember_me ){
           $token->expires_at = Carbon::now()->addWeeks(1);

       }

       if ($token->save()){
           return response()->json([
               'user' => $user ,
               'access_token' => $tokenData->accessToken,
               'token_type' => 'Bearer',
               'token_scope' => $tokenData->token->scopes[0],
               'exipres_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString(),
               'status_code' => 200
           ], 200);

       }else{
           return response()->json([
               'message'=> 'Erreur d\'authentification ',
               'status_code' => 500

           ], 500);
       }

    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response()->json([
            'message'=> 'utilisaateur deconnecte ',
               'status_code' => 200

        ], 200);


    }
    public function profile(Request $request){
        if ($request->user()){
            return response()->json($request->user(), 200);
        }
        return response()->json([
            'message'=> 'utilisateur non connectee ',
               'status_code' => 500

        ], 500);
        


    }
    public function destroy($iduser){
        
        foreach( User::find($iduser)->comments as $c ){
            $c->delete();
        }
        foreach( User::find($iduser)->posts as $p ){
            $p->delete();
        }
        User::find($iduser)->delete();
        return response()->json(['message'=>'Utilisateur supprimer'], 200);

    }
    public function Update(Request $request){
        
       
        $user = Auth::user();
        $user->name = $request->name ;
        $user->nom = $request->nom ;
        $user->prenom = $request->prenom ;
        $user->adresse = $request->adresse ;

        if( $request->file('avatar') ){
            $avatar = $request->file('avatar');
            $newavatarName = uniqid().'.'.$avatar->getClientOriginalExtension();
            //Move Uploaded File
            $destinationPath = 'uploads/avatars/';
            $avatar->move($destinationPath,$newavatarName);
            $user->avatar =   $newavatarName;

        }
        $user->update();


        return response()->json($user, 200);

    }
    public function passwordChange(Request $request){

        Auth::user()->password = Hash::make($request->password);
        Auth::user()->update();
        return response()->json(Auth::user(), 200);

    }



}
