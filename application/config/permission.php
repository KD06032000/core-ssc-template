<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// phan quyen
$config['cms_language_role']['groups'] = 'Nhóm quyền';
$config['cms_language_role']['media'] = 'Media';
$config['cms_language_role']['newsletter'] = 'Newsletter';
$config['cms_language_role']['post'] = 'Bài viết';
$config['cms_language_role']['property'] = 'Thuộc tính';
$config['cms_language_role']['setting'] = 'Cài đặt';
$config['cms_language_role']['users'] = 'Tài khoản quản trị';
$config['cms_language_role']['banner'] = 'Banner';
$config['cms_language_role']['company'] = 'Đơn vị thành viên';
$config['cms_language_role']['business'] = 'Lĩnh vực hoạt động';
$config['cms_language_role']['library'] = 'Thư viện';
$config['cms_language_role']['project'] = 'Dự án';
$config['cms_language_role']['video'] = 'Thư viện Video';
$config['cms_language_role']['service'] = 'Dịch vụ';
$config['cms_language_role']['account'] = 'Khách hàng';
$config['cms_language_role']['category'] = 'Danh mục';
$config['cms_language_role']['contact'] = 'Liên hệ';
$config['cms_language_role']['page'] = 'Quản lý trang tĩnh';
$config['cms_language_role']['question'] = 'Câu hỏi';
$config['cms_language_role']['report'] = 'Báo cáo đơn hàng';
$config['cms_language_role']['menus'] = 'Quản lý menu';
$config['cms_language_role']['gallery'] = 'Thư viện ảnh';
$config['cms_language_role']['field'] = 'Lĩnh vực hoạt động';
$config['cms_language_role']['product'] = 'Sản phẩm';
$config['cms_language_role']['document'] = 'Tài liệu';
$config['cms_language_role']['location'] = 'Quản lý địa điểm';
$config['cms_language_role']['category_post'] = 'Danh mục bài viết';
$config['cms_language_role']['category_career'] = 'Danh mục tuyển dụng';
$config['cms_language_role']['category_pro_service'] = 'Danh mục sản phẩm';
$config['cms_language_role']['pro_service'] = 'Sản phẩm / dịch vụ';
$config['cms_language_role']['category_project'] = 'Danh mục dự án';
$config['cms_language_role']['category_document'] = 'Danh mục tài liệu';
$config['cms_language_role']['category_video'] = 'Danh mục video';
$config['cms_language_role']['category_service'] = 'Danh mục dịch vụ';
$config['cms_language_role']['category_question'] = 'Danh mục câu hỏi';
$config['cms_language_role']['category_ecosystem'] = 'Danh mục hệ sinh thái';
$config['cms_language_role']['category_calendar'] = 'Danh mục lịch hoạt động';
$config['cms_language_role']['category_field'] = 'Danh mục lĩnh vực hoạt động';
$config['cms_language_role']['category_redirect'] = 'Tags liên kết';
$config['cms_language_role']['property_location'] = 'Địa điểm tuyển dụng';
$config['cms_language_role']['career'] = 'Quản lý tuyển dụng';
$config['cms_language_role']['candidate'] = 'Quản lý ứng viên';
$config['cms_language_role']['location_city'] = 'Quản lý tỉnh/thành phố';
$config['cms_language_role']['location_district'] = 'Quản lý quận/huyện';
$config['cms_language_role']['location_ward'] = 'Quản lý phường xã';
$config['cms_language_role']['ecosystem'] = 'Hệ sinh thái';
$config['cms_language_role']['calendar'] = 'Lịch hoạt động';
$config['cms_language_role']['tag'] = 'Tag bài viết';
$config['cms_language_role']['redirect'] = 'Link chuyển hướng';

$config['cms_check_not_add'] = array('contact', 'media');
$config['cms_check_not_edit'] = array('contact', 'media');
$config['cms_check_not_delete'] = array('report', 'media');
// Những controller ai cũng có quyền xem
$config['cms_not_per'] = array('dashboard');
$config['cms_not_per_method'] = array();
$config['cms_check_export'] = array('redirect');
$config['cms_check_import'] = array();

//Những controller custom phân quyền
$config['cms_custom_per'] = ['category', 'property'];
$category = ['post', 'calendar', 'ecosystem', 'redirect'];

// những method cần phân quyền theo cms_custom_per
$config['cms_per_list_method'] = array_merge($category);
// list controller cần phân quyền
$config['cms_controller_permission'] = array('groups', 'users', 'redirect', 'post', 'tag', 'menus', 'page', 'media', 'gallery', 'video', 'banner', 'ecosystem', 'calendar', 'category' => $category);
