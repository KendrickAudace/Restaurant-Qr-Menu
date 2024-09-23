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
use Altum\Database;
use Altum\Title;

class ItemOptionUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.items')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $item_option_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$item_option = db()->where('item_option_id', $item_option_id)->where('user_id', $this->user->user_id)->getOne('items_options', '*')) {
            redirect('dashboard');
        }

        $item_option->options = json_decode($item_option->options);

        $item = db()->where('item_id', $item_option->item_id)->where('user_id', $this->user->user_id)->getOne('items', ['item_id', 'url']);
        $category = db()->where('category_id', $item_option->category_id)->where('user_id', $this->user->user_id)->getOne('categories', ['category_id', 'url']);
        $menu = db()->where('menu_id', $item_option->menu_id)->where('user_id', $this->user->user_id)->getOne('menus', ['menu_id', 'url']);
        $store = db()->where('store_id', $item_option->store_id)->where('user_id', $this->user->user_id)->getOne('stores', ['store_id', 'domain_id', 'url', 'currency']);

        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        if(!empty($_POST)) {
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['options'] = explode(',', query_clean($_POST['options']));
            $_POST['options'] = array_map('trim', $_POST['options']);
            $_POST['options'] = json_encode($_POST['options']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                $stmt = database()->prepare("UPDATE `items_options` SET `name` = ?, `options` = ?, `last_datetime` = ? WHERE `item_option_id` = ? AND `user_id` = ?");
                $stmt->bind_param('sssss', $_POST['name'], $_POST['options'], \Altum\Date::$date, $item_option->item_option_id, $this->user->user_id);
                $stmt->execute();
                $stmt->close();

                /* Clear the cache */
                cache()->deleteItemsByTag('store_id=' . $store->store_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('item-option-update/' . $item_option->item_option_id);
            }

        }

        /* Establish the account sub menu view */
        $data = [
            'item_option_id' => $item_option->item_option_id,
            'resource_name' => $item_option->name,
            'external_url' => $store->full_url . $menu->url . '/' . $category->url . '/' . $item->url
        ];
        $app_sub_menu = new \Altum\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('item_option_update.title'), $item_option->name));

        /* Prepare the view */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'item' => $item,
            'item_option' => $item_option
        ];

        $view = new \Altum\View('item-option-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
