<div class="page" id="page_search">

    <div class="main-page">
        <div class="container">

            <h1 class="title-pri vS wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                <?= lang('post') ?>
            </h1>
            <p><?= lang('search_results_key') ?> "<?=!empty($key) ? $key : ''?>"</p>

            <div class="list-pri" id="listNews"> </div>

        </div>

        <div class="container">

            <h1 class="title-pri vS wow zoomIn" style="visibility: visible; animation-name: zoomIn;">
                <?= lang('story') ?>
            </h1>
            <p><?= lang('search_results_key') ?> "<?=!empty($key) ? $key : ''?>"</p>

            <div class="list-pri" id="listStory"> </div>

        </div>
    </div>
</div>