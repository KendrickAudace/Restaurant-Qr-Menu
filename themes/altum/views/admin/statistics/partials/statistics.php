<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-chart-bar fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.statistics.header') ?></h2>

            <div>
                <span data-toggle="tooltip" title="<?= l('admin_statistics.statistics.chart') ?>" class="badge <?= $data->total['statistics'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['statistics'] > 0 ? '+' : null) . nr($data->total['statistics']) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="statistics"></canvas>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Display chart */
    let statistics_chart = document.getElementById('statistics').getContext('2d');
    color_gradient = statistics_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    new Chart(statistics_chart, {
        type: 'line',
        data: {
            labels: <?= $data->statistics_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.statistics.chart')) ?>,
                    data: <?= $data->statistics_chart['statistics'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
