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
            <li>
                <a href="<?= url('category/' . $data->category->category_id) ?>"><?= l('category.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= url('item/' . $data->item->item_id) ?>"><?= l('item.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li>
                <?= l('item_option.breadcrumb') ?><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('item_option_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <h1 class="h4 text-truncate"><?= l('item_option_create.header') ?></h1>
    <p></p>

    <form action="" method="post" role="form" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

        <div class="form-group">
            <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('item_option.input.name') ?></label>
            <input type="text" id="name" name="name" class="form-control" value="" placeholder="<?= l('item_option.input.name_placeholder') ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="options"><i class="fas fa-fw fa-sm fa-bars text-muted mr-1"></i> <?= l('item_option.input.options') ?></label>
            <input type="text" id="options" name="options" class="form-control" value="" placeholder="<?= l('item_option.input.options_placeholder') ?>" required="required" />
            <small class="form-text text-muted"><?= l('item_option.input.options_help') ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.create') ?></button>
    </form>

</div>
