@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.appointment.title') }}
        </div>

        <div class="card-body">
            <div class="mb-2">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                {{ trans('cruds.appointment.fields.id') }}
                            </th>
                            <td>
                                {{ $appointment->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.appointment.fields.employee') }}
                            </th>
                            <td>
                                {{ $appointment->employee->name ?? '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.client') }}</th>
                            <td>{{ $appointment->client->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.appointment.fields.start_time') }}
                            </th>
                            <td>
                                {{ $appointment->start_time }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.appointment.fields.finish_time') }}
                            </th>
                            <td>
                                {{ $appointment->finish_time }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.appointment.fields.services') }}
                            </th>
                            <td>
                                @foreach($appointment->services->pluck('category')->unique() as $category)
                                    <span class="label label-info">- {{ $category }}</span><br>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @php
                    $user = auth()->user();
                    $isClient = $user->roles()->where('title', 'Murid')->exists();
                    $isEmployee = $user->roles()->where('title', 'Pelatih')->exists();
                    $isAdmin = $user->roles()->where('title', 'Admin')->exists();

                    $client = \App\Client::where('user_id', $user->id)->first();
                    $isOwned = $client && $appointment->client_id === $client->id;
                @endphp

                @if ($isAdmin)
                    <a style="margin-top:20px;" class="btn btn-success"
                        href="{{ route('admin.appointments.edit', $appointment->id) }}">
                        Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection