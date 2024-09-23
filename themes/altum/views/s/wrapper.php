<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" dir="<?= l('direction') ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL ?>">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
            <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
            <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \Altum\Uploads::get_path('pwa') . 'manifest.json' ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php \Altum\Meta::output() ?>

        <?php if(isset($this->store) && $this->store_user->plan_settings->search_engine_block_is_enabled && !$this->store->is_se_visible): ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <?php if(isset($this->store) && $this->store->favicon): ?>
            <link href="<?= \Altum\Uploads::get_full_url('store_favicons') . $this->store->favicon ?>" rel="icon" />
        <?php else: ?>

            <?php if(!empty(settings()->main->favicon)): ?>
                <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="icon" />
            <?php endif ?>

        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['store-custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js_store)): ?>
            <?= get_settings_custom_head_js('head_js_store') ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css_store)): ?>
            <style><?= settings()->custom->head_css_store ?></style>
        <?php endif ?>

        <?php if(!empty($this->store->custom_css) && $this->store_user->plan_settings->custom_css_is_enabled): ?>
            <style><?= $this->store->custom_css ?></style>
        <?php endif ?>

        <?php if($this->store->settings->font_family): ?>
            <?php $fonts = require APP_PATH . 'includes/s/fonts.php' ?>
            <?php if($fonts[$this->store->settings->font_family]['font_css_url']): ?>
                <link href="<?= $fonts[$this->store->settings->font_family]['font_css_url'] ?>" rel="stylesheet">
            <?php endif ?>

            <?php if($fonts[$this->store->settings->font_family]['font-family']): ?>
                <style>html, body {font-family: <?= $fonts[$this->store->settings->font_family]['font-family'] ?> !important;}</style>
            <?php endif ?>
        <?php endif ?>
        <style>html {font-size: <?= (int) ($this->store->settings->font_size ?? 16) . 'px' ?> !important;}</style>
    </head>

    <body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> <?= $this->store->theme ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
        <?php if(!empty(settings()->custom->body_content_transfers)): ?>
            <?= settings()->custom->body_content_transfers ?>
        <?php endif ?>

        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>
        <?php require THEME_PATH . 'views/partials/ad_blocker_detector.php' ?>

        <?php require THEME_PATH . 'views/s/partials/ads_header.php' ?>

        <main class="altum-animate altum-animate-fill-both altum-animate-fade-in">

            <?= $this->views['content'] ?>

        </main>

        <?php require THEME_PATH . 'views/s/partials/ads_footer.php' ?>

        <?= $this->views['footer'] ?>

        <?= \Altum\Event::get_content('modals') ?>

        <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

        <?php foreach(['custom.js'] as $file): ?>
            <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
        <?php endforeach ?>

        <?= \Altum\Event::get_content('javascript') ?>

        <?php if(!empty($this->store->custom_js) && $this->store_user->plan_settings->custom_js_is_enabled): ?>
            <?= $this->store->custom_js ?>
        <?php endif ?>
    </body>
</html>
