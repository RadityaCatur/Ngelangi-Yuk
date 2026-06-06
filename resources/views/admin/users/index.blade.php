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
        /* Memberi sedikit jarak antara tombol Tambah dan tombol Copy */
        .dt-buttons .btn-success {
            margin-right: 15px !important;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header" style="font-weight: bold; font-size: 1.25rem; color: #2c3e50; border-bottom: 2px solid #f1f3f5;">
            {{ trans('global.list') }} {{ trans('cruds.user.title_singular') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                {{ trans('cruds.user.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.username') }}
                            </th>
                            <th>
                                {{ trans('cruds.user.fields.roles') }}
                            </th>
                            <th>
                                {{ trans('global.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                            <tr data-entry-id="{{ $user->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $user->id ?? '' }}
                                </td>
                                <td>
                                    {{ $user->name ?? '' }}
                                </td>
                                <td>
                                    {{ $user->username ?? '' }}
                                </td>
                                <td>
                                    @if($user->role())
                                        <span class="badge badge-info">{{ $user->role()->title }}</span>
                                    @endif
                                </td>
                                <td>
                                    @can('user_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.users.show', $user->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('user_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.users.edit', $user->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan

                                    @can('user_delete')
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            
            // Memasukkan Tombol "Tambah Pengguna" ke dalam array dtButtons
            @can('user_create')
                let createButton = {
                    text: '<i class="fas fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}',
                    className: 'btn-success',
                    action: function (e, dt, node, config) {
                        window.location.href = "{{ route('admin.users.create') }}";
                    }
                }
                // unshift untuk menaruh tombol di urutan paling awal (sebelum tombol Copy)
                dtButtons.unshift(createButton)
            @endcan

            @can('user_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.users.massDestroy') }}",
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                            return $(entry).data('entry-id')
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
                            text: `Anda akan menghapus ${ids.length} pengguna terpilih. Data tidak dapat dikembalikan!`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f86c6b', // Merah pastel baru
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
                                    Swal.fire('Terhapus!', 'Pengguna berhasil dihapus.', 'success').then(() => {
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

            // Custom DOM Layout untuk memindahkan posisi elemen
            $.extend(true, $.fn.dataTable.defaults, {
                order: [[1, 'asc']],
                pageLength: 25,
                dom: '<"row align-items-center mb-3"<"col-md-8"B><"col-md-4"f>>' + // Atas: Buttons (Kiri), Search (Kanan)
                     '<"row"<"col-sm-12"t>>' + // Tengah: Table
                     '<"row align-items-center mt-3"<"col-md-6 d-flex align-items-center"li><"col-md-6"p>>', // Bawah: Length & Info (Kiri, berdampingan), Pagination (Kanan)
            });
            
            $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            // SCRIPT INTERCEPTOR: Merubah konfirmasi Hapus (Single Row) menjadi SweetAlert
            $('.datatable-User').on('click', '.btn-danger[type="submit"]', function(e) {
                e.preventDefault(); // Hentikan form agar tidak langsung submit
                let form = $(this).closest('form');
                form.removeAttr('onsubmit'); // Bersihkan attribute onsubmit bawaan HTML

                Swal.fire({
                    title: 'Hapus Pengguna?',
                    text: "Pengguna yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f86c6b', // Merah pastel baru
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
        })
    </script>
@endsection