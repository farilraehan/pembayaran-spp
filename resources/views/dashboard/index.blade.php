@extends('layouts.base')
@section('content')
<div class="row">
    <div class="ms-3 mb-4">
        <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
        <p class="mb-4">Management System Pembayaran SPP</p>
    </div>

    <div class="row mb-4 d-flex align-items-stretch">
        <div class="col-xl-8 d-flex flex-column">
            <div class="row mb-4">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Data Siswa</p>
                                    <h4 class="mb-0 text-center">3462</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">leaderboard</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <a href="#" class="mb-0 text-sm" data-bs-toggle="modal" data-bs-target="#Siswa">
                                <span class="text-success font-weight-bolder">Cek Detail . . .</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Tunggakan</p>
                                    <h4 class="mb-0 text-center">103</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <a href="/" class="mb-0 text-sm" data-bs-toggle="modal" data-bs-target="#Tunggakan"><span class="text-danger font-weight-bolder">Cek Detail . . .</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-fill">
                <div class="col-12 d-flex">
                    <div class="card flex-fill p-2">
                        <canvas id="chart-line-tasks" class="h-100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 d-flex flex-column">
            <div class="card flex-fill p-3 d-flex flex-column">
                <div class="mb-3 flex-shrink-0">
                    <h6 class="text-center mb-2">Nominal Spp Per Tahun</h6>
                    <ul class="list-group list-group-flush overflow-auto" style="max-height: 200px;">
                        @for($i=1; $i<=10; $i++)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            2017 
                            <span class="badge bg-warning rounded-pill">{{ rand(50, 500) }}</span>
                        </li>
                        @endfor
                    </ul>
                </div>
                <div class="mb-3 flex-shrink-0" style="height: 150px;">
                    <h6 class="text-center mb-1">Jumlah Siswa</h6>
                    <canvas id="donutChart" style="height: 100%; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<!-- Modal Siswa -->
<div class="modal fade modal-fullscreen" id="Siswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Detail siswa...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tunggakan -->
<div class="modal fade modal-fullscreen" id="Tunggakan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Tunggakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Detail tunggakan...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
const ctxLine = document.getElementById('chart-line-tasks').getContext('2d');
new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
        datasets: [{
            label: 'Pendapatan',
            data: [1200,1900,3000,5000,2300,3200,4100,3800],
            borderColor: '#4bc0c0',
            backgroundColor: 'rgba(75,192,192,0.2)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#4bc0c0'
        }]
    },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:true}}, scales:{y:{beginAtZero:true}} }
});

const donutCtx = document.getElementById('donutChart').getContext('2d');
new Chart(donutCtx, {
    type:'doughnut',
    data:{ labels:['Completed','Remaining'], datasets:[{ data:[50,50], backgroundColor:['#4bc0c0','#e9ecef'], cutout:'70%'}]},
    options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}} }
});
</script>
@endsection
