<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('dashboard') ?>"><?= l('dashboard.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= url('store/' . $data->{$data->identifier_name}->store_id) ?>"><?= l('store.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <?php if(in_array($data->identifier_name, ['menu', 'category', 'item'])): ?>
                <li>
                    <a href="<?= url('menu/' . $data->{$data->identifier_name}->menu_id) ?>"><?= l('menu.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>

                <?php if(in_array($data->identifier_name, ['category', 'item'])): ?>
                    <li>
                        <a href="<?= url('category/' . $data->{$data->identifier_name}->category_id) ?>"><?= l('category.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                <?php endif ?>

                <?php if(in_array($data->identifier_name, ['item'])): ?>
                    <li>
                        <a href="<?= url('item/' . $data->{$data->identifier_name}->item_id) ?>"><?= l('item.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                <?php endif ?>
            <?php endif ?>
            <li class="active" aria-current="page"><?= l('orders_statistics.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>


    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><?= sprintf(l('orders_statistics.header'), $data->{$data->identifier_name}->name) ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <div>
                <button
                        id="url_copy"
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="tooltip"
                        title="<?= l('global.clipboard_copy') ?>"
                        aria-label="<?= l('global.clipboard_copy') ?>"
                        data-copy="<?= l('global.clipboard_copy') ?>"
                        data-copied="<?= l('global.clipboard_copied') ?>"
                        data-clipboard-text="<?= $data->external_url ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

            <button
                    id="daterangepicker"
                    type="button"
                    class="btn btn-sm btn-light"
                    data-min-date="<?= \Altum\Date::get($data->{$data->identifier_name}->datetime, 4) ?>"
                    data-max-date="<?= \Altum\Date::get('', 4) ?>"
            >
                <i class="fas fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                        <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->datetime['end_date'], 2, \Altum\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fas fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>
    </div>

    <p class="text-truncate">
        <a href="<?= $data->external_url ?>" target="_blank" rel="noreferrer">
            <i class="fas fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= remove_url_protocol_from_url($data->external_url) ?>
        </a>
    </p>


    <?php if(!count($data->orders_items)): ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'orders_statistics',
            'has_secondary_text' => true,
        ]); ?>

    <?php else: ?>

        <div class="card mb-5">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="orders_items_chart"></canvas>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
                        <div>
                            <h3 class="h5"><?= l('orders_statistics.ordered_items') ?></h3>
                            <p class="text-muted"><?= l('orders_statistics.ordered_items_help') ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-lg-auto d-flex d-print-none">
                        <div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                                    <i class="fas fa-fw fa-sm fa-download"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right d-print-none">
                                    <a href="<?= url('orders-statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date'] . '&export=csv') ?>" target="_blank" class="dropdown-item">
                                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                                    </a>
                                    <a href="<?= url('orders-statistics?' . $data->identifier_key . '=' . $data->identifier_value . '&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date'] . '&export=json') ?>" target="_blank" class="dropdown-item">
                                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" onclick="window.print();return false;" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php foreach($data->orders_items as $row): ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="text-truncate">
                                <a href="<?= url('item/' . $row->item_id) ?>"><?= $row->name ?></a>
                            </div>

                            <div>
                                <small class="text-muted"><?= nr($row->value) . ' ' . $data->store->currency ?></small>
                                <span class="ml-3"><?= sprintf(l('orders_statistics.orders'), nr($row->orders)) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

    <?php endif ?>

    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js?v=' . PRODUCT_CODE ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>
    <script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js?v=' . PRODUCT_CODE ?>"></script>

    <script>
        'use strict';

        moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

        /* Daterangepicker */
        $('#daterangepicker').daterangepicker({
            startDate: <?= json_encode($data->datetime['start_date']) ?>,
            endDate: <?= json_encode($data->datetime['end_date']) ?>,
            minDate: $('#daterangepicker').data('min-date'),
            maxDate: $('#daterangepicker').data('max-date'),
            ranges: {
                <?= json_encode(l('global.date.today')) ?>: [moment(), moment()],
                <?= json_encode(l('global.date.yesterday')) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                <?= json_encode(l('global.date.last_7_days')) ?>: [moment().subtract(6, 'days'), moment()],
                <?= json_encode(l('global.date.last_30_days')) ?>: [moment().subtract(29, 'days'), moment()],
                <?= json_encode(l('global.date.this_month')) ?>: [moment().startOf('month'), moment().endOf('month')],
                <?= json_encode(l('global.date.last_month')) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                <?= json_encode(l('global.date.all_time')) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
            },
            alwaysShowCalendars: true,
            linkedCalendars: false,
            singleCalendar: true,
            locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
        }, (start, end, label) => {

            /* Redirect */
            redirect(`<?= url('orders-statistics?' . $data->identifier_key . '=' . $data->identifier_value) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

        });

        <?php if(count($data->orders_items)): ?>
        let css = window.getComputedStyle(document.body);
        let value_color = css.getPropertyValue('--primary');
        let orders_color = css.getPropertyValue('--gray-800');
        let value_color_gradient = null;
        let orders_color_gradient = null;

        /* Chart */
        let orders_items_chart = document.getElementById('orders_items_chart').getContext('2d');

        /* Colors */
        value_color_gradient = orders_items_chart.createLinearGradient(0, 0, 0, 250);
        value_color_gradient.addColorStop(0, set_hex_opacity(value_color, 0.6));
        value_color_gradient.addColorStop(1, set_hex_opacity(value_color, 0.1));

        orders_color_gradient = orders_items_chart.createLinearGradient(0, 0, 0, 250);
        orders_color_gradient.addColorStop(0, set_hex_opacity(orders_color, 0.6));
        orders_color_gradient.addColorStop(1, set_hex_opacity(orders_color, 0.1));

        /* Display chart */
        new Chart(orders_items_chart, {
            type: 'line',
            data: {
                labels: <?= $data->orders_items_chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(l('orders_statistics.ordered_items_label')) ?>,
                        data: <?= $data->orders_items_chart['ordered_items'] ?? '[]' ?>,
                        backgroundColor: value_color_gradient,
                        borderColor: orders_color,
                        fill: true
                    },
                    {
                        label: <?= json_encode(l('orders_statistics.value_label')) ?>,
                        data: <?= $data->orders_items_chart['value'] ?? '[]' ?>,
                        backgroundColor: orders_color_gradient,
                        borderColor: value_color,
                        fill: true
                    }
                ]
            },
            options: chart_options
        });
        <?php endif ?>
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'store',
    'resource_id' => 'store_id',
    'has_dynamic_resource_name' => true,
    'path' => 'store/delete'
]), 'modals'); ?>
