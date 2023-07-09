<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <?php
            $this->session->admin_permission == null ? $permission[] = null : $permission = array_keys($this->session->admin_permission);
            $cmsControllerPer = $this->config->item('cms_controller_permission'); // list controller cần phân quyền
            $cmsCusPer = $this->config->item('cms_custom_per'); // Những controller custom phân quyền
            $cmsPerMethod = $this->config->item('cms_per_list_method'); // những method cần phân quyền theo cms_custom_per
            $cmsNotPer = $this->config->item('cms_not_per'); // những controller ai cũng xem được
            $list_menu = $this->load->get_var('list_menu');
            foreach ($list_menu as $key => $value):
                $iconTree = $childs = '';
                if (!empty($value['children'])) {
                    $childs = $this->load->view($this->template_path . '_tree_view', $value, TRUE);
                }
                if (!empty($childs) || in_array($value['controller'], $cmsNotPer) || perUserLogin($value['href'], $cmsCusPer, $cmsPerMethod)) {
                    if (!empty($childs)) {
                        $iconTree = '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
                        $childs = '<ul class="treeview-menu">' . $childs . '</ul>';
                    }
                    ?>
                    <li class="<?= $value['class'] ?>">
                        <a href="<?= empty($value['class']) ? BASE_ADMIN_URL . $value['href'] : '#' ?>">
                            <i class="<?= $value['icon'] ?>"></i>
                            <span><?= $value['text'] ?></span>
                            <?= $iconTree ?>
                        </a>
                        <?= $childs ?>
                    </li>
                    <?php
                }
            endforeach;
            ?>
            <?php if ($this->session->userdata['user_id'] == 1): ?>
                <li class="header">Cài đặt</li>
                <li>
                    <a href="<?php echo site_url('admin/setting'); ?>">
                        <i class="fa fa-cogs"></i> <span>Cấu hình chung</span>
                    </a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo site_url('admin/template_mail'); ?><!--">-->
<!--                        <i class="fa fa-envelope-o" aria-hidden="true"></i> <span>Mail mẫu</span>-->
<!--                    </a>-->
<!--                </li>-->

                <li>
                    <a href="<?php echo site_url('admin/logaction'); ?>">
                        <i class="fa fa-exclamation-triangle"></i> <span>Logs</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </section>
</aside>
