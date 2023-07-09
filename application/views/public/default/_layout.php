<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="<?= $this->lang_code ?>">

<head>
    <base href="<?= BASE_URL ?>">
    <?php $this->load->view($this->template_path . '_meta') ?>
    <link rel="stylesheet" href="<?= $this->templates_assets . 'css/app.css' ?>">
    <link rel="stylesheet" href="<?= $this->templates_assets . 'css/style.css' ?>">
    <link rel="stylesheet" href="<?= $this->templates_assets . 'css/main.css' ?>">
    <link href="<?= $this->templates_assets . 'css/slick.css' ?>" type="text/css" rel="stylesheet">
    <link href="<?= $this->templates_assets .'css/animate.css' ?>" type="text/css" rel="stylesheet">
    <link href="<?= $this->templates_assets .'css/fancybox.css' ?>" type="text/css" rel="stylesheet">
    <link href="<?= $this->templates_assets .'css/main.css' ?>" type="text/css" rel="stylesheet">
    <link href="<?= $this->templates_assets .'css/bootstrap.min.css' ?>" type="text/css" rel="stylesheet">
    <link href="<?= $this->templates_assets .'fonts/fontawesome-pro-5.8.2-web/css/all.min.css' ?>" rel="stylesheet">
    <link href="<?= $this->templates_assets .'fonts/line-awesome/css/line-awesome.min.css' ?>" rel="stylesheet">
    <link href="<?= $this->templates_assets .'fonts/elegantIcon/elegantIcon.css' ?>" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->templates_assets . 'js/head.js' ?>"></script>
    <script>
        var current_url = '<?= current_url() ?>',
            base_url = '<?= base_url(); ?>',
            media_url = '<?= MEDIA_URL . '/'; ?>',
            controller = '<?= $this->_controller ?>',
            album = [],
            <?php
            switch ($this->lang_code) {
                case 'en':
                    $lang_fb = 'en_US';
                    break;
                default:
                    $lang_fb = 'vi_VN';
            }
            ?>
            lang = "<?= $lang_fb ?>";
    </script>
    <?= !empty($this->settings['script_head']) ? $this->settings['script_head'] : '' ?>
</head>

<body>

<?php
$this->load->view($this->template_path . '_nav');
?>
<div id="page">
    <?php
    $this->load->view($this->template_path . '_header');
    echo !empty($main_content) ? $main_content : '';
    $this->load->view($this->template_path . '_footer');
    ?>
    <a href="#" id="back-to-top" class="back-top"><i class="fas fa-chevron-up"></i> </a>
</div>
<div id="fb-root"></div>
<script src="<?= $this->templates_assets . 'js/app.js' ?>"></script>
<script src="<?= $this->templates_assets . 'js/utils.js' ?>"></script>
<script src="<?= $this->templates_assets . 'js/custom.js' ?>"></script>
<script src="<?= $this->templates_assets. 'js/jquery.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets . 'js/bootstrap.min.js' ?>'" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/slick.min.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/tilt.jquery.min.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/wow.min.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/scrollspy.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/fancybox.js' ?>" type="text/javascript"></script>
<script src="<?= $this->templates_assets .'js/script.js' ?>" type="text/javascript"></script>

<?= !empty($this->settings['embeb_js']) ? $this->settings['embeb_js'] : '' ?>

</body>

</html>