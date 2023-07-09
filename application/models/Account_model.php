<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends APS_Model
{

  protected $table_device_logged;
  protected $table_gift;

  public function __construct()
  {
    parent::__construct();
    $this->table = 'account';
    $this->account_group = 'account_groups';
    $this->table_device_logged = 'logged_device';//bảng logged device
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.email", "$this->table.company_name", "$this->table.full_name", "$this->table.last_name", "$this->table.phone", "$this->table.birthday", "$this->table.address", "$this->table.country", "$this->table.city", "$this->table.postCode", "$this->table.active"); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table.full_name", "$this->table.email", "$this->table.id","$this->table.phone"); //thiết lập cột search
    $this->order_default = array('id' => 'desc'); //cột sắp xếp mặc định

  }


  public function __where($args, $typeQuery = null)
  {
    $select = "*";
    //$lang_code = $this->session->admin_lang; //Mặc định lấy lang của Admin
    $page = 1; //Page default
    $limit = 10;

    extract($args);
    //$this->db->distinct();
    $this->db->select($select);
    $this->db->from($this->table);
    if (!empty($group_by))
      $this->db->group_by("$this->table.$group_by");

    if (!empty($other_active)) $this->db->where("$this->table.active !=", $other_active);
    if (isset($active)) $this->db->where("$this->table.active", $active);
    if (!empty($group_id)) {
      $this->db->join("account_groups", "account.id = account_groups.user_id");
      $this->db->where("account_groups.group_id", $group_id);
    }
    if (!empty($order_by)) $this->db->order_by('created_time', $order_by);

    /* if (!empty($search)) {
       $this->db->group_start();
       $this->db->like("$this->table.full_name", $search);
       $this->db->or_like("$this->table.company_name", $search);
       $this->db->or_like("$this->table.email", $search);
       $this->db->or_like("$this->table.phone", $search);
       $this->db->group_end(); //close bracket
     }*/
    //query for datatables jquery
    $this->_get_datatables_query();

    $this->db->order_by('created_time', 'DESC');
    if (!empty($search_user)) {
      $this->db->group_start();
      $this->db->like("$this->table.username", $search_user);
      $this->db->group_end(); //close bracket
    }
    if ($typeQuery === null) {
      $offset = ($page - 1) * $limit;
      $this->db->limit($limit, $offset);
    }
  }

  public function getTotalPro($args = [])
  {
    $this->__where($args, "count");
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function get_by_id_account($id)
  {
    $this->db->select(array("$this->table.*", "$this->account_group.user_id", "$this->account_group.group_id"));
    $this->db->from($this->table);
    $this->db->join($this->account_group, "$this->table.id=$this->account_group.user_id");
    $this->db->where("$this->table.id", $id);
    return $this->db->get()->row();
  }

  public function getDataPro($args = array(), $returnType = "object")
  {
    $this->__where($args);
    $query = $this->db->get();
    if ($returnType !== "object") return $query->result_array(); //Check kiểu data trả về
    else return $query->result();
  }

  public function getUserByField($key, $value, $status = '')
  {
    $this->db->select('*');
    $this->db->where($this->table . '.' . $key, $value);
    if ($status != '') $this->db->where($this->table . '.active', $status);
    return $this->db->get($this->table)->row();
  }

  public function getAccountByField($key, $value, $select = '*')
  {
    $this->db->select($select);
    $this->db->where($this->table . '.' . $key, $value);
    return $this->db->get($this->table)->row();
  }


  public function checkProduct($product_id, $account_id)
  {
    $this->db->where('product_id', $product_id);
    $this->db->where('account_id', $account_id);
    return $this->db->get($this->account_wishlist)->row();
  }

  public function check_oauth($field, $oauth)
  {
    $tablename = $this->table;
    $this->db->select('*');
    $this->db->where($field, $oauth);

    return $this->db->get($tablename)->row();
  }


  public function updateField($account_id, $key, $value)
  {
    $this->db->where($this->table . '.id', $account_id);
    $this->db->update($this->table, array($this->table . '.' . $key => $value));
    return true;
  }

  public function get_group_by_account_id($id)
  {
    $this->db->from($this->account_group);
    $this->db->where('user_id', $id);
    $query = $this->db->get();
    return $query->row();
  }


  public function unlinkOld()
  {
    return $this->db->select('thumbnail')->from($this->table)->where('id', $this->session->account['account_id'])->get()->row();
  }


  public function getAccount($id, $select = 'id,email,full_name,username')
  {
    $this->db->select($select);
    $this->db->from($this->table);
    $this->db->where_in('id', $id);
    $data = $this->db->get()->row();
    return $data;
  }

  public function getTotalAccount()
  {
    // $this->db->select('1');
    $this->db->from($this->table);
    // $this->db->join($this->table_detail, "$this->table.id = $this->table_detail.order_id");
    $this->db->where('active', 1);
    $data = $this->db->count_all_results();
    return $data;
  }


  public function check_email($id, $email)
  {
    $this->db->select('id');
    $this->db->from($this->table);
    $this->db->where("id!=", $id);
    $this->db->where("email", $email);
    $data = $this->db->get()->row();
    return $data;
  }

  public function check_phone($id, $phone)
  {
    $this->db->select('id');
    $this->db->from($this->table);
    $this->db->where("id!=", $id);
    $this->db->where("phone", $phone);
    $data = $this->db->get()->row();
    return $data;
  }
}
