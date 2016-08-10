{!! Form::model($member, ['url' => route('admin.members.destroy', $member->id), 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => 'Yakin mau menghapus member ' . $member->name . '?'] ) !!}
     |   
    {!! Form::submit('Hapus', ['class'=>'btn btn-xs btn-danger']) !!}
{!! Form::close()!!}
