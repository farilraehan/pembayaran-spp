@if ($type == 'select')
    <div class="col-12">
        <label class="form-label">Nama Sub Laporan</label>
        <select name="sub_laporan" id="sub_laporan" class="form-select select2 w-100">
            @foreach ($sub_laporan as $sub)
                <option value="{{ $sub['value'] }}">{{ $sub['title'] }}</option>
            @endforeach
        </select>
    </div>
@elseif ($type == 'textarea')
    <div class="col-12">
        <label class="form-label d-block">Catatan Laporan</label>

        {{-- Editor tampil --}}
        <div id="editor" style="min-height:200px;">
            {!! $keterangan !!}
        </div>

        {{-- Value untuk submit --}}
        <textarea name="sub_laporan" id="sub_laporan" class="d-none">
            {!! $keterangan !!}
        </textarea>
    </div>
@endif
