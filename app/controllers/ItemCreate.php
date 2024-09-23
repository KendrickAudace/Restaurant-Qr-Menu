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

class ItemCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.items')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$category = db()->where('category_id', $category_id)->where('user_id', $this->user->user_id)->getOne('categories', ['category_id', 'menu_id', 'store_id', 'url'])) {
            redirect('dashboard');
        }

        $menu = db()->where('menu_id', $category->menu_id)->where('user_id', $this->user->user_id)->getOne('menus', ['menu_id', 'url']);

        $store = db()->where('store_id', $category->store_id)->where('user_id', $this->user->user_id)->getOne('stores', ['store_id', 'domain_id', 'url', 'currency']);

        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `items` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->items_limit != -1 && $total_rows >= $this->user->plan_settings->items_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('dashboard');
        }

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['price'] = (float) trim(query_clean($_POST['price']));

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                if(db()->where('category_id', $category->category_id)->where('url', $_POST['url'])->getOne('items', ['item_id'])) {
                    Alerts::add_error(l('item.error_message.url_exists'));
                }

            }

            $image = \Altum\Uploads::process_upload(null, 'item_images', 'image', 'image_remove', settings()->stores->item_image_size_limit);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                if(!$_POST['url']) {
                    $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));

                    /* Generate random url if not specified */
                    while(db()->where('category_id', $category->category_id)->where('url', $_POST['url'])->getOne('items', ['item_id'])) {
                        $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));
                    }
                }

                /* Database query */
                $stmt = database()->prepare("INSERT INTO `items` (`category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `price`, `datetime`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssssssss', $category->category_id, $menu->menu_id, $store->store_id, $this->user->user_id, $_POST['url'], $_POST['name'], $_POST['description'], $image, $_POST['price'], \Altum\Date::$date);
                $stmt->execute();
                $item_id = $stmt->insert_id;
                $stmt->close();

                /* Clear the cache */
                cache()->deleteItemsByTag('store_id=' . $store->store_id);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('item/' . $item_id);
            }

        }

        /* Set default values */
        $values = [
            'url' => $_POST['url'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? '',
        ];

        /* Prepare the view */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'category' => $category,
            'values' => $values
        ];

        $view = new \Altum\View('item-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
