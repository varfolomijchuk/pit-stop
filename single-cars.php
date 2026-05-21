<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-car'); ?>>

        <h1 class="single-car__title"><?php the_title(); ?></h1>

        <?php if (has_post_thumbnail()) : ?>
            <div class="single-car__image">
                <?php the_post_thumbnail('full'); ?>
            </div>
        <?php endif; ?>

        <?php
        $records = get_post_meta(get_the_ID(), '_service_records', true);
        $records = is_array($records) ? $records : [];
        ?>

        <?php if ($records) : ?>
            <div class="single-cars__records">
                <h2><?php esc_html_e('Service Records', 'pitstop'); ?></h2>

                <div class="records-filter">
                    <label>
                        <?php esc_html_e('From', 'pitstop'); ?>
                        <input type="date" id="filter-date-from">
                    </label>
                    <label>
                        <?php esc_html_e('To', 'pitstop'); ?>
                        <input type="date" id="filter-date-to">
                    </label>
                    <button type="button" id="filter-records-reset" class="button">
                        <?php esc_html_e('Reset', 'pitstop'); ?>
                    </button>
                </div>

                <div id="records-list">
                    <?php foreach ($records as $record) : ?>
                        <div class="service-record">
                            <?php if (!empty($record['date'])) : ?>
                                <p class="service-record__date">
                                    <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($record['date']))); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($record['wysiwyg'])) : ?>
                                <div class="service-record__notes">
                                    <?php echo wp_kses_post($record['wysiwyg']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($record['number'] !== '') : ?>
                                <p class="service-record__number">
                                    <?php echo esc_html($record['number']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </article>

<?php endwhile; ?>

<script>
(function () {
    var dateFrom = document.getElementById('filter-date-from');
    var dateTo   = document.getElementById('filter-date-to');
    var reset    = document.getElementById('filter-records-reset');
    var list     = document.getElementById('records-list');

    if (!dateFrom) return;

    function fetchRecords() {
        list.style.opacity = '0.5';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?= esc_js(admin_url('admin-ajax.php')) ?>');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                list.innerHTML    = xhr.responseText;
                list.style.opacity = '1';
            }
        };
        xhr.send(
            'action=filter_service_records' +
            '&post_id=<?= get_the_ID() ?>' +
            '&date_from=' + encodeURIComponent(dateFrom.value) +
            '&date_to='   + encodeURIComponent(dateTo.value) +
            '&nonce=<?= esc_js(wp_create_nonce('filter_service_records_nonce')) ?>'
        );
    }

    dateFrom.addEventListener('change', fetchRecords);
    dateTo.addEventListener('change', fetchRecords);

    reset.addEventListener('click', function () {
        dateFrom.value = '';
        dateTo.value   = '';
        fetchRecords();
    });
}());
</script>

<?php get_footer(); ?>
