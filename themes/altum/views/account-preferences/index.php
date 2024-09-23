<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_preferences.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_preferences.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />


                <div class="form-group">
                    <label for="default_results_per_page"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('account_preferences.input.default_results_per_page') ?></label>
                    <select id="default_results_per_page" name="default_results_per_page" class="custom-select <?= \Altum\Alerts::has_field_errors('default_results_per_page') ? 'is-invalid' : null ?>">
                        <?php foreach([10, 25, 50, 100, 250, 500, 1000] as $key): ?>
                            <option value="<?= $key ?>" <?= ($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page) == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_results_per_page') ?>
                </div>

                <div class="form-group">
                    <label for="default_order_type"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('account_preferences.input.default_order_type') ?></label>
                    <select id="default_order_type" name="default_order_type" class="custom-select <?= \Altum\Alerts::has_field_errors('default_order_type') ? 'is-invalid' : null ?>">
                        <option value="ASC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                        <option value="DESC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_order_type') ?>
                </div>

                <div class="form-group">
                    <label for="stores_default_order_by"><i class="fas fa-fw fa-sm fa-store text-muted mr-1"></i> <?= sprintf(l('account_preferences.input.default_order_by_x'), l('stores.title')) ?></label>
                    <select id="stores_default_order_by" name="stores_default_order_by" class="custom-select <?= \Altum\Alerts::has_field_errors('stores_default_order_by') ? 'is-invalid' : null ?>">
                        <option value="store_id" <?= $this->user->preferences->stores_default_order_by == 'store_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                        <option value="datetime" <?= $this->user->preferences->stores_default_order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                        <option value="last_datetime" <?= $this->user->preferences->stores_default_order_by == 'last_datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_last_datetime') ?></option>
                        <option value="pageviews" <?= $this->user->preferences->stores_default_order_by == 'pageviews' ? 'selected="selected"' : null ?>><?= l('dashboard.filters.pageviews') ?></option>
                        <option value="orders" <?= $this->user->preferences->stores_default_order_by == 'orders' ? 'selected="selected"' : null ?>><?= l('dashboard.filters.orders') ?></option>
                        <option value="name" <?= $this->user->preferences->stores_default_order_by == 'name' ? 'selected="selected"' : null ?>><?= l('dashboard.filters.name') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('stores_default_order_by') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>
        </div>
    </div>
</div>
