<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use App\User;
use Illuminate\Support\Facades\Auth;
use Entrust;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $books = Book::select(['id','title']);
            return Datatables::of($books)
                ->addColumn('action', function($book){
                 //  if (Entrust::hasRole('admin')) return'';
           //        return '<a class="btn btn-xs btn-primary" href="'.route('books.download',$book->id).'">Download</a>';
                   return '<a class="btn btn-xs btn-primary" href="'.route('books.show',$book->id).'">Detail</a>';
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name'=>'title', 'title'=>'Judul'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('guest.index')->with(compact('html'));
    }

    public function bukusaya()
    {
        $id = Auth::user()->id;
        $book = Book::where('user_id', '=', $id)->get();
        return view('guest.bukusaya', ['bukusaya'=>$book]);
    

    }
}
