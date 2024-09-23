<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Title;

class StoreUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.stores')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $store_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$store = db()->where('store_id', $store_id)->where('user_id', $this->user->user_id)->getOne('stores')) {
            redirect('dashboard');
        }

        foreach(['settings', 'details', 'socials', 'payment_processors', 'business', 'ordering'] as $key) {
            $store->{$key} = json_decode($store->{$key});
        }

        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, true, $store->store_id);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['address'] = trim(query_clean($_POST['address']));
            $_POST['phone'] = trim(query_clean($_POST['phone']));
            $_POST['website'] = trim(query_clean($_POST['website']));
            $_POST['email'] = trim(query_clean($_POST['email']));
            $_POST['currency'] = trim(query_clean($_POST['currency']));
            $_POST['password'] = !empty($_POST['password']) ?
                ($_POST['password'] != $store->password ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $store->password)
                : null;
            $_POST['timezone']  = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? query_clean($_POST['timezone']) : settings()->main->default_timezone;
            $_POST['custom_css'] = mb_substr(trim($_POST['custom_css']), 0, 8192);
            $_POST['custom_js'] = mb_substr(trim($_POST['custom_js']), 0, 8192);
            $_POST['is_se_visible'] = $this->user->plan_settings->search_engine_block_is_enabled ? (int) isset($_POST['is_se_visible']) : 1;
            $_POST['is_removed_branding'] = (int) isset($_POST['is_removed_branding']);
            $_POST['email_reports_is_enabled'] = (int) isset($_POST['email_reports_is_enabled']);
            $_POST['ordering_on_premise_is_enabled'] = (int) isset($_POST['ordering_on_premise_is_enabled']);
            $_POST['ordering_on_premise_minimum_value'] = (float) $_POST['ordering_on_premise_minimum_value'];
            $_POST['ordering_takeaway_is_enabled'] = (int) isset($_POST['ordering_takeaway_is_enabled']);
            $_POST['ordering_takeaway_minimum_value'] = (float) $_POST['ordering_takeaway_minimum_value'];
            $_POST['ordering_delivery_is_enabled'] = (int) isset($_POST['ordering_delivery_is_enabled']);
            $_POST['ordering_delivery_minimum_value'] = (float) $_POST['ordering_delivery_minimum_value'];
            $_POST['ordering_delivery_cost'] = (float) $_POST['ordering_delivery_cost'];
            $_POST['ordering_delivery_free_minimum_value'] = (float) $_POST['ordering_delivery_free_minimum_value'];
            $_POST['email_orders_is_enabled'] = (int) isset($_POST['email_orders_is_enabled']);

            /* Payments */
            $_POST['paypal_is_enabled'] = (bool) $_POST['paypal_is_enabled'];
            $_POST['paypal_mode'] = in_array($_POST['paypal_mode'], ['live', 'sandbox']) ? query_clean($_POST['paypal_mode']) : 'sandbox';
            $_POST['paypal_client_id'] = trim(query_clean($_POST['paypal_client_id']));
            $_POST['paypal_secret'] = trim(query_clean($_POST['paypal_secret']));
            $_POST['stripe_is_enabled'] = (bool) $_POST['stripe_is_enabled'];
            $_POST['stripe_publishable_key'] = trim(query_clean($_POST['stripe_publishable_key']));
            $_POST['stripe_secret_key'] = trim(query_clean($_POST['stripe_secret_key']));
            $_POST['stripe_webhook_secret'] = trim(query_clean($_POST['stripe_webhook_secret']));
            $_POST['mollie_is_enabled'] = (bool) $_POST['mollie_is_enabled'];
            $_POST['mollie_api_key'] = trim(query_clean($_POST['mollie_api_key']));
            $_POST['offline_payment_is_enabled'] = (bool) $_POST['offline_payment_is_enabled'];

            /* Business */
            $_POST['business_invoice_is_enabled'] = (bool) $_POST['business_invoice_is_enabled'];
            $_POST['business_invoice_nr_prefix'] = trim(query_clean($_POST['business_invoice_nr_prefix']));
            $_POST['business_name'] = trim(query_clean($_POST['business_name']));
            $_POST['business_address'] = trim(query_clean($_POST['business_address']));
            $_POST['business_city'] = trim(query_clean($_POST['business_city']));
            $_POST['business_county'] = trim(query_clean($_POST['business_county']));
            $_POST['business_zip'] = trim(query_clean($_POST['business_zip']));
            $_POST['business_country'] = trim(query_clean($_POST['business_country']));
            $_POST['business_email'] = trim(query_clean($_POST['business_email']));
            $_POST['business_phone'] = trim(query_clean($_POST['business_phone']));
            $_POST['business_tax_type'] = trim(query_clean($_POST['business_tax_type']));
            $_POST['business_tax_id'] = trim(query_clean($_POST['business_tax_id']));
            $_POST['business_custom_key_one'] = trim(query_clean($_POST['business_custom_key_one']));
            $_POST['business_custom_value_one'] = trim(query_clean($_POST['business_custom_value_one']));
            $_POST['business_custom_key_two'] = trim(query_clean($_POST['business_custom_key_two']));
            $_POST['business_custom_value_two'] = trim(query_clean($_POST['business_custom_value_two']));

            /* Others */
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_store'] = isset($_POST['is_main_store']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;

            $hours = [];
            foreach([1, 2, 3, 4, 5, 6, 7] as $key) {
                $hours[$key] = [];

                $_POST['hours'][$key]['is_enabled'] = isset($_POST['hours'][$key]['is_enabled']);
                $_POST['hours'][$key]['hours'] = trim(query_clean($_POST['hours'][$key]['hours']));

                $hours[$key] = [
                    'is_enabled' => $_POST['hours'][$key]['is_enabled'],
                    'hours' => $_POST['hours'][$key]['hours'],
                ];
            }

            /* Make sure the socials sent are proper */
            $socials = require APP_PATH . 'includes/s/socials.php';

            foreach($_POST['socials'] as $key => $value) {

                if(!array_key_exists($key, $socials)) {
                    unset($_POST['socials'][$key]);
                } else {
                    $_POST['socials'][$key] = input_clean($_POST['socials'][$key], $socials[$key]['max_length']);
                }

            }

            $_POST['title'] = query_clean($_POST['title'], 70);
            $_POST['meta_description'] = query_clean($_POST['meta_description'], 160);
            $_POST['meta_keywords'] = query_clean($_POST['meta_keywords'], 160);
            $fonts = require APP_PATH . 'includes/s/fonts.php';
            $_POST['font_family'] = array_key_exists($_POST['font_family'], $fonts) ? query_clean($_POST['font_family']) : false;
            $_POST['font_size'] = (int) $_POST['font_size'] < 14 || (int) $_POST['font_size'] > 22 ? 16 : (int) $_POST['font_size'];
            $_POST['display_share_buttons'] = (int) isset($_POST['display_share_buttons']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if(
                ($_POST['url'] && $this->user->plan_settings->custom_url_is_enabled && $_POST['url'] != $store->url)
                || ($store->domain_id != $_POST['domain_id'])
            ) {

                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_store = database()->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_store) {
                    Alerts::add_error(l('store.error_message.url_exists'));
                }

                /* Make sure the custom url meets the requirements */
                if(mb_strlen($_POST['url']) < ($this->user->plan_settings->url_minimum_characters ?? 1)) {
                    Alerts::add_error(sprintf(l('store.error_message.url_minimum_characters'), ($this->user->plan_settings->url_minimum_characters ?? 1)));
                }

                if(mb_strlen($_POST['url']) > ($this->user->plan_settings->url_maximum_characters ?? 64)) {
                    Alerts::add_error(sprintf(l('store.error_message.url_maximum_characters'), ($this->user->plan_settings->url_maximum_characters ?? 64)));
                }
            }

            $logo = \Altum\Uploads::process_upload($store->logo, 'store_logos', 'logo', 'logo_remove', settings()->stores->logo_size_limit);
            $favicon = \Altum\Uploads::process_upload($store->favicon, 'store_favicons', 'favicon', 'favicon_remove', settings()->stores->favicon_size_limit);
            $image = \Altum\Uploads::process_upload($store->image, 'store_images', 'image', 'image_remove', settings()->stores->image_size_limit);
            $opengraph = \Altum\Uploads::process_upload($store->opengraph, 'store_opengraph', 'opengraph', 'opengraph_remove', settings()->stores->opengraph_size_limit);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = json_encode([
                    'title' => $_POST['title'],
                    'meta_description' => $_POST['meta_description'],
                    'meta_keywords' => $_POST['meta_keywords'],
                    'font_family' => $_POST['font_family'],
                    'font_size' => $_POST['font_size'],
                    'display_share_buttons' => $_POST['display_share_buttons']
                ]);
                $details = json_encode([
                    'address' => $_POST['address'],
                    'phone' => $_POST['phone'],
                    'website' => $_POST['website'],
                    'email' => $_POST['email'],
                    'hours' => $hours
                ]);
                $socials = json_encode($_POST['socials']);
                $ordering = json_encode([
                    'on_premise_is_enabled' => $_POST['ordering_on_premise_is_enabled'],
                    'delivery_is_enabled' => $_POST['ordering_delivery_is_enabled'],
                    'takeaway_is_enabled' => $_POST['ordering_takeaway_is_enabled'],
                    'on_premise_minimum_value' => $_POST['ordering_on_premise_minimum_value'],
                    'delivery_minimum_value' => $_POST['ordering_delivery_minimum_value'],
                    'delivery_cost' => $_POST['ordering_delivery_cost'],
                    'delivery_free_minimum_value' => $_POST['ordering_delivery_free_minimum_value'],
                    'takeaway_minimum_value' => $_POST['ordering_takeaway_minimum_value'],
                ]);
                $payment_processors = json_encode([
                    'paypal_is_enabled' => $_POST['paypal_is_enabled'],
                    'paypal_mode' => $_POST['paypal_mode'],
                    'paypal_client_id' => $_POST['paypal_client_id'],
                    'paypal_secret' => $_POST['paypal_secret'],

                    'stripe_is_enabled' => $_POST['stripe_is_enabled'],
                    'stripe_publishable_key' => $_POST['stripe_publishable_key'],
                    'stripe_secret_key' => $_POST['stripe_secret_key'],
                    'stripe_webhook_secret' => $_POST['stripe_webhook_secret'],

                    'mollie_is_enabled' => $_POST['mollie_is_enabled'],
                    'mollie_api_key' => $_POST['mollie_api_key'],

                    'offline_payment_is_enabled' => $_POST['offline_payment_is_enabled'],
                ]);
                $business = json_encode([
                    'invoice_is_enabled' => $_POST['business_invoice_is_enabled'],
                    'invoice_nr_prefix' => $_POST['business_invoice_nr_prefix'],
                    'name' => $_POST['business_name'],
                    'address' => $_POST['business_address'],
                    'city' => $_POST['business_city'],
                    'county' => $_POST['business_county'],
                    'zip' => $_POST['business_zip'],
                    'country' => $_POST['business_country'],
                    'email' => $_POST['business_email'],
                    'phone' => $_POST['business_phone'],
                    'tax_type' => $_POST['business_tax_type'],
                    'tax_id' => $_POST['business_tax_id'],
                    'custom_key_one' => $_POST['business_custom_key_one'],
                    'custom_value_one' => $_POST['business_custom_value_one'],
                    'custom_key_two' => $_POST['business_custom_key_two'],
                    'custom_value_two' => $_POST['business_custom_value_two'],
                ]);

                if(!$_POST['url']) {
                    $is_existing_store = true;

                    /* Generate random url if not specified */
                    while($is_existing_store) {
                        $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_store = database()->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }

                }

                /* Database query */
                db()->where('store_id', $store->store_id)->update('stores', [
                    'domain_id' => $_POST['domain_id'],
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'settings' => $settings,
                    'details' => $details,
                    'socials' => $socials,
                    'currency' => $_POST['currency'],
                    'password' => $_POST['password'],
                    'timezone' => $_POST['timezone'],
                    'custom_css' => $_POST['custom_css'],
                    'custom_js' => $_POST['custom_js'],
                    'is_se_visible' => $_POST['is_se_visible'],
                    'is_removed_branding' => $_POST['is_removed_branding'],
                    'email_reports_is_enabled' => $_POST['email_reports_is_enabled'],
                    'email_orders_is_enabled' => $_POST['email_orders_is_enabled'],
                    'ordering' => $ordering,
                    'payment_processors' => $payment_processors,
                    'business' => $business,
                    'logo' => $logo,
                    'favicon' => $favicon,
                    'image' => $image,
                    'opengraph' => $opengraph,
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_store']) {

                    /* If the main status page of a particular domain is changing, update the old domain as well to "free" it */
                    if($_POST['domain_id'] != $store->domain_id) {
                        /* Database query */
                        db()->where('domain_id', $store->domain_id)->update('domains', [
                            'store_id' => null,
                            'last_datetime' => \Altum\Date::$date,
                        ]);
                    }

                    /* Database query */
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', [
                        'store_id' => $store_id,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Clear the cache */
                    cache()->deleteItems([
                        'domains?user_id=' . $this->user->user_id,
                        'domain?domain_id=' . $store->domain_id,
                        'domain?domain_id=' . $_POST['domain_id'],
                        'domain?host=' . md5($domains[$store->domain_id]->host ?? ''),
                        'domain?host=' . md5($domains[$_POST['domain_id']]->host ?? ''),
                    ]);
                    cache()->deleteItemsByTag('domains?user_id=' . $this->user->user_id);
                }

                /* Update old main custom domain if needed */
                if(!$_POST['is_main_store'] && $store->domain_id && $domains[$store->domain_id]->store_id == $store->store_id) {
                    /* Database query */
                    db()->where('domain_id', $store->domain_id)->update('domains', [
                        'store_id' => null,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Clear the cache */
                    cache()->deleteItems([
                        'domains?user_id=' . $this->user->user_id,
                        'domain?domain_id=' . $store->domain_id,
                        'domain?domain_id=' . $_POST['domain_id'],
                        'domain?host=' . md5($domains[$store->domain_id]->host ?? ''),
                        'domain?host=' . md5($domains[$_POST['domain_id']]->host ?? ''),
                    ]);
                    cache()->deleteItemsByTag('domains?user_id=' . $this->user->user_id);
                }

                /* Clear the cache */
                cache()->deleteItemsByTag('store_id=' . $store->store_id);
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('store-update/' . $store->store_id);
            }

        }

        /* Establish the account sub menu view */
        $data = [
            'store_id' => $store->store_id,
            'resource_name' => $store->name,
            'external_url' => $store->full_url
        ];
        $app_sub_menu = new \Altum\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('store_update.title'), $store->name));

        /* Prepare the view */
        $data = [
            'store' => $store,
            'domains' => $domains
        ];

        $view = new \Altum\View('store-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
