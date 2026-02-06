@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">View Table</h3>
   

    <div class="panel panel-default">
        <div class="panel-heading">
           View Tables
        </div>

        <div class="panel-body table-responsive">
            
        <table class="table table-bordered table-striped dt-select">
                <thead>
                    <tr>
                        <th>View</th>
                        <th>Allowed</th>
                       
                    </tr>
                </thead>
                <tbody>
                      @if (count($tables) > 0)
                        @foreach ($tables as $table)
                        <tr >
                            <td>{{$table->TABLE_NAME}} </td>
                            <td><input type="checkbox" name="tbl" value="tbl" > </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <br>
            <p>
                 <a href="{{ route('admin.tables.update',1) }}" class="btn btn-success">Update</a>
            </p>
        </div>
    </div>
@endsection
