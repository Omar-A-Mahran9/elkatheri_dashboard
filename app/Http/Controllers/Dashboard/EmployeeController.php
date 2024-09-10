<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_employees');

        if ($request->ajax())
        {
            $data = getModelData( model: new Employee() );

            return response()->json($data);
        }

        return view('dashboard.employees.index');
    }

    public function create()
    {
        $this->authorize('create_employees');
        $roles = Role::select('id','name_' . getLocale() )->get();

        return view('dashboard.employees.create',compact('roles'));
    }


    public function show(Employee $employee)
    {
        $this->authorize('show_employees');

        $roles = Role::select('id','name_' . getLocale() )->get();

        return view('dashboard.employees.show',compact('employee','roles'));
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update_employees');

        $roles = Role::select('id','name_' . getLocale() )->get();

        return view('dashboard.employees.edit',compact('employee','roles'));
    }

    public function store(Request $request)
    {

        $this->authorize('create_employees');

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'phone'     => ['required','numeric','unique:employees'],
            'password'  => ['required','string','min:6','max:255','confirmed'],
            'password_confirmation' => ['required','same:password'],
            'email'     => ['required','string', "email:rfc,dns",'unique:employees'],
            'roles'     => ['required','array','min:1'],
        ]);

        // $data['password'] = $request['phone'];

        $data['phone'] = convertArabicNumbers($data['phone']);
        $employee = Employee::create($data);

        $rolesAndDefaultOne = array_merge( $request['roles'] , [ "2" ] );

        $employee->roles()->attach( $rolesAndDefaultOne );

    }

    public function update(Request $request , Employee $employee)
    {
        $this->authorize('update_employees');

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required','numeric','unique:employees,phone,' . $employee['id'] ],
            'password' => ['nullable','exclude_if:password,null','string','min:6','max:255','confirmed'],
            'password_confirmation' => ['nullable','exclude_if:password_confirmation,null','same:password'],
            'email'    => ['required','string', "email:rfc,dns",'unique:employees,email,' . $employee['id'] ],
            'roles'    => ['required','array','min:1'],
        ]);
        $data['phone'] = convertArabicNumbers($data['phone']);
        $employee->update($data);

        $rolesAndDefaultOne = array_merge( $request['roles'] , [ "2" ] );

        $employee->roles()->sync( $rolesAndDefaultOne );
    }


    public function destroy(Request $request, Employee $employee)
    {
        $this->authorize('delete_employees');

        if($request->ajax())
        {
            $employee->delete();
        }
    }

    public function updateProfile(Request $request){

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required','numeric','unique:employees,phone,' . auth()->id() ,],
            'email'    => ['required','string', "email:rfc,dns",'unique:employees,email,' . auth()->id() ],
        ]);
        $data['phone'] = convertArabicNumbers($data['phone']);
        auth()->user()->update($data); 
        
    }
    public function updatePassword(Request $request){
        
        $data = $request->validate([
            'password' => ['required','string','min:6','max:255','confirmed'],
            'password_confirmation' => ['required','same:password'],
        ]);
        
        auth()->user()->update($data);

    }

}
