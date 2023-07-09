<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Smart model.
 */
class APS_Model extends CI_Model
{
    public $table;
    public $table_trans;
    public $table_category;
    public $table_property;
    public $table_tag;
    public $primary_key;
    public $column_order;
    public $column_search;
    public $order_default;
    public $slug;
    public $_args = array();

    public function __construct()
    {
        parent::__construct();
        $this->table = str_replace('_model', '', get_Class($this));
        $this->primary_key = "id";
        $this->column_order = array("$this->table.id", "$this->table.id", "$this->table_trans.title", "$this->table.is_status", "$this->table.updated_time", "$this->table.created_time"); //thiết lập cột sắp xếp
        $this->column_search = array("$this->table_trans.title"); //thiết lập cột search
        $this->order_default = array("$this->table.created_time" => "DESC"); //cột sắp xếp mặc định

        //load cache driver
        $this->load->driver('cache', array('adapter' => 'file'));
    }

    /*Hàm xử lý các tham số truyền từ Datatables Jquery*/
    public function _get_datatables_query()
    {
        if (!empty($this->input->post('columns'))) {
            $i = 0;
            foreach ($this->column_search as $item) // loop column
            {
                if (trim(xss_clean($this->input->post('search')['value']))) // if datatable send POST for search
                {
                    if ($i === 0) // first loop
                    {
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, trim(xss_clean($this->input->post('search')['value'])));
                    } else {
                        $this->db->or_like($item, trim(xss_clean($this->input->post('search')['value'])));
                    }

                    if (count($this->column_search) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }

            if ($this->input->post('order')) {
                $this->db->order_by($this->column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
            } else if (isset($this->order_default)) {
                $order = $this->order_default;
                $this->db->order_by(key($order), $order[key($order)]);
            }
        }
    }

    public function _where_before($args, $select = '')
    {
        if (empty($select)) $select = "*";
        // $lang_code = $this->session->admin_lang; //Mặc định lấy lang của Admin

        extract($args);
        //$this->db->distinct();
        $this->db->select($select);
        $this->db->from($this->table);

        if (!empty($this->table_trans)) {
            $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
            if (empty($lang_code)) $lang_code = $this->session->admin_lang;
            if (!empty($lang_code)) $this->db->where("$this->table_trans.language_code", $lang_code);
        }

        if (!empty($category_id)) {
            if (!empty($this->table_category)) {
                $nameModel = str_replace('_model', '', $this->table);
                $this->db->join($this->table_category, "$this->table.id = $this->table_category.{$nameModel}_id");
                $this->db->where_in("$this->table_category.category_id", $category_id);
            } else {
                $this->db->where_in("$this->table.category_id", $category_id);
            }
        }

        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($is_featured))
            $this->db->where("$this->table.is_featured", $is_featured);

        if (isset($is_status))
            $this->db->where("$this->table.is_status", $is_status);

        if (!empty($in))
            $this->db->where_in("$this->table.id", $in);
        if (!empty($not_in))
            $this->db->where_not_in("$this->table.id", $not_in);
    }

    public function _where_after($args, $typeQuery)
    {
        $page = 1; //Page default
        $limit = 10;
        extract($args);

        if (!empty($search)) {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            if (!empty($this->table_trans)) {
                $this->db->like("$this->table_trans.title", trim(xss_clean(($search))));
            }else{
                $this->db->like("$this->table.title", trim(xss_clean(($search))));
            }
            $this->db->group_end(); //close bracket
        }
        $this->_get_datatables_query();
        if ($typeQuery === null || empty($typeQuery)) {
            //query for datatables jquery
            if (!empty($order) && is_array($order)) {
                foreach ($order as $k => $v)
                    $this->db->order_by($k, $v);
            } else if (isset($this->order_default)) {
                $order = $this->order_default;
                $this->db->order_by(key($order), $order[key($order)]);
            }
            $offset = ($page - 1) * $limit;
            $this->db->limit($limit, $offset);
        }
    }

    public function _where_custom($args)
    {
    }

    //Xử lý tham số truyền vào. Tham số truyền vào phải dạng Array
    private function _where($args, $typeQuery = null, $select = '')
    {
        $this->_where_before($args, $select);
        $this->_where_custom($args);
        $this->_where_after($args, $typeQuery);
    }

    /*
     * Lấy tất cả dữ liệu
     * */
    public function getAll($lang_code = null, $is_status = null)
    {
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        if (!empty($lang_code)) $this->db->where('language_code', $lang_code);
        if (!empty($is_status)) $this->db->where('is_status', $is_status);
        $query = $this->db->get();
        return $query->result();
    }


    /*
     * Đếm tổng số bản ghi
     * */
    public function getTotalAll($table = '')
    {
        if (empty($table)) $table = $this->table;
        $this->db->from($table);
        return $this->db->count_all_results();
    }


    public function getTotal($args = [])
    {
        $this->_where($args, "count");
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getData($args = array(), $returnType = "object", $select = '')
    {
        $this->_where($args, null, $select);
        $query = $this->db->get();

        switch ($returnType) {
            case 'object':
                return $query->result();
                break;
            case 'row':
                return $query->row();
                break;
            default:
                return $query->result_array();
                break;
        }
    }

    /*
     * Lấy dữ liệu một hàng ra
     * Truyền vào id
     * */
    public function getById($id, $table = '', $select = '*', $lang_code = null)
    {
        $table = !empty($table) ? $table : $this->table;
        $this->db->select($select);
        $this->db->from("$table as A");
        if (!empty($this->table_trans)) $this->db->join("$this->table_trans as B", "A.id = B.id");
        $this->db->where("A.id", $id);
        if (empty($this->table_trans)) {
            $query = $this->db->get();
            return $query->row();
        }

        if (!empty($lang_code)) {
            $this->db->where("B.language_code", $lang_code);
            $query = $this->db->get();
            return $query->row();
        } else {
            $query = $this->db->get();
            return $query->result();
        }
    }

    /*
     * Lấy dữ liệu một hàng ra
     * Truyền vào slug
     * */
    public function getBySlug($slug, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where("$this->table_trans.slug", $slug);
        $this->db->where("$this->table_trans.language_code", $lang_code);
        $query = $this->db->get();
        return $query->row();

    }


    public function getPrevById($id, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where("$this->table.id <", $id);
        $this->db->where("$this->table.is_status", 1);
        $this->db->order_by("$this->table.id", 'DESC');
        if (empty($this->table_trans)) {
            $query = $this->db->get();
            return $query->row();
        }

        if (!empty($lang_code)) {
            $this->db->where("$this->table_trans.language_code", $lang_code);
            $query = $this->db->get();
            return $query->row();
        } else {
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function getNextById($id, $select = '*', $lang_code = null)
    {

        $this->db->select($select);
        $this->db->from($this->table);
        if (!empty($this->table_trans)) $this->db->join($this->table_trans, "$this->table.id = $this->table_trans.id");
        $this->db->where("$this->table.id >", $id);
        $this->db->where("$this->table.is_status", 1);
        $this->db->order_by("$this->table.id", 'ASC');
        if (empty($this->table_trans)) {
            $query = $this->db->get();
            return $query->row();
        }

        if (!empty($lang_code)) {
            $this->db->where("$this->table_trans.language_code", $lang_code);
            $query = $this->db->get();
            return $query->row();
        } else {
            $query = $this->db->get();
            return $query->result();
        }
    }


    public function checkExistByField($field, $value, $tablename = '')
    {
        $this->db->select('1');
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->from($tablename);
        $this->db->where($field, $value);
        return $this->db->count_all_results() > 0 ? true : false;
    }

    public function getSelect2($ids, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->select("$tablename.id, title AS text");
        $this->db->from($tablename);
        if (!empty($this->table_trans)) {
            $this->db->join($this->table_trans, "$tablename.id = $this->table_trans.id");
            $this->db->where("$this->table_trans.language_code", $this->session->admin_lang);
        }
        if (is_array($ids)) $this->db->where_in("$tablename.id", $ids);
        else $this->db->where("$tablename.id", $ids);

        $query = $this->db->get();
        return $query->result();
    }


    public function save($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $data_store = array();
        if (!empty($data)) foreach ($data as $k => $item) {
            if (!is_array($item)) $data_store[$k] = $item;
        }

        if (!$this->db->insert($tablename, $data_store)) {
            log_message('info', json_encode($data_store));
            log_message('error', json_encode($this->db->error()));
            return false;
        } else {
            $id = $this->db->insert_id();

            /*Xử lý bảng category nếu có*/
            if (!empty($this->table_category) && !empty($data['category_id']) && is_array($data['category_id'])) {
                $dataCategory = $data['category_id'];
                if (!empty($dataCategory)) foreach ($dataCategory as $item) {
                    $tmpCategory[$this->table . "_id"] = $id;
                    $tmpCategory["category_id"] = $item;
                    if (!$this->insert($tmpCategory, $this->table_category)) return false;
                    unset($tmpCategory);
                }
            }
            if (isset($data['category_id'])) unset($data['category_id']);

            /*Xử lý bảng tag nếu có*/
            if (!empty($this->table_tag) && !empty($data['tag_id']) && is_array($data['tag_id'])) {
                $dataTag = $data['tag_id'];
                if (!empty($dataTag)) foreach ($dataTag as $item) {
                    $tmpTag[$this->table . "_id"] = $id;
                    $tmpTag["tag_id"] = $item;
                    if (!$this->insert($tmpTag, $this->table_tag)) return false;
                    unset($tmpTag);
                }
            }if (isset($data['tag_id'])) unset($data['tag_id']);


            /*Xử lý bảng property nếu có*/
            if (!empty($this->table_property) && !empty($data['property']) && is_array($data['property'])) {
                $dataProperty = $data['property'];
                $tmpProperty = array();
                if (!empty($dataProperty)) foreach ($dataProperty as $type => $item) {
                    if (is_array($item)) foreach ($item as $v) {
                        $tmp[$this->table . "_id"] = $id;
                        $tmp["type"] = $type;
                        $tmp["property_id"] = $v;
                        $tmpProperty[] = $tmp;
                    } else {
                        $tmp[$this->table . "_id"] = $id;
                        $tmp["type"] = $type;
                        $tmp["property_id"] = $item;
                        $tmpProperty[] = $tmp;
                    }
                }
                if (!$this->insertMultiple($tmpProperty, $this->table_property)) return false;
                unset($tmpProperty);
            }
            if (isset($data['property'])) unset($data['property']);

            $this->_save_custom($data, $id);

            /*Xử lý bảng translate nếu có*/
            if (!empty($this->table_trans)) {

                //thêm vào bảng translation
                foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                    if (!empty($data['title'][$lang_code]) || !empty($data['name'][$lang_code])) {
                        $data_trans = array();
                        $data_trans['id'] = $id;
                        $data_trans['language_code'] = $lang_code;
                        foreach ($data as $k => $item) {
                            if ($k == 'slug' && !empty($data[$k][$lang_code])) { // kiểm tra slug đó đã tồn tại chưa. nếu tồn tại thì đổi slug
                                $this->checkSlug($id, $data[$k][$lang_code]);
                                $data_trans[$k] = $this->slug;
                            } else {
                                $lang_code_value = $lang_code;
                                if (isset($item[$lang_code_value]) && is_array($item)) {
                                    if ($k === 'title' || $k === 'meta_title') $data_trans[$k] = !empty($item[$lang_code_value]) ? stripcslashes($item[$lang_code_value]) : '';
                                    else if (is_array($item[$lang_code_value])) $data_trans[$k] = !empty($item[$lang_code_value]) ? json_encode($item[$lang_code_value]) : '';
                                    $data_trans[$k] = !empty($item[$lang_code_value]) ? $item[$lang_code_value] : '';
                                }
                                if (!empty($item[$lang_code_value]['title']) && empty($item[$lang_code_value]['slug'])) $data_trans['slug'] = toSlug(toNormal($item[$lang_code_value]['title']));
                                if (isset($item[$lang_code_value]) && is_array($item[$lang_code_value])) $data_trans[$k] = !empty($item[$lang_code_value]) ? json_encode($item[$lang_code_value]) : '';
                            }
                        }

                        if (!$this->db->insert($this->table_trans, $data_trans)) {
                            log_message('info', json_encode($data_trans));
                            log_message('error', json_encode($this->db->error()));
                            return false;
                        }
                    }
                }
            }

            $this->cache->clean();
            return $id;
        }
    }

    public function _save_custom($data, $id){}


    public function search($conditions = null, $limit = 500, $offset = 0, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        $query = $this->db->get($tablename, $limit, $offset);

        return $query->result();
    }

    public function single($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);

        return $this->db->get($tablename)->row();
    }

    public function insert($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->insert($tablename, $data);
        $this->cache->clean();
        return $this->db->affected_rows();
    }


    public function insertMultiple($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->insert_batch($tablename, $data);
        $this->cache->clean();
        return $this->db->affected_rows();
    }

    public function insertOnUpdate($data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $data_update = [];
        if (!empty($data)) foreach ($data as $k => $val) {
            $data_update[] = $k . " = '" . $val . "'";
        }

        $queryInsertOnUpdate = $this->db->insert_string($tablename, $data) . " ON DUPLICATE KEY UPDATE " . implode(', ', $data_update);
        if (!$this->db->query($queryInsertOnUpdate)) {
            log_message('info', json_encode($data));
            log_message('error', json_encode($this->db->error()));
            return false;
        }
        $this->cache->clean();
        return $this->db->affected_rows();
    }

    public function update($conditions, $data, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $dataInfo = [];
        if (!empty($data)) foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $dataInfo[$key] = $value;
                unset($data[$key]);
            }
        }

        if (!$this->db->update($tablename, $dataInfo, $conditions)) {
            log_message('info', json_encode($conditions));
            log_message('info', json_encode($data));
            log_message('error', json_encode($this->db->error()));
            return false;
        }

        /*Xử lý bảng category nếu có*/
        if (!empty($this->table_category) && isset($data['category_id'])) {
            $dataCategory = $data['category_id'];
            $tmpCategory[$this->table . "_id"] = $conditions['id'];
            $this->delete($tmpCategory, $this->table_category);
            if (!empty($dataCategory)) foreach ($dataCategory as $item) {
                $tmpCategory["category_id"] = $item;
                if (!$this->insert($tmpCategory, $this->table_category)) {
                    log_message('error', json_encode($this->db->error()));
                    return false;
                }
            }
        }
        if (isset($data['category_id'])) unset($data['category_id']);

        /*Xử lý bảng tag nếu có*/
        if (!empty($this->table_tag) && isset($data['tag_id'])) {
            $dataTag = $data['tag_id'];
            $tmpTag[$this->table . "_id"] = $conditions['id'];
            $this->delete($tmpTag, $this->table_tag);
            if (!empty($dataTag)) foreach ($dataTag as $item) {
                $tmpTag["tag_id"] = $item;
                if (!$this->insert($tmpTag, $this->table_tag)) {
                    log_message('error', json_encode($this->db->error()));
                    return false;
                }
            }
        }
        if (isset($data['tag_id'])) unset($data['tag_id']);

        /*Xử lý bảng property nếu có*/
        if (!empty($this->table_property) && isset($data['property'])) {
            $dataProperty = $data['property'];
            $this->delete([$this->table . "_id" => $conditions['id']], $this->table_property);
            $tmpProperty = array();
            if (!empty($dataProperty)) foreach ($dataProperty as $type => $item) {
                if (!empty($item)) {
                    if (is_array($item)) foreach ($item as $v) {
                        $tmp[$this->table . "_id"] = $conditions['id'];
                        $tmp["type"] = $type;
                        $tmp["property_id"] = $v;
                        $tmpProperty[] = $tmp;
                    } else {
                        $tmp[$this->table . "_id"] = $conditions['id'];
                        $tmp["type"] = $type;
                        $tmp["property_id"] = $item;
                        $tmpProperty[] = $tmp;
                    }
                }
            }
            if (!empty($tmpProperty)) $this->insertMultiple($tmpProperty, $this->table_property);
            unset($tmpProperty);
        }
        if (isset($data['property'])) unset($data['property']);

        $this->_update_custom($conditions, $data);

        /*Xử lý bảng translate nếu có*/
        if (!empty($this->table_trans)) {
            if ($this->config->item('cms_language')) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) {
                if (!empty($data['title'][$lang_code]) || !empty($data['name'][$lang_code])) {
                    $data_trans = array();
                    $data_update = array();
                    foreach ($data as $k => $item) {
                        if ($k == 'slug') { // kiểm tra slug đó đã tồn tại chưa. nếu tồn tại thì đổi slug
                            if (!empty($item[$lang_code])) {
                                $this->checkSlug($conditions['id'], $item[$lang_code]);
                            } else {
                                $this->checkSlug($conditions['id'], toSlug(toNormal($data['title'][$lang_code])));
                            }
                            $data_trans[$k] = $this->slug;
                        } else {
                            if (is_array($item)) {
                                if ($k === 'title' || $k === 'meta_title') {
                                    if (isset($item[$lang_code])) {
                                        $data_trans[$k] = !empty($item[$lang_code]) ? stripcslashes($item[$lang_code]) : '';
                                        $data_update[] = $k . " = '" . $data_trans[$k] . "'";
                                    }
                                } else if (isset($item[$lang_code]) && is_array($item[$lang_code])) {
                                    $data_trans[$k] = !empty($item[$lang_code]) ? json_encode($item[$lang_code]) : '';
                                    $data_update[] = $k . " = '" . json_encode($item[$lang_code]) . "'";
                                } else {
                                    if (isset($item[$lang_code])) {
                                        $data_trans[$k] = !empty($item[$lang_code]) ? $item[$lang_code] : '';
                                        $data_update[] = $k . " = '" . $item[$lang_code] . "'";
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($data_trans)) {

                        $where_trans = array('id' => $conditions['id'], 'language_code' => $lang_code);
                        if (!empty($this->handleTrans($conditions['id'], $lang_code))) {
                            if (!$this->db->update($this->table_trans, $data_trans, $where_trans)) {
                                log_message('info', json_encode($data_trans));
                                log_message('error', json_encode($this->db->error()));
                                return false;
                            }
                        } else {
                            if (!$this->db->insert($this->table_trans, array_merge($where_trans, $data_trans))) {
                                log_message('info', json_encode(array_merge($where_trans, $data_trans)));
                                log_message('error', json_encode($this->db->error()));
                                return false;
                            }
                        }

                        /*$where_trans = array('id'=>$conditions['id'],'language_code'=>$lang_code);
                        $data_trans = array_merge($where_trans,$data_trans);
                        $queryInsertOnUpdate = $this->db->insert_string($this->table_trans, $data_trans)." ON DUPLICATE KEY UPDATE id = {$conditions['id']}, language_code = '{$lang_code}', ".implode(", ", $data_update);
                        echo $queryInsertOnUpdate;
                        if(!$this->db->query($queryInsertOnUpdate)){
                            log_message('info',json_encode($where_trans));
                            log_message('info',json_encode($data_trans));
                            log_message('error',json_encode($this->db->error()));
                            return false;
                        }*/
                    }
                }
            }
        }

        $this->cache->clean();
        return true;
    }

    public function _update_custom($conditions, $data){}

    private function handleTrans($id, $lang = 'vi')
    {
        $this->db->select('id');
        $this->db->from($this->table_trans);
        $this->db->where('id', $id);
        $this->db->where('language_code', $lang);
        return $this->db->get()->row();
    }

    public function delete($conditions, $tablename = '')
    {
        if ($tablename == '') {
            $tablename = $this->table;
        }
        $this->db->where($conditions);
        if (!$this->db->delete($tablename)) {
            log_message('info', json_encode($conditions));
            log_message('info', json_encode($tablename));
            log_message('error', json_encode($this->db->error()));
        }
        $this->cache->clean();
        return $this->db->affected_rows();
    }

    public function count($conditions = null, $tablename = '')
    {
        if ($conditions != null) {
            $this->db->where($conditions);
        }

        if ($tablename == '') {
            $tablename = $this->table;
        }

        $this->db->select('1');
        return $this->db->get($tablename)->num_rows();
    }

    public function getpostAllLang($id)
    {
        $this->db->distinct();
        $this->db->select(['slug', 'language_code']);
        $this->db->from($this->table_trans);
        $this->db->where('id', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    /*
     * Kiểm tra xem có tồn tại slug đó hay chưa
     * @params
     * $id: id của post
     * $slug: slug của post
     * $lang: lang_code
     * $table: table cần check
     * */
    private function checkSlug($id = '', $slug, $table = '')
    {
        if (!empty($slug)) {
            if (empty($table)) $table = $this->table_trans;
            $this->db->select('slug');
            $this->db->from($table);
            if (!empty($id)) $this->db->where('id!=', $id);
            $this->db->where('slug', $slug);
            $data = $this->db->get()->row();
            if (!empty($data)) {
                $slug = $slug . '-copy';
                $this->checkSlug($id, $slug, $table);
            }
            $this->slug = $slug;
        }

        return true;
    }

    public function getLastOrder()
    {
        $this->db->select('order');
        $this->db->from($this->table);
        $this->db->order_by('order', 'DESC');
        $data = $this->db->get()->row();
        return $data;
    }
}
