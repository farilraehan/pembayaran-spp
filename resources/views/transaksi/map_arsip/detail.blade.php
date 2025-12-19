@php
    use App\Utils\Tanggal;
@endphp

<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="keuangan" class="table align-items-center table-striped">
                        <thead>
                            <tr>
                                <th width="15%">Tanggal Trx</th>
                                <th width="15%">Bulan SPP</th>
                                <th width="15%">Nominal</th>
                                <th width="40%">Keterangan</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswa->getTransaksi as $item)
                                <tr>
                                    <td>
                                        {{ Tanggal::tglIndo($item->tanggal_transaksi) }}
                                    </td>
                                    <td>
                                        @if ($item->spp)
                                            {{ Tanggal::namabulan($item->spp->tanggal) }}
                                        @else
                                            Daftar Ulang
                                        @endif
                                    </td>
                                    <td>
                                        Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td class="text-nowrap">
                                    <a href="/app/transaksi/kwitansi-spp?ids={{ $item->id }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary"
                                    title="Cetak Kwitansi">
                                        <i class="material-symbols-rounded fs-6">print</i>
                                    </a>
                                    <button type="submit"
                                            class="btn btn-sm btn-danger btnDelete" data-id="{{  $item->id }}">
                                        <i class="material-symbols-rounded fs-6">delete</i>
                                    </button>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        Tidak ada transaksi SPP
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
