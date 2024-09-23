<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class Item extends Model {

    public function get_item_by_store_id_and_item_id($store_id, $item_id) {

        /* Get the item */
        $item = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = cache()->getItem('s_item?store_id=' . $store_id . '&item_id=' . $item_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item = database()->query("SELECT * FROM `items` WHERE `store_id` = {$store_id} AND `item_id` = '{$item_id}'")->fetch_object() ?? null;

            cache()->save(
                $cache_instance->set($item)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item = $cache_instance->get();

        }

        return $item;

    }

    public function get_item_by_store_id_and_url($store_id, $url) {

        /* Get the item */
        $item = null;

        /* Try to check if the store posts exists via the cache */
        $cache_instance = cache()->getItem('s_item?store_id=' . $store_id . '&url=' . $url);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $item = database()->query("SELECT * FROM `items` WHERE `store_id` = {$store_id} AND `url` = '{$url}'")->fetch_object() ?? null;

            cache()->save(
                $cache_instance->set($item)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $item = $cache_instance->get();

        }

        return $item;

    }

    public function get_items_by_store_id_and_category_id($store_id, $category_id) {

        /* Get the store posts */
        $items = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = cache()->getItem('s_items?store_id=' . $store_id . '&category_id=' . $category_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $items_result = database()->query("
                SELECT 
                    *
                FROM 
                    `items` 
                WHERE 
                    `store_id` = {$store_id}
                    AND `category_id` = {$category_id} 
                    AND `is_enabled` = 1
                ORDER BY `order`
            ");
            while($row = $items_result->fetch_object()) $items[] = $row;

            cache()->save(
                $cache_instance->set($items)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $items = $cache_instance->get();

        }

        return $items;

    }

    public function get_items_by_store_id_and_menu_id($store_id, $menu_id) {

        /* Get the store posts */
        $items = [];

        /* Try to check if the store posts exists via the cache */
        $cache_instance = cache()->getItem('s_items?store_id=' . $store_id . '&menu_id=' . $menu_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $items_result = database()->query("
                SELECT 
                    *
                FROM 
                    `items` 
                WHERE 
                    `store_id` = {$store_id}
                    AND `menu_id` = {$menu_id} 
                    AND `is_enabled` = 1
                ORDER BY `order`
            ");
            while($row = $items_result->fetch_object()) $items[] = $row;

            cache()->save(
                $cache_instance->set($items)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('store_id=' . $store_id)
            );

        } else {

            /* Get cache */
            $items = $cache_instance->get();

        }

        return $items;

    }

    public function duplicate($item, $item_id, $category_id, $menu_id, $store_id, $user_id) {
        /* Get all extras to duplicate */
        $item_extras = db()->where('item_id', $item->item_id)->get('items_extras');

        /* Duplicate all of them */
        foreach($item_extras as $item_extra) {
            /* Insert to database */
            db()->insert('items_extras', [
                'item_id' => $item_id,
                'category_id' => $category_id,
                'menu_id' => $menu_id,
                'store_id' => $store_id,
                'user_id' => $user_id,
                'name' => $item_extra->name,
                'description' => $item_extra->description,
                'price' => $item_extra->price,
                'is_enabled' => $item_extra->is_enabled,
                'datetime' => \Altum\Date::$date,
            ]);
        }

        /* Get all extras to duplicate */
        $item_options = db()->where('item_id', $item->item_id)->get('items_options');

        /* Duplicate all of them */
        $item_options_old_to_new = [];
        foreach($item_options as $item_option) {
            /* Insert to database */
            $item_option_id = db()->insert('items_options', [
                'item_id' => $item_id,
                'category_id' => $category_id,
                'menu_id' => $menu_id,
                'store_id' => $store_id,
                'user_id' => $user_id,
                'name' => $item_option->name,
                'options' => $item_option->options,
                'datetime' => \Altum\Date::$date,
            ]);

            $item_options_old_to_new[$item_option->item_option_id] = $item_option_id;
        }
        /* Get all variants to duplicate */
        $item_variants = db()->where('item_id', $item->item_id)->get('items_variants');

        foreach($item_variants as $item_variant) {
            $item_options_ids = json_decode($item_variant->item_options_ids);
            $new_item_options_ids = [];

            foreach($item_options_ids as $item_options_set) {
                $new_item_options_ids[] = [
                    'item_option_id' => $item_options_old_to_new[$item_options_set->item_option_id],
                    'option' => $item_options_set->option
                ];
            }

            /* Insert to database */
            db()->insert('items_variants', [
                'item_id' => $item_id,
                'category_id' => $category_id,
                'menu_id' => $menu_id,
                'store_id' => $store_id,
                'user_id' => $user_id,
                'item_options_ids' => json_encode($new_item_options_ids),
                'price' => $item_variant->price,
                'is_enabled' => $item_variant->is_enabled,
                'datetime' => \Altum\Date::$date,
            ]);
        }
    }
}
