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

class ItemUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.items')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $item_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item = db()->where('item_id', $item_id)->where('user_id', $this->user->user_id)->getOne('items')) {
            redirect('dashboard');
        }

        $category = db()->where('category_id', $item->category_id)->where('user_id', $this->user->user_id)->getOne('categories', ['category_id', 'url']);
        $menu = db()->where('menu_id', $item->menu_id)->where('user_id', $this->user->user_id)->getOne('menus', ['menu_id', 'url']);
        $store = db()->where('store_id', $item->store_id)->where('user_id', $this->user->user_id)->getOne('stores', ['store_id', 'domain_id', 'url', 'currency']);

        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['price'] = (float) trim(query_clean($_POST['price']));
            $_POST['variants_is_enabled'] = (int) isset($_POST['variants_is_enabled']);
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url'] && $_POST['url'] != $item->url) {

                if(db()->where('category_id', $item->category_id)->where('url', $_POST['url'])->getOne('items', ['item_id'])) {
                    Alerts::add_error(l('category.error_message.url_exists'));
                }

            }

            $image = \Altum\Uploads::process_upload($item->image, 'item_images', 'image', 'image_remove', settings()->stores->item_image_size_limit);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                if(!$_POST['url']) {
                    $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));

                    /* Generate random url if not specified */
                    while(db()->where('store_id', $category->store_id)->where('url', $_POST['url'])->getOne('categories', ['category_id'])) {
                        $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));
                    }
                }

                /* Database query */
                db()->where('item_id', $item->item_id)->update('items', [
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price'],
                    'image' => $image,
                    'variants_is_enabled' => $_POST['variants_is_enabled'],
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Clear the cache */
                cache()->deleteItemsByTag('store_id=' . $store->store_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('item-update/' . $item->item_id);
            }

        }

        /* Establish the account sub menu view */
        $data = [
            'item_id' => $item->item_id,
            'resource_name' => $item->name,
            'external_url' => $store->full_url . $menu->url . '/' . $category->url . '/' . $item->url
        ];
        $app_sub_menu = new \Altum\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('item_update.title'), $item->name));

        /* Prepare the view */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item
        ];

        $view = new \Altum\View('item-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
