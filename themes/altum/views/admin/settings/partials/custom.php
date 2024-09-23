<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="head_js"><i class="fab fa-fw fa-sm fa-js text-muted mr-1"></i> <?= l('admin_settings.custom.head_js') ?></label>
        <textarea id="head_js" name="head_js" class="form-control"><?= settings()->custom->head_js ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.custom.head_js_help') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}', '{{USER:USER_ID}}', '{{USER:PLAN_ID}}']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="head_css"><i class="fab fa-fw fa-sm fa-css3 text-muted mr-1"></i> <?= l('admin_settings.custom.head_css') ?></label>
        <textarea id="head_css" name="head_css" class="form-control"><?= settings()->custom->head_css ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.custom.head_css_help') ?></small>
    </div>

    <div class="form-group">
        <label for="body_content"><i class="fab fa-fw fa-sm fa-html5 text-muted mr-1"></i> <?= l('admin_settings.custom.body_content') ?></label>
        <textarea id="body_content" name="body_content" class="form-control"><?= settings()->custom->body_content ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.custom.body_content_help') ?></small>
    </div>

    <div class="form-group">
        <label for="head_js_store"><i class="fab fa-fw fa-sm fa-js text-muted mr-1"></i> <?= l('admin_settings.custom.head_js_store') ?></label>
        <textarea id="head_js_store" name="head_js_store" class="form-control"><?= settings()->custom->head_js_store ?></textarea>
        <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}', '{{USER:USER_ID}}', '{{USER:PLAN_ID}}']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="head_css_store"><i class="fab fa-fw fa-sm fa-css3 text-muted mr-1"></i> <?= l('admin_settings.custom.head_css_store') ?></label>
        <textarea id="head_css_store" name="head_css_store" class="form-control"><?= settings()->custom->head_css_store ?></textarea>
    </div>

    <div class="form-group">
        <label for="body_content_store"><i class="fab fa-fw fa-sm fa-html5 text-muted mr-1"></i> <?= l('admin_settings.custom.body_content_store') ?></label>
        <textarea id="body_content_store" name="body_content_store" class="form-control"><?= settings()->custom->body_content_store ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.custom.body_content_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
