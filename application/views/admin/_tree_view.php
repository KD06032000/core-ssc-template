<?php
$cmsControllerPer = $this->config->item('cms_controller_permission'); // list controller cần phân quyền
$cmsCusPer = $this->config->item('cms_custom_per'); // Những controller custom phân quyền
$cmsPerMethod = $this->config->item('cms_per_list_method'); // những method cần phân quyền theo cms_custom_per
$cmsNotPer = $this->config->item('cms_not_per'); // những controller ai cũng xem được

foreach ($children as $value):
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