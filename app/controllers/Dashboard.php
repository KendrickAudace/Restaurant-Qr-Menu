<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'domain_id'], ['name'], ['store_id', 'last_datetime', 'datetime', 'pageviews', 'name', 'orders']));
        $filters->set_default_order_by($this->user->preferences->stores_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = \Altum\Cache::cache_function_result('stores_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('stores', 'count(*)');
        });
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('dashboard?' . $filters->get_get() . '&page=%d')));

        /* Get the stores */
        $stores = [];
        $stores_result = database()->query("
            SELECT
                *
            FROM
                `stores`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $stores_result->fetch_object()) {

            /* Generate the store full URL base */
            $row->full_url = (new \Altum\Models\Store())->get_store_full_url($row, $this->user, $domains);

            $stores[] = $row;
        }

        /* Get some extra data for the widgets */
        $stores_statistics = \Altum\Cache::cache_function_result('stores_statistics?user_id=' . $this->user->user_id, null, function() {
            return database()->query("SELECT COUNT(*) AS `stores`, SUM(`pageviews`) AS `pageviews`, SUM(`orders`) AS `orders` FROM `stores` WHERE `user_id` = {$this->user->user_id}")->fetch_object();
        }, 60 * 60 * 12);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'stores' => $stores,
            'total_stores' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'domains' => $domains,
            'stores_statistics' => $stores_statistics,
        ];

        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
