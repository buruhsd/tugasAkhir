<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
  {!! Form::label('title', 'Judul', ['class'=>'col-md-2 control-label']) !!}
  <div class="col-md-4">
    {!! Form::text('title', null, ['class'=>'form-control']) !!}
    {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
  </div>
</div>


<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
  {!! Form::label('file', 'File', ['class'=>'col-md-2 control-label']) !!}
  <div class="col-md-4">
    {!! Form::file('file') !!}
    @if (isset($book) && $book->file)
      <p>
      {!! Html::image(asset('file/'.$book->file), null, ['class'=>'form-control']) !!}
      </p>
    @endif
    {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group{{ $errors->has('cover') ? ' has-error' : '' }}">
  {!! Form::label('cover', 'Cover', ['class'=>'col-md-2 control-label']) !!}
  <div class="col-md-4">
    {!! Form::file('cover') !!}
    @if (isset($book) && $book->cover)
      <p>
      {!! Html::image(asset('img/'.$book->cover), null, ['class'=>'img-rounded']) !!}
      </p>
    @endif
    {!! $errors->first('cover', '<p class="help-block">:message</p>') !!}
  </div>
</div>


<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
  {!! Form::label('description', 'Deskripsi', ['class'=>'col-md-2 control-label']) !!}
  <div class="col-md-4">
    {!! Form::textArea('description', null, ['class'=>'form-control']) !!}
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
  </div>
</div>

<div class="form-group">
  <div class="col-md-4 col-md-offset-2">
    {!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!}
  </div>
</div>
