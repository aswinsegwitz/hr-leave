@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Leave</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('leaves.all') }}"> Back </a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('failed'))
<div class="alert alert-danger">
    <p>{{ $message }}</p>
</div>
@endif

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> Something went wrong.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


{!! Form::model($leave, ['method' => 'PATCH','route' => ['leaves.update', $leave->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Remark</strong>
            {!! Form::textarea('remarks', null, array('required' => 'required', 'placeholder' => 'Remark','class' => 'form-control')) !!}
        </div>
    </div>

    <div class="col-sm-9" style="margin:auto">
        <div class="form-check form-check-inline">
            {{Form::radio('reason','approved',['class'=>'form-control'])}}
            <label class="form-check-label ml-2" for="inlineRadio1">Approved</label>
        </div>
        <div class="form-check form-check-inline">
            {{Form::radio('reason','rejected',['class'=>'form-control'])}}
            <label class="form-check-label ml-2" for="inlineRadio2">Rejected</label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}


@endsection
