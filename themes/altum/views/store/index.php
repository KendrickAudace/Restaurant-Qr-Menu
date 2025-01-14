<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('dashboard') ?>"><?= l('dashboard.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('store.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-store mr-1"></i> <?= sprintf(l('store.header'), $data->store->name) ?></h1>

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
                        data-clipboard-text="<?= $data->store->full_url ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

            <?= include_view(THEME_PATH . 'views/store/store_dropdown_button.php', ['id' => $data->store->store_id, 'resource_name' => $data->store->name]) ?>
        </div>
    </div>

    <p class="text-truncate">
        <a href="<?= $data->store->full_url ?>" target="_blank" rel="noreferrer">
            <i class="fas fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= remove_url_protocol_from_url($data->store->full_url) ?>
        </a>
    </p>

    <?php if(count($data->orders)): ?>
        <div class="chart-container mb-5">
            <canvas id="orders_chart"></canvas>
        </div>
    <?php endif ?>

    <div class="d-flex align-items-center mb-3">
        <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= l('menu.menus') ?></h2>

        <div class="flex-fill">
            <hr class="border-gray-100" />
        </div>

        <div class="ml-3">
            <?php if($this->user->plan_settings->menus_limit != -1 && $data->total_menus >= $this->user->plan_settings->menus_limit): ?>
                <button type="button" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>" class="btn btn-sm btn-primary disabled">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('menu.create') ?>
                </button>
            <?php else: ?>
                <a href="<?= url('menu-create/' . $data->store->store_id) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_menus, $this->user->plan_settings->menus_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('menu.create') ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->menus)): ?>
        <div class="row" data-blocks>

            <?php foreach($data->menus as $row): ?>
                <div  data-menu-id="<?= $row->menu_id ?>" class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h4 mb-0">
                                    <a href="<?= url('menu/' . $row->menu_id) ?>"><?= $row->name ?></a>
                                </h3>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-link text-secondary draggable" data-draggable>
                                        <i class="fas fa-fw fa-expand-arrows-alt"></i>
                                    </button>

                                    <?= include_view(THEME_PATH . 'views/menu/menu_dropdown_button.php', ['id' => $row->menu_id, 'resource_name' => $row->name]) ?>
                                </div>
                            </div>

                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fas fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf(l('menu.datetime'), \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
                                    <i class="fas fa-fw fa-sm fa-chart-pie text-muted mr-1"></i> <a href="<?= url('statistics?menu_id=' . $row->menu_id) ?>"><?= sprintf(l('menu.pageviews'), nr($row->pageviews)) ?></a>
                                </div>

                                <div>
                                    <?php if($row->is_enabled): ?>
                                        <span class="badge badge-success"><i class="fas fa-fw fa-check"></i> <?= l('global.active') ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash"></i> <?= l('global.disabled') ?></span>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'store',
            'has_secondary_text' => true,
        ]); ?>

    <?php endif ?>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    <?php if(count($data->orders)): ?>
    let css = window.getComputedStyle(document.body);
    let value_color = css.getPropertyValue('--primary');
    let orders_color = css.getPropertyValue('--gray-800');
    let value_color_gradient = null;
    let orders_color_gradient = null;

    /* Chart */
    let orders_chart = document.getElementById('orders_chart').getContext('2d');

    /* Colors */
    value_color_gradient = orders_chart.createLinearGradient(0, 0, 0, 250);
    value_color_gradient.addColorStop(0, set_hex_opacity(value_color, 0.6));
    value_color_gradient.addColorStop(1, set_hex_opacity(value_color, 0.1));

    orders_color_gradient = orders_chart.createLinearGradient(0, 0, 0, 250);
    orders_color_gradient.addColorStop(0, set_hex_opacity(orders_color, 0.6));
    orders_color_gradient.addColorStop(1, set_hex_opacity(orders_color, 0.1));

    /* Display chart */
    new Chart(orders_chart, {
        type: 'line',
        data: {
            labels: <?= $data->orders_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('store.orders_label')) ?>,
                    data: <?= $data->orders_chart['orders'] ?? '[]' ?>,
                    backgroundColor: orders_color_gradient,
                    borderColor: orders_color,
                    fill: true
                },
                {
                    label: <?= json_encode(l('store.value_label')) ?>,
                    data: <?= $data->orders_chart['value'] ?? '[]' ?>,
                    backgroundColor: value_color_gradient,
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

<?php include_view(THEME_PATH . 'views/partials/js_sortable_blocks.php', ['id_type' => 'menu', 'store' => $data->store]) ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'store',
    'resource_id' => 'store_id',
    'has_dynamic_resource_name' => true,
    'path' => 'store/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'menu',
    'resource_id' => 'menu_id',
    'has_dynamic_resource_name' => true,
    'path' => 'menu/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'store_duplicate_modal', 'resource_id' => 'store_id', 'path' => 'store/duplicate']), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'menu_duplicate_modal', 'resource_id' => 'menu_id', 'path' => 'menu/duplicate']), 'modals'); ?>
