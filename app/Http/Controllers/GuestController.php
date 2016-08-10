<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use App\Book;
use Illuminate\Support\Facades\Auth;
use Entrust;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


class GuestController extends Controller
{
    //

    public function home(){
        return view('auth.login');
    }


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


/*	 public function books(Request $request, Builder $htmlBuilder)
    {
        //
        if ($request->ajax()) {
            $books = Book::select(['id','title']);
            return Datatables::of($books)
                ->addColumn('action', function($book){
                    return view('datatable._action', [
                        'model'           => $book,
                        'form_url'        => route('books.destroy', $book->id),
                        'confirm_message' => 'Yakin mau menghapus ' . $book->title . '?'
                    ]);
                })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'title', 'name'=>'title', 'title'=>'Judul'])
            ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false]);

        return view('guest.tmbhbook')->with(compact('html'));
    }
*/
     public function create()
    {
        //
        return view('guest.create');
    }

     public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title'     => 'required|unique:books,title',
        //    'user_id'   => 'required|exists:users,id',
          //  'cover'     => 'image|max:2048',
            'file'     => 'file|max:200480',
           // 'description' => 'required'
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

        return redirect()->route('books.create');
    }

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
        return redirect()->back();
    }


    public function edit($id){
         $book = Book::find($id);
        return view('guest.edit')->with(compact('book'));

    }

        //
    public function update(UpdateBookRequest $request, $id){

        $book = Book::find($id);
        if(!$book->update($request->all())) return redirect()->back();

        if ($request->hasFile('cover')) {
            $filename = null;
            $uploaded_cover = $request->file('cover');
            $extension = $uploaded_cover->getClientOriginalExtension();

            // membuat nama file random dengan extension
            $filename = md5(time()) . '.' . $extension;
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';

            // memindahkan file ke folder public/img
            $uploaded_cover->move($destinationPath, $filename);

            // hapus cover lama, jika ada
            if ($book->cover) {
                $old_cover = $book->cover;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'img'
                    . DIRECTORY_SEPARATOR . $book->cover;

                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // File sudah dihapus/tidak ada
                }
            }

            // ganti field cover dengan cover yang baru
            $book->cover = $filename;
            $book->save();
        }
        if ($request->hasFile('file')) {
            $uploaded_file = $request->file('file');

            // mengambil extension file
            $extension = $uploaded_file->getClientOriginalExtension();

            // membuat nama file random dengan extension
            $filename = time() . '.' . $extension;

            // memindahkan file ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'file';
            $uploaded_file->move($destinationPath, $filename);

            if ($book->file) {
                $old_file = $book->file;
                $filepath = public_path() . DIRECTORY_SEPARATOR . 'file'
                    . DIRECTORY_SEPARATOR . $book->file;

                try {
                    File::delete($filepath);
                } catch (FileNotFoundException $e) {
                    // File sudah dihapus/tidak ada
                }
            }

            // mengisi field cover di book dengan filename yang baru dibuat
            $book->file = $filename;
            $book->save();
        }

        Session::flash("flash_notification", [
            "level"=>"success",
            "message"=>"Berhasil menyimpan $book->title"
        ]);
    }


     public function download($id){
        $book = Book::find($id);
        $file = $book->file;
        $filepath = public_path() . DIRECTORY_SEPARATOR . 'file'
            . DIRECTORY_SEPARATOR . $book->file;
        return response()->download($filepath);
    }



     public function show($id)
    {
        //
        $buku = Book::find($id);
        return view('books.show', compact('buku', 'slug'));
  //      return '<a class="btn btn-xs btn-primary" href="'.route('books.download',$book->id).'">Download</a>';
    }

}

