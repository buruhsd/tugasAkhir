<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Role;
use App\User;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Session;

class MembersController extends Controller
{
    //

    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $members = Role::where('name', 'member')->first()->users;
            return Datatables::of($members)
                ->addColumn('action', function($member){
                    return view('datatable._member-action', compact('member'));
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'name', 'name'=>'name', 'title'=>'Nama'])
            ->addColumn(['data' => 'email', 'name'=>'email', 'title'=>'Email'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('members.index', compact('html'));
    }

    public function show($id)
    {
       
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user->hasRole('member')) {
            $user->delete();
            Session::flash("flash_notification", [
                "level"=>"success",
                "message"=>"Member berhasil dihapus"
            ]);
        }

        return redirect()->route('admin.members.index');
    }
}
