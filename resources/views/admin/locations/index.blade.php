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
        Daftar Lokasi Latihan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Location">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>ID</th>
                        <th>Nama Lokasi</th>
                        <th>Link Google Maps</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $key => $location)
                        <tr data-entry-id="{{ $location->id }}">
                            <td></td>
                            <td>{{ $location->id ?? '' }}</td>
                            <td>{{ $location->name ?? '' }}</td>
                            <td>
                                @if($location->URL)
                                    <a href="{{ $location->URL }}" target="_blank" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-map-marker-alt"></i> Buka Maps
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada link</span>
                                @endif
                            </td>
                            <td>
                                @can('location_edit')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.locations.edit', $location->id) }}">
                                        Edit
                                    </a>
                                @endcan

                                @can('location_delete')
                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Hapus">
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
        
        // Memasukkan Tombol Tambah ke dalam baris DataTables
        @can('location_create')
            let createButton = {
                text: '<i class="fas fa-plus"></i> Tambah Lokasi Baru',
                className: 'btn-success',
                action: function (e, dt, node, config) {
                    window.location.href = "{{ route('admin.locations.create') }}";
                }
            }
            dtButtons.unshift(createButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'asc' ]],
            pageLength: 25,
            dom: '<"row align-items-center mb-3"<"col-md-8"B><"col-md-4"f>>' + 
                 '<"row"<"col-sm-12"t>>' + 
                 '<"row align-items-center mt-3"<"col-md-6 d-flex align-items-center"li><"col-md-6"p>>',
        });
        
        let table = $('.datatable-Location:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

        // SCRIPT INTERCEPTOR: Merubah konfirmasi Hapus (Single Row) menjadi SweetAlert
        $('.datatable-Location').on('click', '.btn-danger[type="submit"]', function(e) {
            e.preventDefault(); // Hentikan form agar tidak langsung submit (bypass onsubmit native html)
            let form = $(this).closest('form');
            form.removeAttr('onsubmit'); // Bersihkan attribute onsubmit bawaan

            Swal.fire({
                title: 'Hapus Lokasi?',
                text: "Lokasi yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f86c6b',
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
