@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.sites.title')</h3>
    <p>
        <a href="{{ route('admin.sites.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($sites) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>@lang('global.sites.fields.username')</th>
                        <th>@lang('global.sites.fields.sitecode')</th>
                        <th>@lang('global.sites.fields.province')</th>
                        <th>@lang('global.sites.fields.name')</th>
                        <th>@lang('global.sites.fields.site')</th>
                       
                        <th>&nbsp;</th>

                    </tr>
                </thead>
                
                <tbody>
                    @if (count($sites) > 0)
                        @foreach ($sites as $site)
                            <tr data-entry-id="{{ $site->id }}">
                                <td></td>
                                <td>{{ $site->username }}</td>
                                <td>{{ $site->sitecode }}</td>
                                <td>{{ $site->province }}</td>
                                <td>{{ $site->name }}</td>
                                <td>{{ $site->site }}</td>
                               
                                <td>
                                    <a href="{{ route('admin.sites.edit',[$site->id]) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                        'route' => ['admin.sites.destroy', $site->id])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.sites.mass_destroy') }}';
    </script>
@endsection