@if ($type == 'select')
    <label class="form-label">Nama Sub Laporan</label>
    <select name="sub_laporan" id="sub_laporan" class="form-select select2 w-100">
        @foreach ($sub_laporan as $sub)
            <option value="{{ $sub['value'] }}">{{ $sub['title'] }}</option>
        @endforeach
    </select>
@elseif ($type == 'textarea')
    <label class="form-label d-block">Catatan Laporan</label>
    <div id="editor">{!! $keterangan !!}</div>
    <textarea name="sub_laporan" id="sub_laporan" class="d-none">{!! $keterangan !!}</textarea>
@endif
