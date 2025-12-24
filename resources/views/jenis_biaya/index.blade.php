@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div
                        class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3" id="headerTitle">
                            Jenis dan Nominal Pembayaran <span id="titleYear" class="text-muted"></span>
                        </h6>
                        <div class="d-flex align-items-center gap-3 pe-3">
                            <label class="text-white mb-0">Angkatan :</label>
                            <div class="input-group input-group-static" style="width: 150px;">
                                <label class="form-label active">Tahun</label>
                                <input type="number" class="form-control tahun text-white">
                            </div>
                            <button id="Filter_angkatan" class="btn btn-info text-white px-4">
                                Lihat
                            </button>
                            <a href="/app/Jenis-biaya/create"class="btn btn-primary text-white px-4">Tambah Jenis
                                Bayar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="keuangan" class="table align-items-center table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Angkatan</th>
                                    <th>Kode Rekening</th>
                                    <th>Jenis Pembayaran</th>
                                    <th>Nominal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast fade hide p-2 mt-2 bg-white" role="alert" aria-live="assertive" id="warningToast"
            aria-atomic="true">
            <div class="toast-header border-0">
                <i class="material-symbols-rounded text-warning me-2">travel_explore</i>
                <span class="me-auto font-weight-bold">Sistem Akademik</span>
                <small class="text-body">Now</small>
                <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast" aria-label="Close"></i>
            </div>
            <div class="toast-body text-dark">
                "Maaf! Tidak ada data untuk ditampilkan".
            </div>
        </div>
    </div>


    <form action="" method="post" id="FormHapusBiaya">
        @method('DELETE')
        @csrf
    </form>
@endsection

@section('script')
    <script>
        //index
        $(document).ready(function() {
            let currentYear = new Date().getFullYear();

            $('.tahun').each(function() {
                $(this).val(currentYear);
                $(this).closest('.input-group-static').addClass('is-filled');
            });
            $('#titleYear').text(currentYear);

            const table = $('#keuangan').DataTable({
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6">>rt<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
                processing: true,
                serverSide: true,
                language: {
                    emptyTable: "Hello, Maaf! Data kosong"
                },
                ajax: {
                    url: '/app/Jenis-biaya',
                    data: function(d) {
                        d.tahun = $('.tahun').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: '5%'
                    },
                    {
                        data: 'angkatan',
                        name: 'angkatan',
                        className: 'text-start ps-4'
                    },
                    {
                        data: 'kode_akun',
                        name: 'kode_akun',
                        className: 'text-start'
                    },
                    {
                        data: 'nama_akun',
                        name: 'nama_akun',
                        className: 'text-start'
                    },
                    {
                        data: 'total_beban',
                        name: 'total_beban',
                        className: 'text-end pe-4'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: '15%',
                        className: 'text-center'
                    },
                ]
            });

            $('#Filter_angkatan').on('click', function() {
                const selectedYear = $('.tahun').val();
                $('#titleYear').text(selectedYear);

                table.ajax.reload();
            });

            $('#keuangan').on('xhr.dt', function(e, settings, json, xhr) {
                if (!json.data || json.data.length === 0) {
                    const toastEl = document.getElementById('warningToast');
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                }
            });

        });
        //hapus
        $(document).on('click', '.btnDelete', function(e) {
            e.preventDefault();

            var hapus_id = $(this).attr('data-id');
            var actionUrl = '/app/Jenis-biaya/' + hapus_id;

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data akan dihapus secara permanen dari aplikasi dan tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = $('#FormHapusBiaya');
                    $.ajax({
                        type: form.attr('method'),
                        url: actionUrl,
                        data: form.serialize(),
                        success: function(result) {
                            if (result.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: result.msg,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal",
                                    text: result.msg,
                                    icon: "info",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                title: "Error",
                                text: "Terjadi kesalahan pada server. Silakan coba lagi.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Data tidak jadi dihapus.",
                        icon: "info",
                        confirmButtonText: "OK"
                    });
                }
            });
        });
    </script>
@endsection
