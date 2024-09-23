<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="container">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row">
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-center align-items-center align-items-lg-start text-center text-lg-left">
                <div>
                    <span class="badge badge-pill badge-success mb-4">
                        <i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i><i class="fas fa-fw fa-star fa-sm text-warning"></i>
                        <?= l('index.stars') ?>
                    </span>
                </div>

                <h1 class="index-header mb-4" ><?= l('index.header') ?></h1>
                <p class="index-subheader"><?= sprintf(l('index.subheader'), '<span class="text-primary-800 font-weight-bold">', '</span>') ?></p>

                <ul class="list-style-none my-4">
                    <li class="d-flex align-items-center mb-2">
                        <i class="fas fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= l('index.feature.one') ?>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-2">
                        <i class="fas fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= l('index.feature.two') ?>
                        </div>
                    </li>

                    <li class="d-flex align-items-center mb-2">
                        <i class="fas fa-fw mr-2 fa-check-circle text-primary"></i>
                        <div class="text-muted">
                            <?= l('index.feature.three') ?>
                        </div>
                    </li>
                </ul>

                <div>
                    <a href="<?= url('register') ?>" class="btn btn-lg btn-primary index-button"><?= l('index.button') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-10 col-md-8 col-lg-4 offset-1 offset-md-2 mt-4 mt-lg-0">
                <img src="<?= ASSETS_URL_PATH . 'images/index/hero.png' ?>" class="img-fluid index-hero" />
            </div>
        </div>

    </div>
</div>

<?php if(settings()->stores->example_url): ?>
<div class="my-10"></div>

<div class="bg-primary-800 py-6">
    <div class="container">
        <div class="d-flex flex-column align-items-center">
            <div class="text-center">
                <h2 class="text-gray-50"><?= l('index.example.header') ?></h2>
                <p class="text-gray-100"><?= l('index.example.subheader') ?></p>
            </div>

            <div class="mt-4">
                <a href="<?= settings()->stores->example_url ?>" target="_blank">
                    <div class="index-qr rounded"></div>
                </a>
            </div>

            <div class="mt-5">
                <a href="<?= settings()->stores->example_url ?>" class="btn btn-light" target="_blank">
                    <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
                    <?= l('index.example.button') ?>
                </a>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<div class="my-10"></div>

<div class="container">
    <div class="text-center d-flex flex-column align-items-center mb-5">
        <h2 class="mt-2"><?= sprintf(l('index.demo.header'), '<span class="text-primary text-underline">', '</span>') ?></h2>
        <ul class="list-style-none d-flex flex-column flex-lg-row mt-4">
            <li class="d-flex align-items-baseline mb-2 mr-lg-4">
                <span class="font-weight-bold text-primary-800 h5 mr-1">1.</span>
                <div class="text-muted">
                    <?= l('index.demo.one') ?>
                </div>
            </li>

            <li class="d-flex align-items-baseline mb-2 mr-lg-4">
                <span class="font-weight-bold text-primary-800 h5 mr-1">2.</span>
                <div class="text-muted">
                    <?= l('index.demo.two') ?>
                </div>
            </li>

            <li class="d-flex align-items-baseline mb-2 mr-lg-4">
                <span class="font-weight-bold text-primary-800 h5 mr-1">3.</span>
                <div class="text-muted">
                    <?= l('index.demo.three') ?>
                </div>
            </li>
        </ul>
    </div>

    <img src="<?= ASSETS_URL_PATH . 'images/index/demo.png' ?>" class="img-fluid rounded shadow" loading="lazy" data-aos="fade-up" />
</div>

<div class="my-10"></div>

<div class="container">
    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-4 d-flex flex-column justify-content-center order-1 order-md-0">
            <small class="text-uppercase font-weight-bold text-muted mb-2"><?= l('index.lightweight.name') ?></small>

            <div>
                <h2 class="h4 mb-3"><?= l('index.lightweight.header') ?></h2>

                <p class="text-muted"><?= l('index.lightweight.subheader') ?></p>
            </div>
        </div>

        <div class="col-12 col-md-7 text-center mb-5 mb-md-0 order-0 order-md-1">
            <img src="<?= ASSETS_FULL_URL . 'images/index/lightweight.png' ?>" class="img-fluid shadow" loading="lazy" />
        </div>
    </div>
</div>

<div class="my-10"></div>

<div class="container">
    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-7 text-center mb-5 mb-md-0">
            <img src="<?= ASSETS_FULL_URL . 'images/index/analytics.png' ?>" class="img-fluid shadow" loading="lazy" />
        </div>

        <div class="col-12 col-md-4 d-flex flex-column justify-content-center">
            <small class="text-uppercase font-weight-bold text-muted mb-2"><?= l('index.analytics.name') ?></small>

            <div>
                <h2 class="h4 mb-3"><?= l('index.analytics.header') ?></h2>

                <p class="text-muted"><?= l('index.analytics.subheader') ?></p>
            </div>
        </div>
    </div>
</div>

<div class="my-10"></div>

<div class="container">
    <div class="row justify-content-between" data-aos="fade-up">
        <div class="col-12 col-md-4 d-flex flex-column justify-content-center order-1 order-md-0">
            <small class="text-uppercase font-weight-bold text-muted mb-2"><?= l('index.extras_options_variants.name') ?></small>

            <div>
                <h2 class="h4 mb-3"><?= l('index.extras_options_variants.header') ?></h2>

                <p class="text-muted"><?= l('index.extras_options_variants.subheader') ?></p>
            </div>
        </div>

        <div class="col-12 col-md-7 text-center mb-5 mb-md-0 order-0 order-md-1">
            <img src="<?= ASSETS_FULL_URL . 'images/index/extras_options_variants.png' ?>" class="img-fluid shadow" loading="lazy" />
        </div>
    </div>
</div>

<?php if(settings()->main->display_index_testimonials): ?>
<div class="my-10"></div>

<div class="py-7 bg-primary-100">
    <div class="container">
        <div class="text-center">
            <h2><?= l('index.testimonials.header') ?> <i class="fas fa-fw fa-xs fa-check-circle text-primary"></i></h2>
        </div>

        <div class="row mt-8">
            <?php foreach(['one', 'two', 'three'] as $key => $value): ?>
                    <div class="col-12 col-lg-4 mb-6 mb-lg-0" data-aos="fade-up" data-aos-delay="<?= $key * 100 ?>">
                        <div class="card border-0 zoom-animation">
                            <div class="card-body">
                                <img src="<?= ASSETS_FULL_URL . 'images/index/testimonial-' . $value . '.jpeg' ?>" class="img-fluid index-testimonial-avatar" alt="<?= l('index.testimonials.' . $value . '.name') . ', ' . l('index.testimonials.' . $value . '.attribute') ?>" loading="lazy" />

                                <p class="mt-5">
                                    <span class="text-gray-800 font-weight-bold text-muted h5">“</span>
                                    <span><?= l('index.testimonials.' . $value . '.text') ?></span>
                                    <span class="text-gray-800 font-weight-bold text-muted h5">”</span>
                                </p>

                                <div class="blockquote-footer mt-4">
                                    <span class="font-weight-bold"><?= l('index.testimonials.' . $value . '.name') ?></span>, <span class="text-muted"><?= l('index.testimonials.' . $value . '.attribute') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
        </div>
    </div>
</div>
<?php endif ?>

<?php if(settings()->main->display_index_plans): ?>
<div class="my-10"></div>

<div class="container">
    <div class="text-center mb-5">
        <small class="text-primary font-weight-bold text-uppercase"><?= l('index.pricing.subheader') ?></small>
        <h2 class="mt-2"><?= l('index.pricing.header') ?></h2>
    </div>

    <?= $this->views['plans'] ?>
</div>
<?php endif ?>

<?php if(settings()->main->display_index_faq): ?>
    <div class="my-10"></div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.faq.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="accordion index-faq" id="faq_accordion">
            <?php foreach(['one', 'two', 'three', 'four'] as $key): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn btn-lg font-weight-bold btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span><?= l('index.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-2" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('index.faq.' . $key . '.answer') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        $('#faq_accordion').on('show.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.transform = 'rotate(180deg)';
            svg.style.color = 'var(--primary)';
        })

        $('#faq_accordion').on('hide.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.color = 'var(--primary-800)';
            svg.style.removeProperty('transform');
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="my-10"></div>

    <div class="bg-primary-800 py-6">
        <div class="container">
            <div class="py-3">
                <div class="row align-items-center justify-content-center" data-aos="fade-up">
                    <div class="col-12 col-lg-5">
                        <div class="text-center text-lg-left mb-4 mb-lg-0">
                            <h1 class="h2 text-gray-100"><?= l('index.cta.header') ?></h1>
                            <p class="h5 font-weight-normal text-gray-200"><?= l('index.cta.subheader') ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                        <div class="text-center text-lg-right">
                            <?php if(\Altum\Authentication::check()): ?>
                                <a href="<?= url('dashboard') ?>" class="btn btn-lg btn-outline-light index-button">
                                    <?= l('dashboard.menu') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('register') ?>" class="btn btn-lg btn-outline-light index-button">
                                    <?= l('index.cta.register') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->stores->example_url): ?>
<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/jquery-qrcode.min.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    'use strict';

    let generate_qr = () => {
        let qr_url = <?= json_encode(settings()->stores->example_url) ?>;

        let default_options = {
            render: 'image',
            minVersion: 1,
            maxVersion: 40,
            ecLevel: 'H',
            left: 0,
            top: 0,
            size: 600,
            fill: '#000',
            background: '#fff',
            text: qr_url,
            radius: 0,
            quiet: 0,
            mode: 0,
            mSize: 0.1,
            mPosX: 0.5,
            mPosY: 0.5,
            fontname: 'arial',
        };

        $('.index-qr').qrcode(default_options);
    }

    generate_qr();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php if(count($data->blog_posts)): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.blog.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="row">
            <?php foreach($data->blog_posts as $blog_post): ?>
            <div class="col-12 col-lg-4 p-4">
                <div class="card h-100 zoom-animation-subtle">
                    <div class="card-body">
                        <?php if($blog_post->image): ?>
                                <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" aria-label="<?= $blog_post->title ?>">
                                    <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="blog-post-image-small img-fluid w-100 rounded mb-4" alt="<?= $blog_post->image_description ?>" loading="lazy" />
                                </a>
                            <?php endif ?>

                        <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>">
                            <h3 class="h5 card-title mb-2"><?= $blog_post->title ?></h3>
                        </a>

                        <p class="text-muted mb-0"><?= $blog_post->description ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>


<?php ob_start() ?>
<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/libraries/aos.min.css?v=' . PRODUCT_CODE ?>">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    AOS.init({
        delay: 100,
        duration: 600
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
