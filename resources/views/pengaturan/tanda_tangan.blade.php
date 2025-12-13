@extends('layouts.base')

@section('content')
<style>
    .tox .tox-promotion {
    background: repeating-linear-gradient(transparent 0 1px, transparent 1px 39px) center top 39px / 100% calc(100% - 39px) no-repeat;
    background-color: #fff;
    grid-column: 2;
    grid-row: 1;
    padding-inline-end: 8px;
    padding-inline-start: 4px;
    padding-top: 5px;
    display: none;
    }
    .tox .tox-statusbar__branding svg {
    fill: rgba(34, 47, 62, .8);
    height: 1.14em;
    vertical-align: -.28em;
    width: 3.6em;
    display: none;
}
</style>
    <div class="app-main__inner">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5 class="card-title">Tanda tangan <b>Pelaporan</b></h5>
                                <form action="/pengaturan/sop/simpanttdpelaporan" method="post" id="formTtdPelaporan" height>
                                    @csrf
                    
                                    <input type="hidden" name="field" id="field" value="tanda_tangan_pelaporan">
                                    <textarea class="tiny-mce-editor" name="tanda_tangan" id="tanda_tangan" rows="20">
                                        {!! json_decode($ttd->tanda_tangan, true) !!}
                                    </textarea>
                                </form>
                    
                                {{-- @if (!$tanggal)
                                    <small class="text-danger">
                                        Masukkan <span style="text-transform: lowercase">
                                            *{tanggal}* pada form tanda tangan untuk menuliskan tanggal laporan dibuat. <b>Hapus tanda bintang
                                                (*)</b>
                                        </span>
                                    </small>
                                @endif --}}
                                <div class="d-flex justify-content-end mt-3">
                                    <button type="button" id="simpanTtdPelaporan" class="btn btn-dark btn-sm ms-2">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        if (tinymce.get('tanda_tangan')) {
        tinymce.get('tanda_tangan').remove();
            }

            tinymce.init({
                selector: '.tiny-mce-editor',
                height: 400,
                readonly: false,
                plugins: 'table visualblocks fullscreen',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | align | table fullscreen | removeformat',
                font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace;',
            });

        $(document).on('click', '#simpanTtdPelaporan', function(e) {
            e.preventDefault()

            tinymce.triggerSave()
            var form = $('#formTtdPelaporan')
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Toastr('success', result.msg)
                    }
                }
            })
        })
    </script>
@endsection
