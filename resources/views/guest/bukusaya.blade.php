@extends('dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table class="table">
              <tbody>
                <tr>
                  <td class="text-muted">Buku saya {{Auth::user()->name}}</td>
                  <td>
                     <ul>
                     
                      @foreach($bukusaya as $book)
                        <tr>
                            <td>{{$book->title}}</td>
                            
                            
                            <td>{{$book->created_at}}</td>

                            <td>
                            <a href="{{route('books.edit', ['book'=>$book->id])}}" class="btn btn-info btn-xs">Edit</a>

                            <form onclick="return confirm('Yakin hapus?')" style="display: inline;" action="{{url('books/'.$book->id)}}" method="post" accept-charset="utf-8">
                    {!! Form::hidden('_method', 'delete') !!}
                    {!!csrf_field()!!}
                    <input type="submit" class="btn btn-danger btn-xs" name="delete" value="Delete">
                    </form></td>
                        </tr>
                        @endforeach
                  
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection