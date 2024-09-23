<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="random_url_length"><?= l('admin_settings.stores.random_url_length') ?></label>
        <input id="random_url_length" type="number" min="4" step="1" name="random_url_length" class="form-control" value="<?= settings()->stores->random_url_length ?? 7 ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.stores.random_url_length_help') ?></small>
    </div>

    <div class="form-group">
        <label for="example_url"><?= l('admin_settings.stores.example_url') ?></label>
        <input id="example_url" type="url" name="example_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" value="<?= settings()->stores->example_url ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.stores.example_url_help') ?></small>
    </div>

    <div class="form-group">
        <label for="branding"><?= l('admin_settings.stores.branding') ?></label>
        <textarea id="branding" name="branding" class="form-control"><?= settings()->stores->branding ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.stores.branding_help') ?></small>
        <small class="form-text text-muted"><?= l('admin_settings.stores.branding_help2') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="domains_is_enabled" name="domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->stores->domains_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="domains_is_enabled"><?= l('admin_settings.stores.domains_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.stores.domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->stores->additional_domains_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="additional_domains_is_enabled"><?= l('admin_settings.stores.additional_domains_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.stores.additional_domains_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="main_domain_is_enabled" name="main_domain_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->stores->main_domain_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="main_domain_is_enabled"><?= l('admin_settings.stores.main_domain_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.stores.main_domain_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="domains_custom_main_ip"><?= l('admin_settings.stores.domains_custom_main_ip') ?></label>
        <input id="domains_custom_main_ip" name="domains_custom_main_ip" type="text" class="form-control" value="<?= settings()->stores->domains_custom_main_ip ?>" placeholder="<?= $_SERVER['SERVER_ADDR'] ?>">
        <small class="form-text text-muted"><?= l('admin_settings.stores.domains_custom_main_ip_help') ?></small>
    </div>

    <div class="form-group">
        <label for="email_reports_is_enabled"><?= l('admin_settings.stores.email_reports_is_enabled') ?></label>
        <select id="email_reports_is_enabled" name="email_reports_is_enabled" class="custom-select">
            <option value="0" <?= !settings()->stores->email_reports_is_enabled ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
            <option value="weekly" <?= settings()->stores->email_reports_is_enabled == 'weekly' ? 'selected="selected"' : null ?>><?= l('admin_settings.stores.email_reports_is_enabled_weekly') ?></option>
            <option value="monthly" <?= settings()->stores->email_reports_is_enabled == 'monthly' ? 'selected="selected"' : null ?>><?= l('admin_settings.stores.email_reports_is_enabled_monthly') ?></option>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.stores.email_reports_is_enabled_help') ?></small><br />
    </div>

    <?php foreach(['logo', 'favicon', 'image', 'opengraph', 'menu_image', 'item_image'] as $key): ?>
        <div class="form-group">
            <label for="<?= $key . '_size_limit' ?>"><?= l('admin_settings.stores.' . $key . '_size_limit') ?></label>
            <div class="input-group">
                <input id="<?= $key . '_size_limit' ?>" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="<?= $key . '_size_limit' ?>" class="form-control" value="<?= settings()->stores->{$key . '_size_limit'} ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.mb') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('global.accessibility.admin_file_size_limit_help') ?></small>
        </div>
    <?php endforeach ?>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
