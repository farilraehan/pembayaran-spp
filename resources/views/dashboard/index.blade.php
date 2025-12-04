@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
            <p class="mb-4">Management System Pembayaran SPP</p>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Data Siswa</p>
                                <h4 class="mb-0">700</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">weekend</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">-> </span><a
                                href="">Cek
                                Detail</a></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                                <h4 class="mb-0">2300</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">person</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last
                            month</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Ads Views</p>
                                <h4 class="mb-0">3,462</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">leaderboard</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than
                            yesterday</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-2 ps-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-0 text-capitalize">Sales</p>
                                <h4 class="mb-0">$103,430</h4>
                            </div>
                            <div
                                class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                <i class="material-symbols-rounded opacity-10">weekend</i>
                            </div>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-2 ps-3">
                        <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than
                            yesterday</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4 col-md-12 mb-md-0 mb-4">
                <div class="card p-2">
                    <canvas id="pie" height="270"></canvas>
                </div>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="card p-2">
                    <canvas id="chart-line-tasks" height="270"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // ============= PIE CHART =============
        const pieColors = [
            "#ff6384", // red pink
            "#ff9f40", // orange soft
            "#ffcd56", // yellow soft
            "#4bc0c0", // green teal
            "#36a2eb" // blue soft
        ];

        const pieData = {
            labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue'],
            datasets: [{
                data: [25, 30, 15, 10, 20],
                backgroundColor: pieColors,
                borderWidth: 1
            }]
        };

        const pieConfig = {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 10,
                            boxWidth: 12,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw}`
                        }
                    }
                }
            }
        };

        new Chart(document.getElementById('pie'), pieConfig);
    </script>
@endsection
