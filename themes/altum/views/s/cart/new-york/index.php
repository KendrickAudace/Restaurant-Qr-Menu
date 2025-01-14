<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<div class="container mt-5">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= $data->store->full_url ?>"><?= l('s_store.breadcrumb') ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/chevron-right.svg') ?></div>
                </li>
                <li class="active" aria-current="page"><?= l('s_cart.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h3"><?= l('s_cart.header') ?></h1>

    <div class="mb-5 d-none" id="cart">
        <div id="cart_items"></div>
        <div id="cart_form"></div>
    </div>

    <div class="mb-5 d-none" id="empty_cart">
        <div class="d-flex flex-column align-items-center justify-content-center d-none">
            <img src="<?= ASSETS_FULL_URL . 'images/s/no_data.svg' ?>" class="col-8 col-md-6 col-lg-4 mb-3" alt="<?= l('s_cart.no_data') ?>" loading="lazy" />
            <h2 class="h4 text-muted"><?= l('s_cart.no_data') ?></h2>
            <p class="text-muted m-0"><?= l('s_cart.no_data_help') ?></p>
        </div>
    </div>

    <div class="mb-5 d-none" id="order_done">
        <div class="d-flex flex-column align-items-center justify-content-center d-none">
            <img src="<?= ASSETS_FULL_URL . 'images/s/order_done.svg' ?>" class="col-8 col-md-6 col-lg-4 mb-3" alt="<?= l('s_cart.order_done') ?>" loading="lazy" />
            <h2 class="h4 text-muted"><?= l('s_cart.order_done') ?></h2>
            <p class="text-muted"><?= l('s_cart.order_done_help') ?></p>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    let cart_name = <?= json_encode($data->store->store_id . '_cart') ?>;

    let process_cart = () => {

        /* Some needed variables */
        let language = {
            remove: <?= json_encode(l('s_cart.remove')) ?>,
            order: <?= json_encode(l('s_cart.order')) ?>,
            total: <?= json_encode(l('s_cart.total')) ?>,
            quantity: <?= json_encode(l('s_cart.quantity')) ?>,

            name: <?= json_encode(l('s_cart.name')) ?>,
            number: <?= json_encode(l('s_cart.number')) ?>,
            phone: <?= json_encode(l('s_cart.phone')) ?>,
            address: <?= json_encode(l('s_cart.address')) ?>,
            message: <?= json_encode(l('s_cart.message')) ?>,
            message_help: <?= json_encode(l('s_cart.message_help')) ?>,

            ordering_on_premise_minimum_value: <?= json_encode(sprintf(l('s_cart.order_minimum_value'), $data->store->ordering->on_premise_minimum_value, $data->store->currency)) ?>,
            ordering_takeaway_minimum_value: <?= json_encode(sprintf(l('s_cart.order_minimum_value'), $data->store->ordering->takeaway_minimum_value, $data->store->currency)) ?>,
            ordering_delivery_minimum_value: <?= json_encode(sprintf(l('s_cart.order_minimum_value'), $data->store->ordering->delivery_minimum_value, $data->store->currency)) ?>,
            ordering_delivery_cost: <?= json_encode(l('s_cart.ordering_delivery_cost')) ?>,
            ordering_delivery_cost_help: <?= json_encode(sprintf(l('s_cart.ordering_delivery_cost_help'), $data->store->ordering->delivery_free_minimum_value, $data->store->currency)) ?>,

            type: <?= json_encode(l('s_cart.type')) ?>,
            type_on_premise: <?= json_encode(l('s_cart.type_on_premise')) ?>,
            type_takeaway: <?= json_encode(l('s_cart.type_takeaway')) ?>,
            type_delivery: <?= json_encode(l('s_cart.type_delivery')) ?>,

            processor: <?= json_encode(l('s_cart.processor')) ?>,
            processor_paypal: <?= json_encode(l('s_cart.processor_paypal')) ?>,
            processor_stripe: <?= json_encode(l('s_cart.processor_stripe')) ?>,
            processor_mollie: <?= json_encode(l('s_cart.processor_mollie')) ?>,
            processor_offline_payment: <?= json_encode(l('s_cart.processor_offline_payment')) ?>,
        };

        let token = <?= json_encode(\Altum\Csrf::get()) ?>;
        let currency = <?= json_encode($data->store->currency) ?>;
        let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];
        let total = 0;

        /* Enabled ordering types */
        let ordering_on_premise_is_enabled = parseInt(<?= json_encode($data->store->ordering->on_premise_is_enabled) ?>);
        let ordering_takeaway_is_enabled = parseInt(<?= json_encode($data->store->ordering->takeaway_is_enabled) ?>);
        let ordering_delivery_is_enabled = parseInt(<?= json_encode($data->store->ordering->delivery_is_enabled) ?>);
        let ordering_delivery_cost = parseFloat(<?= json_encode($data->store->ordering->delivery_cost) ?>);
        let ordering_delivery_free_minimum_value = parseFloat(<?= json_encode($data->store->ordering->delivery_free_minimum_value) ?>);
        let ordering_on_premise_minimum_value = parseFloat(<?= json_encode($data->store->ordering->on_premise_minimum_value) ?>);
        let ordering_takeaway_minimum_value = parseFloat(<?= json_encode($data->store->ordering->takeaway_minimum_value) ?>);
        let ordering_delivery_minimum_value = parseFloat(<?= json_encode($data->store->ordering->delivery_minimum_value) ?>);
        let paypal_is_enabled = <?= json_encode($data->store->payment_processors->paypal_is_enabled && $data->store_user->plan_settings->online_payments_is_enabled) ?>;
        let stripe_is_enabled = <?= json_encode($data->store->payment_processors->stripe_is_enabled && $data->store_user->plan_settings->online_payments_is_enabled) ?>;
        let mollie_is_enabled = <?= json_encode($data->store->payment_processors->mollie_is_enabled && $data->store_user->plan_settings->online_payments_is_enabled) ?>;
        let offline_payment_is_enabled = <?= json_encode($data->store->payment_processors->offline_payment_is_enabled) ?>;

        /* Display the cart or empty cart message */
        if(cart.length) {

            /* cart_items handler */
            let cart_items_handler = () => {
                cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

                let html = ``;

                for (let item of cart) {

                    /* Generate the item variants */
                    let variant_options_html = '';

                    if(item.item_variant_options) {
                        for(let item_variant_option of item.item_variant_options) {
                            variant_options_html += `
                            <div>
                                <small class="text-muted">&#8226; <span class="font-weight-bold">${item_variant_option.name}:</span> ${item_variant_option.option}</small>
                            </div>
                            `;
                        }
                    }

                    /* Generate extras html */
                    let extras_html = '';

                    for (let item_extra of item.item_extras) {
                        extras_html += `
                        <div>
                            <small class="text-muted">&#8226; ${item_extra.name}</small>
                        </div>
                        `;
                    }

                    html += `
                        <div id="${'item_' + item.item_generated_id}" class="my-3 rounded p-3 bg-gray-50">
                            <div class="row">
                                <div class="col-8 col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="store-cart-image-wrapper mr-3">
                                            ${item.full_image ? `<img src="${item.full_image}" class="store-cart-image-background" loading="lazy" />` : ''}
                                        </div>

                                        <div class="d-flex flex-column">
                                            <div class="mr-3">
                                                <a href="${item.full_url}" class="font-weight-bold" target="_blank">${item.name}</a>
                                            </div>

                                            <div class="d-flex flex-column flex-lg-row">
                                                <div class="ml-lg-3 mb-3 mb-lg-0 mr-lg-3">
                                                    ${variant_options_html}
                                                </div>

                                                <div>
                                                    ${extras_html}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-3 d-flex flex-lg-column justify-content-lg-center order-1 order-lg-0 mt-3 mt-lg-0">
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted mr-3">${language.quantity}</span>

                                        <div class="mr-3">
                                            <input type="number" min="1" max="25" value="${item.quantity}" name="" class="form-control form-control-sm item-quantity" data-item-generated-id="${item.item_generated_id}" />
                                        </div>

                                        <div>
                                            <a href="#" class="item-delete text-decoration-none" role="button" title="${language.remove}" data-item-generated-id="${item.item_generated_id}">
                                                <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/trash.svg') ?></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 col-lg-3 d-flex align-items-center justify-content-end order-0 order-lg-1">
                                    <div>
                                       <span class="font-weight-bold">${nr(item.final_price * item.quantity)}</span> <span class="text-muted">${currency}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    /* Add price to final */
                    total += parseFloat(item.final_price * item.quantity);
                }

                /* Add the delivery html */
                html += `
                <div class="my-3 rounded p-3 bg-gray-50 d-none" id="ordering_delivery_cost" data-ordering-delivery-cost="${ordering_delivery_cost}">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-flex align-items-center">
                                <span class="font-weight-bold">${language.ordering_delivery_cost}</span>
                            </div>
                            ${ordering_delivery_free_minimum_value > 0 ? `<small class="text-muted">${language.ordering_delivery_cost_help}</small>` : ''}
                        </div>

                        <div class="col-4 col-lg-4 d-flex align-items-center justify-content-end order-0 order-lg-1">
                            <div>
                               <span class="font-weight-bold">${nr(ordering_delivery_cost)}</span> <span class="text-muted">${currency}</span>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                /* Add the total row */
                html += `
                <div class="d-flex justify-content-between my-4 p-3 rounded bg-primary-100">
                    <div class="font-weight-bold">
                        ${language.total}
                    </div>

                    <div class="d-none" id="total">
                        <span class="font-weight-bold">${nr(total)}</span> <span class="text-muted">${currency}</span>
                    </div>

                    <div class="d-none" id="total_with_delivery_cost">
                        <span class="font-weight-bold">${nr(total + ordering_delivery_cost)}</span> <span class="text-muted">${currency}</span>
                    </div>
                </div>
                `;

                document.querySelector('#cart_items').innerHTML = html;
            }

            cart_items_handler();

            /* cart_form handler */
            let cart_form_handler = () => {
                let html = ``;

                /* Generate the inputs with the selected values */
                let hidden_inputs = '';
                let index = 0;

                for(let item of cart) {

                    hidden_inputs += `
                        <input type="hidden" name="items[${index}][item_id]" value="${item.item_id}" />
                        <input type="hidden" name="items[${index}][item_variant_id]" value="${item.item_variant_id ? item.item_variant_id : ''}" />
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}" />
                    `;

                    for(let item_extra of item.item_extras) {
                        hidden_inputs += `
                            <input type="hidden" name="items[${index}][extras][]" value="${item_extra.item_extra_id}" />
                        `;
                    }

                    index++;
                }


                html += `
                    <form action="" method="post" role="form">
                        <div class="my-4">
                            <input type="hidden" name="token" value="${token}" />
                            ${hidden_inputs}

                            <div class="form-group">
                                <label for="type">${language.type}</label>
                                <div class="row">
                                    ${ordering_on_premise_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="type" value="on_premise" class="custom-control-input" checked="checked" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.type_on_premise}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}

                                    ${ordering_takeaway_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="type" value="takeaway" class="custom-control-input" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.type_takeaway}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}

                                    ${ordering_delivery_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="type" value="delivery" class="custom-control-input" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.type_delivery}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="processor">${language.processor}</label>
                                <div class="row">
                                    ${offline_payment_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="processor" value="offline_payment" class="custom-control-input" checked="checked" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.processor_offline_payment}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}

                                    ${stripe_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="processor" value="stripe" class="custom-control-input" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.processor_stripe}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}

                                    ${paypal_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="processor" value="paypal" class="custom-control-input" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.processor_paypal}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}

                                    ${mollie_is_enabled ? `
                                    <label class="col-12 col-lg custom-radio-box">
                                        <input type="radio" name="processor" value="mollie" class="custom-control-input" required="required">

                                        <div class="card">
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <div class="card-title mb-0">${language.processor_mollie}</div>
                                            </div>
                                        </div>
                                    </label>
                                    ` : ``}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">${language.name}</label>
                                <input type="text" id="name" name="name" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="phone">${language.phone}</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="address">${language.address}</label>
                                <input type="text" id="address" name="address" class="form-control" value="" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="number">${language.number}</label>
                                <input type="number" min="0" step="1" id="number" name="number" class="form-control" value="1" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="message">${language.message}</label>
                                <textarea id="message" class="form-control" name="message"></textarea>
                                <small class="text-muted">${language.message_help}</small>
                            </div>
                        </div>

                        <button type="submit" name="submit" id="order_submit" class="btn btn-block btn-primary my-4">${language.order}</button>

                        <div class="my-4 alert alert-info" id="order_minimum_value"><div>

                    </form>
                `;

                document.querySelector('#cart_form').innerHTML = html;

                /* Type hanlder */
                let initiate_type_handler = () => {
                    let type = document.querySelector('input[name="type"]:checked')?.value;

                    switch(type) {
                        case 'on_premise':
                            document.querySelector('#phone').parentElement.classList.add('d-none');
                            document.querySelector('#phone').removeAttribute('required');

                            document.querySelector('#address').parentElement.classList.add('d-none');
                            document.querySelector('#address').removeAttribute('required');

                            document.querySelector('#number').parentElement.classList.remove('d-none');
                            document.querySelector('#number').setAttribute('required', 'required');

                            if(total < ordering_on_premise_minimum_value) {
                                document.querySelector('#order_minimum_value').innerText = language.ordering_on_premise_minimum_value;
                                document.querySelector('#order_minimum_value').classList.remove('d-none');
                                document.querySelector('#order_submit').classList.add('d-none');
                            } else {
                                document.querySelector('#order_minimum_value').classList.add('d-none');
                                document.querySelector('#order_submit').classList.remove('d-none');
                            }

                            /* Display total */
                            document.querySelector('#total').classList.remove('d-none');
                            document.querySelector('#total_with_delivery_cost').classList.add('d-none');
                            document.querySelector('#ordering_delivery_cost').classList.add('d-none');

                            break;

                        case 'takeaway':
                            document.querySelector('#phone').parentElement.classList.remove('d-none');
                            document.querySelector('#phone').setAttribute('required', 'required');

                            document.querySelector('#address').parentElement.classList.add('d-none');
                            document.querySelector('#address').removeAttribute('required');

                            document.querySelector('#number').parentElement.classList.add('d-none');
                            document.querySelector('#number').removeAttribute('required');

                            if(total < ordering_takeaway_minimum_value) {
                                document.querySelector('#order_minimum_value').innerText = language.ordering_takeaway_minimum_value;
                                document.querySelector('#order_minimum_value').classList.remove('d-none');
                                document.querySelector('#order_submit').classList.add('d-none');
                            } else {
                                document.querySelector('#order_minimum_value').classList.add('d-none');
                                document.querySelector('#order_submit').classList.remove('d-none');
                            }

                            /* Display total */
                            document.querySelector('#total').classList.remove('d-none');
                            document.querySelector('#total_with_delivery_cost').classList.add('d-none');
                            document.querySelector('#ordering_delivery_cost').classList.add('d-none');

                            break;

                        case 'delivery':
                            document.querySelector('#phone').parentElement.classList.remove('d-none');
                            document.querySelector('#phone').setAttribute('required', 'required');

                            document.querySelector('#address').parentElement.classList.remove('d-none');
                            document.querySelector('#address').setAttribute('required', 'required');

                            document.querySelector('#number').parentElement.classList.add('d-none');
                            document.querySelector('#number').removeAttribute('required');

                            if(total < ordering_delivery_minimum_value) {
                                document.querySelector('#order_minimum_value').innerText = language.ordering_delivery_minimum_value;
                                document.querySelector('#order_minimum_value').classList.remove('d-none');
                                document.querySelector('#order_submit').classList.add('d-none');
                            } else {
                                document.querySelector('#order_minimum_value').classList.add('d-none');
                                document.querySelector('#order_submit').classList.remove('d-none');
                            }

                            /* display delivery cost if needed */
                            if(ordering_delivery_cost > 0 && ((ordering_delivery_free_minimum_value !== 0  && total < ordering_delivery_free_minimum_value) || ordering_delivery_free_minimum_value == 0)) {
                                document.querySelector('#ordering_delivery_cost').classList.remove('d-none');

                                /* Display total */
                                document.querySelector('#total').classList.add('d-none');
                                document.querySelector('#total_with_delivery_cost').classList.remove('d-none');
                            } else {
                                document.querySelector('#ordering_delivery_cost').classList.add('d-none');

                                /* Display total */
                                document.querySelector('#total').classList.remove('d-none');
                                document.querySelector('#total_with_delivery_cost').classList.add('d-none');
                            }

                            break;
                    }

                };
                document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', initiate_type_handler));

                initiate_type_handler();
            }
            cart_form_handler();


            /* Delete handler */
            let initiate_delete_handler = () => {
                document.querySelectorAll(`div[id^='item_'] .item-delete`).forEach(element => {
                    element.addEventListener('click', event => {

                        let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

                        if(cart.length) {

                            let item_generated_id = element.getAttribute('data-item-generated-id');

                            /* Find the item to be deleted from the cart */
                            let found_item_index = cart.findIndex(item => item.item_generated_id == item_generated_id);

                            cart.splice(found_item_index, 1);

                            /* Save the localstorage */
                            localStorage.setItem(cart_name, JSON.stringify(cart));

                            process_cart();
                        }

                        event.preventDefault();
                    });
                });
            };
            initiate_delete_handler();

            /* Quantity handler */
            let initiate_quantity_handler = () => {
                document.querySelectorAll(`div[id^='item_'] .item-quantity`).forEach(element => {
                    element.addEventListener('change', event => {

                        let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

                        if(cart.length) {

                            let item_generated_id = element.getAttribute('data-item-generated-id');

                            /* Find the item to be deleted from the cart */
                            let found_item_index = cart.findIndex(item => item.item_generated_id == item_generated_id);

                            /* New quantity */
                            let new_quantity = event.currentTarget.value <= 0 || event.currentTarget.value > 25 ? 1 : parseFloat(event.currentTarget.value);

                            cart[found_item_index].quantity = new_quantity;

                            /* Save the localstorage */
                            localStorage.setItem(cart_name, JSON.stringify(cart));

                            process_cart();
                        }

                        event.preventDefault();
                    });
                });
            };
            initiate_quantity_handler();

            document.querySelector('#cart').classList.remove('d-none');
            document.querySelector('#empty_cart').classList.add('d-none');

        } else {
            document.querySelector('#empty_cart').classList.remove('d-none');
            document.querySelector('#cart').classList.add('d-none');
        }

    };


    /* Check if the order has been sent */
    let current_url = new URL(window.location.href);

    if(current_url.searchParams.get('order') == 'done') {

        /* Show the success message */
        document.querySelector('#order_done').classList.remove('d-none');

        localStorage.removeItem(cart_name);

        setTimeout(() => {
            current_url.searchParams.delete('order');
            current_url.searchParams.delete('page');
            location.replace(current_url.toString());
        }, 10000);

    } else {
        process_cart();
    }

    /* Listen for changes on the localstorage on other potential tabs */
    window.addEventListener('storage', () => {
        process_cart();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
