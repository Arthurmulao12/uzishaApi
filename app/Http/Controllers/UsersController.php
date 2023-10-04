<?php

namespace App\Http\Controllers;
use App\Models\affectation_users;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\usersenterprise;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index($enterprise_id)
    {
        $list=collect(User::join('usersenterprises as UE', 'users.id','=','UE.user_id')->where('UE.enterprise_id','=',$enterprise_id)->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userS = user::create($request->all());
        if(isset($request->enterprise_id) && !empty($request->enterprise_id)){
             //affect user to the Ese
                usersenterprise::create([
                    'user_id'=>$userS->id,
                    'enterprise_id'=>$request->enterprise_id
                ]);
        }

        $userSave=$this->getone($userS['id']);

        if(isset($request->level) && isset($request->department_id)){
            // verification si il existe un utilisatair de type admin deja affecter
            if ($request->level == 'chief') {

                $ifIsChief = DB::table('affectation_users')
                ->where('department_id','=', $request->department_id)
                ->where('level', '=', 'chief')
                ->get();

                if (count($ifIsChief) == 0) {
                    $departemetAffect = affectation_users::create(
                        ['user_id' => $userS['id'],
                        'level' => $request->level,
                        'department_id' => $request->department_id,
                    ]);
                    return [$userSave, $affected = 'succes'];
                }else {
                    return [$userSave, $affected ='error'];
                }
            }else{
                $departemetAffect = affectation_users::create(
                    ['user_id' => $userS['id'],
                    'level' => $request->level,
                    'department_id' => $request->department_id,
                ]);
                return [$userSave=$this->getone($userS['id']), $affected = 'succes'];
            }
        }else{
            return $userSave;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        // $usersent=User::leftjoin('affectation_users as A', 'users.id','=','A.user_id')
        // // ->leftjoin('departments as D', 'A.department_id','=','D.id')
        // // ->leftjoin('usersenterprises as UE', 'users.id','=','UE.user_id')
        // // ->leftjoin('enterprises as E', 'E.id','=','UE.enterprise_id')
        // ->where('users.id','=',$user->id)
        // ->get(['users.*', 'A.level'])[0];

        return $user;
    }

    public function getone($id){

        return User::leftjoin('affectation_users as A', 'users.id','=','A.user_id')
        ->leftjoin('departments as D', 'A.department_id','=','D.id')
        ->where('users.id', '=',$id)
        ->get(['D.department_name as department_name', 'D.id as department_id', 'users.*', 'A.level'])[0];

    }

    public function getuseraccess($id){

        $ouput=['user'=>'','type'=>'','access'=>'','can_validate'=>false];

        $user=User::leftjoin('affectation_users as A', 'users.id','=','A.user_id')
        ->leftjoin('departments as D', 'A.department_id','=','D.id')
        ->leftjoin('decision_teams as DC','users.id','=','DC.user_id')
        ->where('users.id', '=',$id)
        ->get(['D.department_name as department_name', 'D.id as department_id', 'users.*', 'A.level','DC.access']);

        $ouput['user']=$user;

        if($user[0]['department_name']){

            if($user[0]['level']=='chief'){
                $ouput['can_validate']=true;
            }

            $ouput['type']=$user[0]['level'];

        }elseif($user[0]['access']){

            $ouput['access']=$user[0]['access'];
            $ouput['type']='decision';

            if($user[0]['access']=='rw'){
                $ouput['can_validate']=true;
            }
        }else{

        }

        return $ouput;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        $user->update($request->all());
        return $this->show($user);
    }

    public function update2(Request $request, $id)
    {
        $user=User::find($id);
        $user->update($request->all());

        return User::leftjoin('affectation_users as A', 'users.id','=','A.user_id')
        ->leftjoin('departments as D', 'A.department_id','=','D.id')
        ->where('users.id','=',$id)
        ->get(['D.department_name as department_name', 'D.id as department_id', 'users.*', 'A.level'])[0];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        return user::destroy($user);
    }

    public function destroy2($id){
        $user=User::find($id);
        return $user->delete();
    }

    public function login(Request $request){

        $user=User::leftjoin('usersenterprises as UE', 'users.id','=','UE.user_id')->leftjoin('roles as R', 'users.permissions','=','R.id')
        ->where('users.user_name',$request->user_name)
        ->where('users.user_password','=',$request->user_password)->get(['users.*','UE.enterprise_id', 'permissions'=> 'R.*', 'id'=> 'users.id'])[0];
        return $user;
    }
}
