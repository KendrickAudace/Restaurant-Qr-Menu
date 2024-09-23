<?php defined('ALTUMCODE') || die() ?>

<div class="container d-flex flex-wrap align-items-md-center my-5">
    <a href="mailto:?body=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Email') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/email.svg') ?></div>
    </a>
    <button type="button" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= l('page.print') ?>" onclick="window.print()">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/pdf.svg') ?></div>
    </button>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Facebook') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/facebook.svg') ?></div>
    </a>
    <a href="https://x.com/share?url=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'X') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/x.svg') ?></div>
    </a>
    <a href="https://pinterest.com/pin/create/link/?url=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Pinterest') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/pinterest.svg') ?></div>
    </a>
    <a href="https://linkedin.com/shareArticle?url=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'LinkedIn') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/linkedin.svg') ?></div>
    </a>
    <a href="https://www.reddit.com/submit?url=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Reddit') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/reddit.svg') ?></div>
    </a>
    <a href="https://wa.me/?text=<?= $data->external_url ?>" target="_blank" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Whatsapp') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/whatsapp.svg') ?></div>
    </a>
    <a href="https://t.me/share/url?url=<?= $data->external_url ?>" class="btn btn-gray-50 mb-2 mb-md-0 mr-3" data-toggle="tooltip" title="<?= sprintf(l('global.share_via'), 'Telegram') ?>">
        <div class="svg-sm d-flex"><?= include_view(ASSETS_PATH . 'images/s/telegram.svg') ?></div>
    </a>
</div>
