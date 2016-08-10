@extends('layouts.dashboard')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href="{{ url('/home') }}">Dashboard</a></li>
          <li class="active">Detail {{ $buku->title }}</li>
        </ul>
        <div class="panel panel-default">
          <div id="all">
  <div class="container">
    <div class="row" id="productMain">
      <div class="col-sm-6">
        <div id="mainImage">
          <img src="{{asset('img/'.$buku->cover)}}" width="250" height="250" alt="" class="img-responsive">
        </div>
      </div>
      
      <div class="col-sm-6">
        <div class="box" id="details">
          
          <h3>Ebook detail</h3>
          <h4>{{ $buku->title }}</h4>
          
          <blockquote>
            <p><em>{{ $buku->description }}</em></p>
          </blockquote>
          <a class="btn btn-xs btn-primary" href="{{url('/books/'.$buku->id.'/download')}}">Download</a>
          <hr>
          
        </div>
      </div>
    </div>
  </div>
</div>
<table>
  <tr></tr>
</table>



@endsection
