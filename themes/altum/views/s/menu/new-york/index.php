<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<div class="container mt-5">

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= $data->store->full_url ?>"><?= l('s_store.breadcrumb') ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/chevron-right.svg') ?></div>
                </li>
                <li class="active" aria-current="page"><?= $data->menu->name ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h3"><?= $data->menu->name ?></h1>

    <?php if($data->menu->description): ?>
    <p class="text-muted"><?= $data->menu->description ?></p>
    <?php endif ?>

    <div class="d-flex flex-column flex-wrap flex-lg-row my-4">
        <?php foreach($data->categories as $category): ?>
            <a href="<?= $data->store->full_url . $data->menu->url . '#category_' . $category->category_id ?>" class="col-12 col-lg-2 mb-3 mr-3 text-truncate btn btn-sm btn-link bg-primary-100 text-decoration-none">
                <?= $category->name ?>
            </a>
        <?php endforeach ?>
    </div>

    <div>
        <?php foreach($data->categories as $category): ?>
        <div class="my-4">
            <h2 class="h4" id="<?= 'category_' . $category->category_id ?>"><?= $category->name ?></h2>

            <?php if($category->description): ?>
            <p class="text-muted"><?= $category->description ?></p>
            <?php endif ?>

            <div class="row">
                <?php foreach($data->items as $item): ?>
                    <?php if($category->category_id != $item->category_id) continue ?>
                    <div class="col-12 col-lg-6 my-3">
                        <div class="d-flex position-relative h-100 rounded p-3 bg-gray-50">
                            <?php if(!empty($item->image)): ?>
                                <div class="store-item-image-wrapper mr-4">
                                    <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>">
                                        <img src="<?= \Altum\Uploads::get_full_url('item_images') . $item->image ?>" class="store-item-image-background" loading="lazy" />
                                    </a>
                                </div>
                            <?php endif ?>

                            <div class="d-flex flex-column justify-content-between w-100">
                                <div>
                                    <h3 class="h5 mb-1">
                                        <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>">
                                            <?= $item->name ?>
                                        </a>
                                    </h3>

                                    <p class="mt-1 text-muted"><?= string_truncate($item->description, 100) ?></p>
                                </div>

                                <div class="mt-3">
                                    <div>
                                    <span class="h5 text-black">
                                        <?= nr($item->price, 2) ?>
                                    </span>
                                        <span class="text-muted">
                                        <?= $data->store->currency ?>
                                    </span>
                                    </div>

                                    <?php if($this->store->cart_is_enabled): ?>
                                        <div class="mt-3">
                                            <?php if($item->variants_is_enabled): ?>
                                                <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>" class="btn btn-block btn-sm btn-primary">
                                                    <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
                                                    <?= l('s_item.configure') ?>
                                                </a>
                                            <?php else: ?>
                                                <button
                                                        type="button"
                                                        class="add_to_cart btn btn-block btn-sm btn-primary"

                                                        data-item-price="<?= $item->price ?>"
                                                        data-item-id="<?= $item->item_id ?>"
                                                        data-item-name="<?= $item->name ?>"
                                                        data-item-full-url="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>"
                                                        data-item-full-image="<?= $item->image ? \Altum\Uploads::get_full_url('item_images') . $item->image : null ?>"
                                                >

                                                    <div class="add_to_cart_not_added">
                                                        <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
                                                        <?= l('s_item.add_to_cart') ?>
                                                    </div>

                                                    <div class="add_to_cart_added d-none">
                                                        <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/check-circle.svg') ?></div>
                                                        <?= l('s_item.added_to_cart') ?>
                                                    </div>

                                                </button>
                                            <?php endif ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <?php endforeach ?>
    </div>

</div>


<?= include_view(THEME_PATH . 'views/s/partials/js_quick_add_to_cart.php', ['store' => $data->store]) ?>

<?php if($data->store->settings->display_share_buttons): ?>
    <?= include_view(THEME_PATH . 'views/s/partials/share.php', ['external_url' => $data->store->full_url . $data->menu->url]) ?>
<?php endif ?>
