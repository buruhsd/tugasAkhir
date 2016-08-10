@extends('layouts.app')
@include('layouts.menu')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href="{{ url('/home') }}">Dashboard</a></li>
          <li><a href="{{ url('/books') }}">Buku</a></li>
          <li class="active">Tambah Buku</li>
        </ul>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h2 class="panel-title">Create Cover</h2>
          </div>

          <div class="panel-body">            
                {!! Form::open(['url' => route('books.store'), 
                  'method' => 'post', 'files'=>'true', 'class'=>'form-horizontal']) !!}
                  @include('guest._form')
                {!! Form::close() !!}
          </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

