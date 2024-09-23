<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class AdminIndex extends Controller {

    public function index() {

        $stores = db()->getValue('stores', 'count(`store_id`)');
        $orders = db()->getValue('orders', 'count(`order_id`)');
        $menus = db()->getValue('menus', 'count(`menu_id`)');
        $items = db()->getValue('items', 'count(`item_id`)');
        $domains = db()->getValue('domains', 'count(`domain_id`)');
        $users = db()->getValue('users', 'count(`user_id`)');

        /* Widgets stats: current month */
        extract(\Altum\Cache::cache_function_result('admin_dashboard_current_month', null, function() {
            return [
                'stores_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('stores', 'count(*)'),
                'orders_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('orders', 'count(*)'),
                'menus_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('menus', 'count(*)'),
                'items_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('items', 'count(*)'),
                'domains_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('domains', 'count(*)'),
                'users_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('users', 'count(*)'),
                'payments_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'count(*)') : 0,
                'payments_amount_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'sum(`total_amount_default_currency`)') : 0,
            ];
        }, 86400));

        /* Get currently active users */
        $fifteen_minutes_ago_datetime = (new \DateTime())->modify('-15 minutes')->format('Y-m-d H:i:s');
        $active_users = db()->where('last_activity', $fifteen_minutes_ago_datetime, '>=')->getValue('users', 'COUNT(*)');

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $payments = db()->getValue('payments', 'count(`id`)');
            $payments_total_amount = db()->getValue('payments', 'sum(`total_amount_default_currency`)');
        } else {
            $payments = $payments_total_amount = 0;
        }

        if(settings()->internal_notifications->admins_is_enabled) {
            $internal_notifications = db()->where('for_who', 'admin')->orderBy('internal_notification_id', 'DESC')->get('internal_notifications', 5);

            $should_set_all_read = false;
            foreach($internal_notifications as $notification) {
                if(!$notification->is_read) $should_set_all_read = true;
            }

            if($should_set_all_read) {
                db()->where('for_who', 'admin')->update('internal_notifications', [
                    'is_read' => 1,
                    'read_datetime' => \Altum\Date::$date,
                ]);
            }
        }

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Main View */
        $data = [
            'stores' => $stores,
            'orders' => $orders,
            'menus' => $menus,
            'items' => $items,
            'domains' => $domains,
            'users' => $users,
            'payments' => $payments,
            'payments_total_amount' => $payments_total_amount,

            'stores_current_month' => $stores_current_month,
            'orders_current_month' => $orders_current_month,
            'menus_current_month' => $menus_current_month,
            'items_current_month' => $items_current_month,
            'domains_current_month' => $domains_current_month,
            'users_current_month' => $users_current_month,
            'payments_current_month' => $payments_current_month,
            'payments_amount_current_month' => $payments_amount_current_month,

            'plans' => $plans,
            'active_users' => $active_users,
            'internal_notifications' => $internal_notifications ?? [],
        ];

        $view = new \Altum\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}