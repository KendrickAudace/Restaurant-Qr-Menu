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
                <a href="<?= url('store/' . $data->store->store_id) ?>"><?= l('store.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= url('menu/' . $data->menu->menu_id) ?>"><?= l('menu.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('category.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-shopping-bag mr-1"></i> <?= sprintf(l('category.header'), $data->category->name) ?></h1>

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
                        data-clipboard-text="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

            <?= include_view(THEME_PATH . 'views/category/category_dropdown_button.php', ['id' => $data->category->category_id, 'resource_name' => $data->category->name]) ?>
        </div>
    </div>

    <p class="text-truncate">
        <a href="<?= $data->store->full_url . $data->menu->url . '/' . $data->category->url ?>" target="_blank" rel="noreferrer">
            <i class="fas fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= remove_url_protocol_from_url($data->store->full_url . $data->menu->url . '/' . $data->category->url) ?>
        </a>
    </p>

    <div class="d-flex align-items-center mb-3">
        <h2 class="h6 text-uppercase text-muted mb-0 mr-3"><?= l('item.items') ?></h2>

        <div class="flex-fill">
            <hr class="border-gray-100" />
        </div>

        <div class="ml-3">
            <?php if($this->user->plan_settings->items_limit != -1 && $data->total_items >= $this->user->plan_settings->items_limit): ?>
                <button type="button" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>" class="btn btn-sm btn-primary disabled">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('item.create') ?>
                </button>
            <?php else: ?>
                <a href="<?= url('item-create/' . $data->category->category_id) ?>" class="btn btn-sm btn-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_items, $this->user->plan_settings->items_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                    <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('item.create') ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <?php if(count($data->items)): ?>
        <div class="row" data-blocks>

            <?php foreach($data->items as $row): ?>
                <div data-item-id="<?= $row->item_id ?>" class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-center justify-content-between">
                                <h3 class="h4 mb-0">
                                    <a href="<?= url('item/' . $row->item_id) ?>"><?= $row->name ?></a>
                                </h3>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-link text-secondary draggable" data-draggable>
                                        <i class="fas fa-fw fa-expand-arrows-alt"></i>
                                    </button>

                                    <?= include_view(THEME_PATH . 'views/item/item_dropdown_button.php', ['id' => $row->item_id, 'resource_name' => $row->name]) ?>
                                </div>
                            </div>

                            <p class="m-0">
                                <small class="text-muted">
                                    <i class="fas fa-fw fa-sm fa-dollar-sign text-muted mr-1"></i> <?= sprintf(l('item.price_currency'), $row->price, $data->store->currency) ?>
                                </small>
                            </p>

                            <?php if($this->user->plan_settings->ordering_is_enabled): ?>
                                <p class="m-0">
                                    <small class="text-muted">
                                        <i class="fas fa-fw fa-sm fa-bell text-muted mr-1"></i> <a href="<?= url('orders/' . $row->store_id) ?>" target="_blank"><?= sprintf(l('item.orders'), nr($row->orders)) ?></a>
                                    </small>
                                </p>
                            <?php endif ?>

                            <p class="m-0">
                                <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                    <i class="fas fa-fw fa-sm fa-calendar text-muted mr-1"></i> <?= sprintf(l('category.datetime'), \Altum\Date::get($row->datetime, 2)) ?>
                                </small>
                            </p>
                        </div>

                        <div class="card-footer bg-gray-50 border-0">
                            <div class="d-flex flex-lg-row justify-content-lg-between">
                                <div>
                                    <i class="fas fa-fw fa-sm fa-chart-pie text-muted mr-1"></i> <a href="<?= url('statistics?item_id=' . $row->item_id) ?>"><?= sprintf(l('category.pageviews'), nr($row->pageviews)) ?></a>
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
            'name' => 'category',
            'has_secondary_text' => true,
        ]); ?>

    <?php endif ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/js_sortable_blocks.php', ['id_type' => 'item', 'store' => $data->store]) ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'item',
    'resource_id' => 'item_id',
    'has_dynamic_resource_name' => true,
    'path' => 'item/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'category',
    'resource_id' => 'category_id',
    'has_dynamic_resource_name' => true,
    'path' => 'category/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'item_duplicate_modal', 'resource_id' => 'item_id', 'path' => 'item/duplicate']), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'category_duplicate_modal', 'resource_id' => 'category_id', 'path' => 'category/duplicate']), 'modals'); ?>
