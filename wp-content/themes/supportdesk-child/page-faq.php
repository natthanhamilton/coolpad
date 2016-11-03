<?php get_header(); ?>

<!-- #page-header -->
<div class="default-cover" style="margin-bottom: 0">
    <div class="wrapper"
         style="background-image: url('http://res.cloudinary.com/coolpad/image/upload/v1468479706/support/page_cover.jpg')">
        <div class="content">
            <div class="title"><?php the_title(); ?></div>
        </div>
    </div>
</div>
<!-- /#page-header -->

<!-- #primary -->
<div id="primary" class="sidebar-off clearfix">
    <div class="ht-container">
        <!-- #content -->
        <section id="content" role="main">

            <?php while (have_posts()) : the_post(); ?>
                <div class="entry-content clearfix">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; // end of the loop. ?>


            <?php
            $args  = [
                'sort_order'  => 'ASC',
                'sort_column' => 'menu_order',
                'post_type'   => 'st_faq',
                'parent'      => 0,
                'post_status' => 'publish'
            ];
            $pages = get_pages($args);
            // Desktop tabs
            $count = 0;
            ?>
            <div class="tabs-left faq-tabs">
                <ul class="nav nav-tabs">
                    <?php
                    foreach ($pages as $page) {
                        if ($count == 0) {
                            $active = 'active';
                        } else {
                            $active = '';
                        }
                        $title_stripped = str_replace(' ', '-', strtolower($page->post_title));
                        $content        = $page->post_content;
                        $content        = apply_filters('the_content', $content);
                        echo '<li class="' . $active . '"><a href="#' . $title_stripped . '" data-toggle="tab">' . $page->post_title . '</a></li>';
                        $count++;
                    }
                    ?>

                </ul>
            </div>
            <div class="tab-content">
                <?php
                $count = 0;
                foreach ($pages as $page) {
                    $content = $page->post_content;
                    $content = apply_filters('the_content', $content);
                    if ($count == 0) {
                        $active = 'active';
                    } else {
                        $active = '';
                    }
                    $title_stripped = str_replace(' ', '-', strtolower($page->post_title));
                    ?>
                    <div class="tab-pane <?= $active ?>" id='<?= $title_stripped; ?>'>
                        <article <?php post_class('clearfix desktop-titles'); ?>>
                            <h2 class="section-title"><?= $page->post_title; ?></h2>
                        </article>
                        <?php
                        $args  = [
                            'sort_order'  => 'ASC',
                            'sort_column' => 'menu_order',
                            'post_type'   => 'st_faq',
                            'child_of'    => $page->ID,
                            'post_status' => 'publish'
                        ];
                        $pages = get_pages($args);
                        foreach ($pages as $page) {
                            $content = $page->post_content;
                            $content = apply_filters('the_content', $content);
                            ?>
                            <article id="post-<?php $page->ID; ?>"
                                     class="clearfix post-79 st_faq type-st_faq status-publish hentry">
                                <h2 class="entry-title">
                                    <div class="action" style="margin-right: 10px;"><span class="plus">+</span><span
                                            class="minus">-</span></div>
                                    <a name="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></a></h2>
                                <div class="entry-content"><?php echo $content; ?></div>
                            </article>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                    $count++;
                }
                ?>
            </div>
        </section>
        <!-- #content -->
    </div>
</div>
<!-- /#primary -->
<?php get_footer(); ?>
