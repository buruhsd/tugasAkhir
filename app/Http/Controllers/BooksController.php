<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        //
        if ($request->ajax()) {
            $books = Book::select(['id','title']);
            return Datatables::of($books)
                ->addColumn('action', function($book){
                    return view('datatable._action', [
                        'model'           => $book,
                        'form_url'        => route('admin.books.destroy', $book->id),
                        'confirm_message' => 'Yakin mau menghapus ' . $book->title . '?'
                    ]);
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name'=>'title', 'title'=>'Judul'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('books.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         $this->validate($request, [
            'title'     => 'required|unique:books,title',
        //    'user_id'   => 'required|exists:users,id',
          //  'cover'     => 'image|max:2048',
            'file'     => 'file|max:200480',
            //'description' => 'required'
        ]);


        $book = Book::create([
            $request->except('file'),
            $request->except('cover'),
            'user_id' => Auth::user()->id,
            'title' =>$request-> input('title'),
            'description' => $request->input('description')
            ]);

        // isi field cover jika ada cover yang diupload
        if ($request->hasFile('file')) {
            $uploaded_file = $request->file('file');

            // mengambil extension file
            $extension = $uploaded_file->getClientOriginalExtension();

            // membuat nama file random dengan extension
            $filename = time() . '.' . $extension;

            // memindahkan file ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'file';
            $uploaded_file->move($destinationPath, $filename);

            // mengisi field cover di book dengan filename yang baru dibuat
            $book->file = $filename;
            $book->save();
        }
        if ($request->hasFile('cover')) {
            $uploaded_cover = $request->file('cover');

            // mengambil extension file
            $extension = $uploaded_cover->getClientOriginalExtension();

            // membuat nama file random dengan extension
            $filename = md5(time()) . '.' . $extension;

            // memindahkan file ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_cover->move($destinationPath, $filename);

            // mengisi field cover di book dengan filename yang baru dibuat
            $book->cover = $filename;
            $book->save();
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil menyimpan $book->title"
        ]);

        return redirect()->route('admin.books.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $book = Book::find($id);
        $cover = $book->cover;
        $file = $book->file;
        if(!$book->delete()) return redirect()->back();

        // handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        // hapus cover lama, jika ada
        if ($cover) {
            $old_cover = $book->cover;
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img'
                . DIRECTORY_SEPARATOR . $book->cover;

            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }

        if ($file) {
            $old_file = $book->file;
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'file'
                . DIRECTORY_SEPARATOR . $book->file;

            try {
                File::delete($filepath);
            } catch (FileNotFoundException $e) {
                // File sudah dihapus/tidak ada
            }
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Buku berhasil dihapus"
        ]);

        return redirect()->route('admin.books.index');
    }

    public function baca($id){
        $book = Book::find($id);
        $file = $book->file;
        $filepath = public_path() . DIRECTORY_SEPARATOR . 'file'
            . DIRECTORY_SEPARATOR . $book->file;
        return response::make(file_get_contents($filepath), 200, [
            'Content-Type'=>'application/pdf',
            'Content-Disposition'=>'inline;filename="'.$file.'"']);
    }


    public function download($id){
        $book = Book::find($id);
        $file = $book->file;
        $filepath = public_path() . DIRECTORY_SEPARATOR . 'file'
            . DIRECTORY_SEPARATOR . $book->file;
        return response()->download($filepath);
    }


    
}
