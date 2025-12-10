@if ($type == 'select')
    <label for="sub_laporan" class="form-label">Nama Sub Laporan</label>
    <select name="sub_laporan" id="sub_laporan" class="form-select select2 w-100">
        @foreach ($sub_laporan as $sub)
            <option value="{{ $sub['value'] }}">{{ $sub['title'] }}</option>
        @endforeach
    </select>
@elseif ($type == 'textarea')
    <label for="sub_laporan" class="form-label d-block">Catatan Laporan</label>
    <div id="editor" style="height:200px;">{!! $keterangan !!}</div>
    <textarea name="sub_laporan" id="sub_laporan" class="d-none">{!! $keterangan !!}</textarea>

    <script>
        if (typeof Quill !== 'undefined') {
            window.quill = new Quill('#editor', {
                theme: 'snow'
            });
            quill.on('text-change', function() {
                $('#sub_laporan').val(quill.root.innerHTML);
            });
        }
    </script>
@endif
