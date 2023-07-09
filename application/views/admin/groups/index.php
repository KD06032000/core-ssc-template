<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7"></div>
                        <?php $this->load->view($this->template_path . "_block/button") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form action="" id="form-table" method="post">
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th><?php echo lang('text_id'); ?></th>
                                <th><?php echo lang('text_title'); ?></th>
                                <th><?php echo lang('text_description'); ?></th>
                                <?php showColumnAction(); ?>
                            </tr>
                            </thead>
                        </table>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => 'form-horizontal']) ?>
                <input type="hidden" name="id" value="0">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">

                        <li class="active"><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a>
                        </li>
                        <li><a href="#tab_per" data-toggle="tab"><?php echo lang('tab_per'); ?></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_info">

                            <?php
                            $list = [
                                ['name' => 'name', 'title' => 'Tên nhóm', 'type' => 'text', 'required' => true],
                                ['name' => 'description', 'title' => 'Mô tả', 'type' => 'text', 'required' => false],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>

                        </div>
                        <div class="tab-pane" id="tab_per">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <table class="table table-hover" id="tbl_per">
                                            <thead>
                                            <tr>
                                                <th><?php echo lang('text_category'); ?></th>
                                                <th class="text-center"><?php echo lang('text_view'); ?></th>
                                                <th class="text-center"><?php echo lang('text_add'); ?></th>
                                                <th class="text-center"><?php echo lang('text_edit'); ?></th>
                                                <th class="text-center"><?php echo lang('text_delete'); ?></th>
                                                <th class="text-center"><?php echo lang('text_import'); ?></th>
                                                <th class="text-center"><?php echo lang('text_export'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $controllers = $this->config->item('cms_controller_permission');
                                            if (!empty($controllers)):
                                                foreach ($controllers as $key => $controller):
                                                    if (!is_array($controller)) {
                                                        ?>
                                                        <tr id="per_<?php echo $controller; ?>">

                                                            <td><?php echo !empty($this->config->item('cms_language_role')[$controller]) ? $this->config->item('cms_language_role')[$controller] : ucfirst($controller); ?></td>

                                                            <td class="text-center">
                                                                <input type="checkbox"
                                                                       id="per_<?php echo $controller; ?>_view"
                                                                       name="permission[<?php echo $controller; ?>][view]"
                                                                       value="1"/>
                                                            </td>
                                                            <?php if (!in_array($controller, $this->config->item('cms_check_not_add'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $controller; ?>_add"
                                                                           name="permission[<?php echo $controller; ?>][add]"
                                                                           value="1"/>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if (!in_array($controller, $this->config->item('cms_check_not_edit'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $controller; ?>_edit"
                                                                           name="permission[<?php echo $controller; ?>][edit]"
                                                                           value="1"/>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if (!in_array($controller, $this->config->item('cms_check_not_delete'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $controller; ?>_delete"
                                                                           name="permission[<?php echo $controller; ?>][delete]"
                                                                           value="1"/>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if (in_array($controller, $this->config->item('cms_check_import'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $controller; ?>_import"
                                                                           name="permission[<?php echo $controller; ?>][import]"
                                                                           value="1"/>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>

                                                            <?php if (in_array($controller, $this->config->item('cms_check_export'))) : ?>
                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $controller; ?>_export"
                                                                           name="permission[<?php echo $controller; ?>][export]"
                                                                           value="1"/>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                            <?php endif; ?>


                                                        </tr>
                                                        <?php
                                                    } else {
                                                        foreach ($controller as $item) {
                                                            $name = $key . '_' . $item;
                                                            ?>
                                                            <tr id="per_<?php echo $name; ?>">

                                                                <td><?php echo !empty($this->config->item('cms_language_role')[$name]) ? $this->config->item('cms_language_role')[$name] : ucfirst($key . ' ' . $item); ?></td>

                                                                <td class="text-center">
                                                                    <input type="checkbox"
                                                                           id="per_<?php echo $name; ?>_view"
                                                                           name="permission[<?php echo $key; ?>][<?php echo $item; ?>][view]"
                                                                           value="1"/>
                                                                </td>
                                                                <?php if (!in_array($name, $this->config->item('cms_check_not_add'))) : ?>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                               id="per_<?php echo $name; ?>_add"
                                                                               name="permission[<?php echo $key; ?>][<?php echo $item; ?>][add]"
                                                                               value="1"/>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                <?php endif; ?>

                                                                <?php if (!in_array($name, $this->config->item('cms_check_not_edit'))) : ?>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                               id="per_<?php echo $name; ?>_edit"
                                                                               name="permission[<?php echo $key; ?>][<?php echo $item; ?>][edit]"
                                                                               value="1"/>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                <?php endif; ?>

                                                                <?php if (!in_array($name, $this->config->item('cms_check_not_delete'))) : ?>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                               id="per_<?php echo $name; ?>_delete"
                                                                               name="permission[<?php echo $key; ?>][<?php echo $item; ?>][delete]"
                                                                               value="1"/>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                                <?php if (in_array($name, $this->config->item('cms_check_import'))) : ?>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                               id="per_<?php echo $name; ?>_import"
                                                                               name="permission[<?php echo $key; ?>][<?php echo $item; ?>][import]"
                                                                               value="1"/>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                <?php endif; ?>

                                                                <?php if (in_array($name, $this->config->item('cms_check_export'))) : ?>
                                                                    <td class="text-center">
                                                                        <input type="checkbox"
                                                                               id="per_<?php echo $name; ?>_export"
                                                                               name="permission[<?php echo $key; ?>][<?php echo $item; ?>][export]"
                                                                               value="1"/>
                                                                    </td>
                                                                <?php else: ?>
                                                                    <td></td>
                                                                <?php endif; ?>
                                                            </tr>
                                                            <?php
                                                        }
                                                    }
                                                    ?>

                                                <?php
                                                endforeach;
                                            endif;
                                            ?>
                                            </tbody>
                                        </table>
                                        <a href="javascript:;" onclick="check_all_per();">Chọn tất cả</a> /
                                        <a href="javascript:;" onclick="uncheck_all_per();">Bỏ chọn</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer v2" style="border-top: 0">
                <?php $this->load->view($this->template_path . '_block/form_button') ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_get_auth = '<?php echo site_url("admin/{$this->router->fetch_class()}/ajax_get_auth")?>';
    var cms_custom_per = <?php echo json_encode($this->config->item('cms_custom_per')) ?>;
</script>
