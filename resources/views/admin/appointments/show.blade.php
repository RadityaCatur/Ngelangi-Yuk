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
                            <th>{{ trans('cruds.appointment.fields.id') }}</th>
                            <td>{{ $appointment->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.employee') }}</th>
                            <td>{{ $appointment->employee->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.client') }}</th>
                            <td>{{ $appointment->client->name ?? '-' }}</td>
                        </tr>
                        {{-- DATA BARU: Tampilkan Lokasi --}}
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $appointment->location ?? 'Belum ditentukan' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.start_time') }}</th>
                            <td>{{ $appointment->start_time }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.finish_time') }}</th>
                            <td>{{ $appointment->finish_time }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.appointment.fields.services') }}</th>
                            <td>
                                @foreach($appointment->services->pluck('category')->unique() as $category)
                                    <span class="label label-info">- {{ $category }}</span><br>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Laporan Pelatih</th>
                            <td>{!! nl2br(e($appointment->comments)) ?: '<i>Belum ada laporan.</i>' !!}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <a class="btn btn-default" href="{{ url()->previous() }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                    
                    @if ($appointment->client_id)
                        <a class="btn btn-info" 
                           href="{{ route('admin.appointments.clientReports', $appointment->id) }}">
                            Lihat Rapor Murid
                        </a>
                    @endif

                    @php
                        $user = auth()->user();
                        $isAdmin = $user->roles()->where('title', 'Admin')->exists();
                        $isPelatih = DB::table('role_user')
                            ->join('roles', 'role_user.role_id', '=', 'roles.id')
                            ->where('role_user.user_id', $user->id)
                            ->whereRaw('LOWER(roles.title) = ?', ['pelatih'])
                            ->exists();

                        $canShowReportButton = $isPelatih && ($user->employee?->id == $appointment->employee_id);

                    @endphp

                    @if ($isAdmin)
                        <a class="btn btn-success" href="{{ route('admin.appointments.edit', $appointment->id) }}">
                            Edit
                        </a>
                    @endif

                    @if ($canShowReportButton )
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reportModal">
                            Isi / Ubah Laporan
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Isi Laporan Latihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="reportForm" method="POST"
                    action="{{ route('admin.appointments.updateReport', $appointment->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="comments" class="required">Laporan/Catatan untuk Murid</label>
                            <textarea class="form-control" name="comments" id="comments" rows="5"
                                required>{{ old('comments', $appointment->comments) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection