<div class="form-group{{ $errors->has('cover') ? ' has-error' : '' }}">
  {!! Form::label('cover', 'Jumlah', ['class'=>'col-md-2 control-label']) !!}
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


<div class="form-group">
  <div class="col-md-4 col-md-offset-2">
    {!! Form::submit('Simpan', ['class'=>'btn btn-primary']) !!}
  </div>
</div>
