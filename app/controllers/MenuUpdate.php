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

class MenuUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.menus')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $menu_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$menu = db()->where('menu_id', $menu_id)->where('user_id', $this->user->user_id)->getOne('menus')) {
            redirect('dashboard');
        }

        $store = db()->where('store_id', $menu->store_id)->where('user_id', $this->user->user_id)->getOne('stores');

        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url'] && $_POST['url'] != $menu->url) {

                if(db()->where('store_id', $store->store_id)->where('url', $_POST['url'])->getOne('menus', ['menu_id'])) {
                    Alerts::add_error(l('menu.error_message.url_exists'));
                }

            }

            $image = \Altum\Uploads::process_upload($menu->image, 'menu_images', 'image', 'image_remove', settings()->stores->menu_image_size_limit);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(db()->where('store_id', $store->store_id)->where('url', $_POST['url'])->getOne('menus', ['menu_id'])) {
                        $_POST['url'] = string_generate(10);
                    }
                }

                /* Database query */
                db()->where('menu_id', $menu->menu_id)->update('menus', [
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'image' => $image,
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                cache()->deleteItemsByTag('store_id=' . $store->store_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('menu-update/' . $menu->menu_id);
            }

        }

        /* Establish the account sub menu view */
        $data = [
            'menu_id' => $menu->menu_id,
            'resource_name' => $menu->name,
            'external_url' => $store->full_url . $menu->url
        ];
        $app_sub_menu = new \Altum\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('menu_update.title'), $menu->name));

        /* Prepare the view */
        $data = [
            'store' => $store,
            'menu' => $menu
        ];

        $view = new \Altum\View('menu-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
