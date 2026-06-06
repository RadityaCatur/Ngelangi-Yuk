@extends('layouts.admin')

@section('styles')
    <style>
        /* Styling untuk menyandingkan "Show entries" dan "Showing info" */
        .dataTables_length {
            margin-right: 15px;
            margin-bottom: 0 !important;
        }
        .dataTables_length label {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            font-weight: normal;
        }
        .dataTables_length select {
            margin: 0 10px;
        }
        .dataTables_info {
            padding-top: 0 !important;
            margin-bottom: 0 !important;
        }
        /* PERBAIKAN: Membuat semua tombol export & mass delete tidak terlalu kotak */
        .dt-buttons .btn {
            border-radius: 4px !important;
        }
        /* Memberi sedikit jarak antar tombol Tambah dan tombol Copy */
        .dt-buttons .btn-success {
            margin-right: 15px !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header" style="font-weight: bold; font-size: 1.25rem; color: #2c3e50; border-bottom: 2px solid #f1f3f5;">
            {{ trans('global.list') }} {{ trans('cruds.appointment.title_singular') }}
        </div>

        <div class="card-body">
            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Appointment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.employee') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.client') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.location') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.start_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.finish_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.services') }}
                        </th>
                        <th>
                            {{ trans('global.action') }}
                        </th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            
            // Memasukkan Tombol "Tambah Jadwal" ke dalam baris DataTables
            @can('appointment_create')
                let createButton = {
                    text: '<i class="fas fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.appointment.title_singular') }}',
                    className: 'btn-success',
                    action: function (e, dt, node, config) {
                        window.location.href = "{{ route('admin.appointments.create') }}";
                    }
                }
                dtButtons.unshift(createButton)
            @endcan

            @can('appointment_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.appointments.massDestroy') }}",
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            // SweetAlert untuk peringatan jika belum memilih data
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: '{{ trans('global.datatables.zero_selected') }}',
                                confirmButtonColor: '#019db2'
                            });
                            return
                        }

                        // SweetAlert untuk Konfirmasi Mass Delete
                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: `Anda akan menghapus ${ids.length} jadwal terpilih. Data tidak dapat dikembalikan!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545', // PERBAIKAN: Warna merah disamakan dengan tombol Bootstrap
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            backdrop: `rgba(0,0,0,0.5)`
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    headers: { 'x-csrf-token': _token },
                                    method: 'POST',
                                    url: config.url,
                                    data: { ids: ids, _method: 'DELETE' }
                                })
                                .done(function () { 
                                    Swal.fire('Terhapus!', 'Jadwal berhasil dihapus.', 'success').then(() => {
                                        location.reload();
                                    });
                                })
                                .fail(function() {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                                });
                            }
                        })
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.appointments.index') }}",
                columns: [
                    { data: 'placeholder', name: 'placeholder' },
                    { data: 'id', name: 'id' },
                    { data: 'employee_name', name: 'employee.name' },
                    { data: 'clients_name', name: 'client.name' },
                    { data: 'location', name: 'location' },
                    { data: 'start_time', name: 'start_time' },
                    { data: 'finish_time', name: 'finish_time' },
                    { data: 'services', name: 'services.name' },
                    { data: 'actions', name: '{{ trans('global.actions') }}' }
                ],
                order: [[1, 'asc']],
                pageLength: 25, // Set ke 25
                // Custom DOM Layout untuk memindahkan posisi elemen
                dom: '<"row align-items-center mb-3"<"col-md-8"B><"col-md-4"f>>' + // Atas: Buttons (Kiri), Search (Kanan)
                     '<"row"<"col-sm-12"t>>' + // Tengah: Table
                     '<"row align-items-center mt-3"<"col-md-6 d-flex align-items-center"li><"col-md-6"p>>', // Bawah: Length & Info (Kiri), Pagination (Kanan)
            };
            
            $('.datatable-Appointment').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            // SCRIPT INTERCEPTOR: Merubah konfirmasi Hapus (Single Row) menjadi SweetAlert
            $('.datatable-Appointment').on('click', '.btn-danger[type="submit"]', function(e) {
                e.preventDefault(); // Hentikan form agar tidak langsung submit (bypass onsubmit native html)
                let form = $(this).closest('form');
                form.removeAttr('onsubmit'); // Bersihkan attribute onsubmit bawaan

                Swal.fire({
                    title: 'Hapus Jadwal?',
                    text: "Jadwal yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f86c6b', // PERBAIKAN: Warna merah disamakan dengan tombol Bootstrap
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    backdrop: `rgba(0,0,0,0.5)`
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Lanjutkan submit jika User klik "Ya"
                    }
                });
            });
        });
    </script>
@endsection