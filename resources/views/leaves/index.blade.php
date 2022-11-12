@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Leaves</h2>
            </div>
            <div class="pull-right">
                @can('leave-create')
                <a class="btn btn-success" href="{{ route('leaves.create') }}"> Create New leave </a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Reason</th>
            <th>Information</th>
            <th>Type</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
	    @foreach ($leaves as $leave)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $leave->reason }}</td>
	        <td>{{ $leave->information }}</td>
            <td>{{ $leave->type->type }}</td>
            <td>{{ $leave->status }}</td>
	        <td>
                <form action="{{ route('leaves.destroy',$leave->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('leaves.show',$leave->id) }}">Show</a>
                    @can('leave-edit')
                    <a class="btn btn-primary" href="{{ route('leaves.edit',$leave->id) }}">Edit</a>
                    @endcan


                    @csrf
                    @method('DELETE')
                    @can('leave-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan
                </form>
	        </td>
	    </tr>
	    @endforeach
    </table>

    {!! $leaves->links() !!}

@endsection
