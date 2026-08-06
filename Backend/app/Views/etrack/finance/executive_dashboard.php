<?php
$year = $year ?? date('Y');
$year_options = $year_options ?? [$year];
?>
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h4 class="page-title mb-0">CEO Executive Dashboard</h4>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select id="exec-year" class="form-select form-select-sm" style="width:auto;">
                    <?php foreach ($year_options as $yr) : ?>
                        <option value="<?php echo $yr; ?>" <?php echo ($yr == $year) ? 'selected' : ''; ?>><?php echo $yr; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-muted small" id="exec-last-synced">Loading...</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="ri-printer-line"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Row 1: Headline KPI cards -->
<div class="row" id="exec-kpi-row">
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">YTD Revenue</p>
                <h3 class="my-1" id="kpi-ytd-revenue">$ 0</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">YTD Cost</p>
                <h3 class="my-1" id="kpi-ytd-cost">$ 0</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">YTD Net Profit</p>
                <h3 class="my-1" id="kpi-ytd-profit">$ 0</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">Cumulative Position</p>
                <h3 class="my-1" id="kpi-cumulative">$ 0</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">Pipeline Coverage</p>
                <h3 class="my-1" id="kpi-pipeline">0 mo</h3>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xl-2">
        <div class="card bg-pattern">
            <div class="card-body">
                <p class="text-muted mb-1 text-truncate">Workforce Utilization</p>
                <h3 class="my-1" id="kpi-utilization">0%</h3>
            </div>
        </div>
    </div>
</div>

<!-- Insights -->
<div class="row">
    <div class="col-12">
        <div class="card border-start border-4 border-info">
            <div class="card-body">
                <h5 class="mb-2"><i class="ri-lightbulb-line"></i> Insights</h5>
                <ul class="mb-0" id="exec-insights"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Revenue composition -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Revenue Composition — Library vs Bespoke vs WIP</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="btn-mode-dollar">$</button>
                        <button type="button" class="btn btn-outline-secondary" id="btn-mode-pct">%</button>
                    </div>
                </div>
                <div class="chart-container"><canvas id="chart-composition"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Profitability trend -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Profitability Trend</h5>
                <div class="chart-container"><canvas id="chart-profitability"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Cost breakdown -->
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cost Breakdown</h5>
                <div class="chart-container" style="max-width:320px;"><canvas id="chart-cost-donut"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Cost as % of Revenue</h5>
                <div class="chart-container"><canvas id="chart-cost-ratio"></canvas></div>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Workforce panel -->
<div class="row">
    <div class="col-lg-2 col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">Male / Female</h6>
                <canvas id="chart-gender"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">Permanent / Contract</h6>
                <canvas id="chart-engagement"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">Production / Support</h6>
                <canvas id="chart-production"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title text-center">Utilization</h6>
                <canvas id="chart-utilization-gauge"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-8">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Utilization Leaderboard</h6>
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted mb-1 small">Top 5</p>
                        <table class="table table-sm mb-0" id="table-leaderboard-top"><tbody></tbody></table>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1 small">Bottom 5</p>
                        <table class="table table-sm mb-0" id="table-leaderboard-bottom"><tbody></tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 6: Pipeline & forecast -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Sales Pipeline vs Breakeven Cost</h5>
                <div class="chart-container"><canvas id="chart-pipeline"></canvas></div>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-container {
        position: relative;
        max-width: 1200px;
        margin: 10px auto;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    var MONTHS = [];
    var charts = {};
    var lastPayload = null;
    var COLORS = {
        library: 'rgba(40, 167, 69, 0.75)',   // green - existing LIB badge color
        bespoke: 'rgba(111, 66, 193, 0.75)',  // purple - brand/category
        wip: 'rgba(255, 193, 7, 0.75)',       // amber - WIP/pending
        revenue: 'rgba(54, 162, 235, 0.85)',  // blue - revenue actuals
        cost: 'rgba(198, 106, 86, 0.85)',     // red - cost/loss
        delta: 'rgba(86, 198, 116, 1)',       // green - profit
        cumulative: 'hsl(71, 100%, 40%)',
        salary: 'rgba(220, 53, 69, 0.75)',
        operational: 'rgba(253, 126, 20, 0.75)',
        pipeline: 'rgba(23, 162, 184, 0.75)',
        breakeven: 'rgba(108, 117, 125, 0.9)'
    };

    function fmtMoney(v) {
        var n = Math.round(v || 0);
        return (n < 0 ? '-$ ' : '$ ') + Math.abs(n).toLocaleString();
    }

    function destroyChart(key) {
        if (charts[key]) {
            charts[key].destroy();
            delete charts[key];
        }
    }

    function loadDashboard(year) {
        document.getElementById('exec-last-synced').textContent = 'Loading...';
        fetch('<?php echo base_url('etrack/executive_dashboard/data'); ?>?year=' + encodeURIComponent(year), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(res) { return res.json(); })
            .then(function(payload) {
                if (!payload || payload.success !== true) {
                    document.getElementById('exec-last-synced').textContent = 'Failed to load data';
                    return;
                }
                lastPayload = payload;
                MONTHS = payload.months;
                renderKpis(payload);
                renderInsights(payload);
                renderComposition(payload, 'dollar');
                renderProfitability(payload);
                renderCostBreakdown(payload);
                renderWorkforce(payload);
                renderLeaderboard(payload);
                renderPipeline(payload);
                document.getElementById('exec-last-synced').textContent = 'Last synced: ' + payload.generated_at;
            })
            .catch(function() {
                document.getElementById('exec-last-synced').textContent = 'Failed to load data';
            });
    }

    function renderKpis(payload) {
        var k = payload.kpis;
        document.getElementById('kpi-ytd-revenue').textContent = fmtMoney(k.ytd_revenue);
        document.getElementById('kpi-ytd-cost').textContent = fmtMoney(k.ytd_cost);

        var profitEl = document.getElementById('kpi-ytd-profit');
        profitEl.textContent = fmtMoney(k.ytd_net_profit);
        profitEl.className = 'my-1 ' + (k.ytd_net_profit < 0 ? 'text-danger' : 'text-success');

        var cumEl = document.getElementById('kpi-cumulative');
        cumEl.textContent = fmtMoney(k.cumulative_position);
        cumEl.className = 'my-1 ' + (k.cumulative_position < 0 ? 'text-danger' : 'text-success');

        document.getElementById('kpi-pipeline').textContent = k.pipeline_coverage_months + ' mo';
        document.getElementById('kpi-utilization').textContent = k.utilization_rate + '%';
    }

    function renderInsights(payload) {
        var list = document.getElementById('exec-insights');
        list.innerHTML = '';
        (payload.insights || []).forEach(function(text) {
            var li = document.createElement('li');
            li.textContent = text;
            list.appendChild(li);
        });
    }

    function renderComposition(payload, mode) {
        var s = payload.series;
        var library = s.library.slice();
        var bespoke = s.bespoke.slice();
        var wip = s.wip.slice();

        if (mode === 'pct') {
            for (var i = 0; i < library.length; i++) {
                var total = library[i] + bespoke[i] + wip[i];
                if (total > 0) {
                    library[i] = +(library[i] / total * 100).toFixed(1);
                    bespoke[i] = +(bespoke[i] / total * 100).toFixed(1);
                    wip[i] = +(wip[i] / total * 100).toFixed(1);
                } else {
                    library[i] = bespoke[i] = wip[i] = 0;
                }
            }
        }

        destroyChart('composition');
        var ctx = document.getElementById('chart-composition').getContext('2d');
        charts.composition = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: MONTHS,
                datasets: [
                    { label: 'Library Revenue', data: library, backgroundColor: COLORS.library, stack: 's' },
                    { label: 'Bespoke Revenue', data: bespoke, backgroundColor: COLORS.bespoke, stack: 's' },
                    { label: 'WIP (placeholder data)', data: wip, backgroundColor: COLORS.wip, stack: 's' }
                ]
            },
            options: {
                responsive: true,
                scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
            }
        });
    }

    function renderProfitability(payload) {
        var s = payload.series;
        var barColors = s.delta.map(function(v) { return v < 0 ? 'rgba(198, 106, 86, 0.45)' : COLORS.revenue; });

        destroyChart('profitability');
        var ctx = document.getElementById('chart-profitability').getContext('2d');
        charts.profitability = new Chart(ctx, {
            data: {
                labels: MONTHS,
                datasets: [
                    { type: 'bar', label: 'Monthly Revenue ($)', data: s.revenue, backgroundColor: barColors },
                    { type: 'bar', label: 'Monthly Cost ($)', data: s.cost, backgroundColor: COLORS.cost },
                    { type: 'line', label: 'Delta ($)', data: s.delta, borderColor: COLORS.delta, borderWidth: 2, yAxisID: 'y1', fill: false },
                    { type: 'line', label: 'Cumulative ($)', data: s.cumulative, borderColor: COLORS.cumulative, borderWidth: 2, yAxisID: 'y1', fill: false }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, position: 'left' },
                    y1: { position: 'right', grid: { drawOnChartArea: false } }
                }
            }
        });
    }

    function renderCostBreakdown(payload) {
        var s = payload.series;
        var totalSalary = s.salary.reduce(function(a, b) { return a + b; }, 0);
        var totalOperational = s.operational.reduce(function(a, b) { return a + b; }, 0);

        destroyChart('costDonut');
        var donutCtx = document.getElementById('chart-cost-donut').getContext('2d');
        charts.costDonut = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Salary Cost', 'Operational Cost'],
                datasets: [{ data: [totalSalary, totalOperational], backgroundColor: [COLORS.salary, COLORS.operational] }]
            },
            options: { responsive: true }
        });

        var ratio = s.revenue.map(function(rev, i) {
            return rev > 0 ? +((s.cost[i] / rev) * 100).toFixed(1) : 0;
        });

        destroyChart('costRatio');
        var ratioCtx = document.getElementById('chart-cost-ratio').getContext('2d');
        charts.costRatio = new Chart(ratioCtx, {
            type: 'line',
            data: {
                labels: MONTHS,
                datasets: [{ label: 'Cost as % of Revenue', data: ratio, borderColor: COLORS.cost, fill: false, borderWidth: 2 }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }

    function donut(canvasId, key, labelA, labelB, countA, countB) {
        destroyChart(key);
        var ctx = document.getElementById(canvasId).getContext('2d');
        charts[key] = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [labelA, labelB],
                datasets: [{ data: [countA, countB], backgroundColor: [COLORS.revenue, COLORS.wip] }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    function renderWorkforce(payload) {
        var w = payload.workforce;
        donut('chart-gender', 'gender', 'Male', 'Female', w.male.count, w.female.count);
        donut('chart-engagement', 'engagement', 'Permanent', 'Contract', w.permanent.count, w.contract.count);
        donut('chart-production', 'production', 'Production', 'Support', w.production.count, w.support.count);

        destroyChart('utilizationGauge');
        var gaugeCtx = document.getElementById('chart-utilization-gauge').getContext('2d');
        var util = Math.min(100, Math.max(0, payload.kpis.utilization_rate));
        charts.utilizationGauge = new Chart(gaugeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Utilized', 'Remaining'],
                datasets: [{ data: [util, 100 - util], backgroundColor: [COLORS.delta, '#e9ecef'] }]
            },
            options: { responsive: true, circumference: 180, rotation: 270, plugins: { legend: { display: false } } }
        });
    }

    function leaderboardRow(row) {
        return '<tr><td>' + row.name + '</td><td class="text-end">' + row.utilization + '%</td></tr>';
    }

    function renderLeaderboard(payload) {
        var top = payload.leaderboard.top || [];
        var bottom = payload.leaderboard.bottom || [];
        document.querySelector('#table-leaderboard-top tbody').innerHTML = top.map(leaderboardRow).join('');
        document.querySelector('#table-leaderboard-bottom tbody').innerHTML = bottom.map(leaderboardRow).join('');
    }

    function renderPipeline(payload) {
        var s = payload.series;
        var avgCost = s.cost.reduce(function(a, b) { return a + b; }, 0) / 12;
        var breakeven = MONTHS.map(function() { return avgCost; });

        destroyChart('pipeline');
        var ctx = document.getElementById('chart-pipeline').getContext('2d');
        charts.pipeline = new Chart(ctx, {
            data: {
                labels: MONTHS,
                datasets: [
                    { type: 'bar', label: 'Sales Pipeline ($)', data: s.pipeline, backgroundColor: COLORS.pipeline },
                    { type: 'line', label: 'Avg Monthly Cost ($)', data: breakeven, borderColor: COLORS.breakeven, borderDash: [6, 4], fill: false, borderWidth: 2 }
                ]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    }

    document.getElementById('exec-year').addEventListener('change', function() {
        loadDashboard(this.value);
    });
    document.getElementById('btn-mode-dollar').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('btn-mode-pct').classList.remove('active');
        if (lastPayload) renderComposition(lastPayload, 'dollar');
    });
    document.getElementById('btn-mode-pct').addEventListener('click', function() {
        this.classList.add('active');
        document.getElementById('btn-mode-dollar').classList.remove('active');
        if (lastPayload) renderComposition(lastPayload, 'pct');
    });

    loadDashboard(document.getElementById('exec-year').value);
})();
</script>
