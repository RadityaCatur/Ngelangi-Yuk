@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.client.title') }}
        </div>

        <div class="card-body">
            <div class="mb-2">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.client.fields.id') }}
                            </th>
                            <td>
                                {{ $client->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.employee.fields.name') }}</th>
                            <td>{{ $client->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.employee.fields.username') }}</th>
                            <td>{{ $client->user->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.client.fields.phone') }}
                            </th>
                            <td>
                                {{ $client->phone }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Services
                            </th>
                            <td>
                                @foreach($client->services as $id => $services)
                                    <span class="label label-info label-many">{{ $services->name }}<br></span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.client.fields.kuota') }}
                            </th>
                            <td>
                                {{ $client->kuota }}
                            </td>
                        </tr>
                        <!-- TAMBAHAN BARU -->
                        <tr>
                            <th>
                                Masa Berlaku Kuota
                            </th>
                            <td>
                                {{ $client->kuota_valid_until ? \Carbon\Carbon::parse($client->kuota_valid_until)->translatedFormat('d F Y') : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex align-items-center mt-3">
                    <a class="btn btn-default" href="{{ url()->previous() }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    <a class="btn btn-primary ms-2" href="{{ route('admin.clients.reports', $client->id) }}">
                        Lihat Report
                    </a>
                </div>
            </div>

            <nav class="mb-3">
                <div class="nav nav-tabs">

                </div>
            </nav>
            <div class="tab-content">

            </div>
        </div>
    </div>
@endsection