<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends APS_Model
{
  public $_table_city;
  public $_table_district;
  public $_table_ward;
  public $_table_country;

  public function __construct()
  {
    parent::__construct();
    $this->_table_city = "location_city";
    $this->_table_district = "location_district";
    $this->_table_ward = "location_ward";
    $this->_table_country = "location_country";
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.title", "$this->table.type", "$this->table.name_with_type", "$this->table.latitude", "$this->table.longitude"); //thiết lập cột sắp xếp
    $this->column_search = array("$this->table.title", "$this->_table_country.title"); //thiết lập cột search
    $this->order_default = array("$this->table.city_id"); //cột sắp xếp mặc định
  }

  private function __where($args, $typeQuery = null, $isGetCountry = 0)
  {
    $select = array("$this->table.*");
    //$lang_code = $this->session->admin_lang; //Mặc định lấy lang của Admin
    $page = 1; //Page default
    $limit = 10;
    extract($args);
    //$this->db->distinct();
    $this->db->select($select);
    $this->db->from($this->table);
    if (!empty($recent)) {
      $this->db->join("$this->_table_search", "$this->table.id = $this->_table_search.city_id");
      $this->db->order_by("$this->_table_search.updated_time", 'DESC');
    }
    if (!empty($district_id))
      $this->db->where("$this->table.district_id", $district_id);

    if (!empty($city_id))
      $this->db->where("$this->table.city_id", $city_id);

    if (!empty($in))
      $this->db->where_in("$this->table.id", $in);

    if (!empty($or_in))
      $this->db->or_where_in("$this->table.id", $or_in);

    if (!empty($is_featured)) {
      $this->db->where($this->table . '.is_featured', $is_featured);
    }
    if (!empty($order_by_name))
      $this->db->order_by("$this->table.slug", 'ASC');
    if (!empty($not_in))
      $this->db->where_not_in("$this->table.id", $not_in);

    if (!empty($or_not_in))
      $this->db->or_where_not_in("$this->table.id", $or_not_in);
    $this->_get_datatables_query();
    if (!empty($search)) {
      if (empty($this->table_trans)) $this->table_trans = $this->table;
      $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
      $this->db->like("$this->table_trans.title", xss_clean(trim($search)));
      $this->db->group_end(); //close bracket
    }

    if (!empty($country_id)) {
      $this->db->where($this->table . '.country_id', $country_id);
    }

    if ($typeQuery === null || empty($typeQuery)) {
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

  public function getTotalLocation($args = [])
  {
    $this->__where($args, "count");
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function getAllLocaltion($type = 'city', $column = '*')
  {
    $this->db->select($column);
    $table = 'location_' . $type;
    $this->db->from($table);
    $this->db->order_by($table . ".id", 'DESC');
    $query = $this->db->get();
    return $query->result();
  }

  public function getTotalAllLocation($type = 'city')
  {
    $table = 'location_' . $type;
    $this->db->from($table);
    return $this->db->count_all_results();
  }

  public function saveCity($data)
  {
    $this->table = $this->_table_city;
    $this->save($data);
  }

  public function saveDistrict($data)
  {
    $this->table = $this->_table_district;
    $this->save($data);
  }

  public function saveWard($data)
  {
    $this->table = $this->_table_ward;
    $this->save($data);
  }

  public function getDataLocation($args = array(), $returnType = "object", $isGetCountry = 0)
  {
    $this->__where($args, null, $isGetCountry);
    if ($isGetCountry === 1) {
      $this->db->join($this->_table_country, "$this->_table_country.id = $this->table.country_id");
    }
    $query = $this->db->get();
    if ($returnType !== "object") return $query->result_array(); //Check kiểu data trả về
    else return $query->result();
  }

  public function loadNationality()
  {
    $url = FCPATH . 'application/third_party/location/nationalities.json';
    $data = file_get_contents($url);
    $data = json_decode($data);
    return $data;
  }

  public function loadInternational()
  {
    $url = FCPATH . 'application/third_party/location/international.json';
    $data = file_get_contents($url);
    $data = json_decode($data);
    return $data;
  }

  public function loadCity()
  {
    $url = FCPATH . 'application/third_party/location/tinh_tp.json';
    $data = file_get_contents($url);
    $data = json_decode($data);
    return $data;
  }

  public function loadDistrict()
  {
    $url = FCPATH . 'application/third_party/location/quan_huyen.json';
    $data = file_get_contents($url);
    $data = json_decode($data);
    return $data;
  }

  public function loadStreet()
  {
    $url = FCPATH . 'application/third_party/location/xa_phuong.json';
    $data = file_get_contents($url);
    $data = json_decode($data);
    return $data;
  }


  private function loadContent($url)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
  }

  public function getByIdlocation($conditions, $tablename = '')
  {
    $this->db->from($tablename);
    $this->db->where($conditions);
    $query = $this->db->get()->row();
    return $query;
  }

  //Load from database
  public function updateLocation($conditions, $data, $tablename = '')
  {
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
    return true;
  }

  public function getDataCity($params = array(), $isGetCountry = 0)
  {
    $this->table = $this->_table_city;
    $this->column_search = array("$this->table.title","$this->table.id","$this->table.slug","$this->table.region","$this->table.name_with_type");
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.title", "$this->table.type", "$this->table.name_with_type", "$this->table.latitude", "$this->table.longitude"); //thiết lập cột sắp xếp
    $list = $this->getDataLocation($params, "object", $isGetCountry);
    return $list;
  }

  public function getDataCountry($params = array())
  {
    $this->table = $this->_table_country;
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.title", "$this->table.currency_name", "$this->table.currency_code", "$this->table.currency_symbol", "$this->table.format", "$this->table.status");
    return $this->getDataLocation($params);
  }
  public function getLocationByIdSelect2($id,$type ='city')
  {
    $table = 'location_' . $type;
    $this->db->select('id,title')->where('id',$id);
    $result = $this->db->get($table)->result();
    if(!empty($result)){
      $result[0]->text = $result[0]->title;
    }
    return $result;
  }

  public function getCityById($cityId)
  {
    return $this->single(array('id' => $cityId), $this->_table_city);
  }

  public function getCityByName($cityName, $country_id)
  {
    return $this->single(array('UPPER(title)' => strtoupper($cityName), 'country_id' => $country_id), $this->_table_city);
  }

  public function updateCityData($city, $country)
  {
    log_message('error', $city);
    log_message('error', $country);
    if (!empty($this->getCityById($city)))
      return $city;
    $cityByName = $this->getCityByName($city, $country);
    if (!empty($cityByName))
      return $cityByName->id;
    $this->insert(array('title' => $city, 'country_id' => $country), $this->_table_city);
    return $this->db->insert_id();
  }

  public function getCountryCurrencyCd($currencyCd)
  {
    return $this->single(array('currency_code' => $currencyCd), $this->_table_country);
  }

  public function getCountryById($countryId)
  {
    return $this->single(array('id' => $countryId), $this->_table_country);
  }

  public function getCountryByName($countryName)
  {
    return $this->single(array('UPPER(title)' => strtoupper($countryName)), $this->_table_country);
  }

  public function updateCountryData($country)
  {
    log_message('error', $country);
    if (!empty($this->getCountryById($country)))
      return $country;
    $countryByName = $this->getCountryByName($country);
    if (!empty($countryByName))
      return $countryByName->id;
    $this->insert(array('title' => $country, 'status' => 1, 'currency_apply' => 0), $this->_table_country);
    return $this->db->insert_id();
  }

  public function getCountryByCurrencyCode($currencyCode)
  {
    return $this->single(array('currency_code' => $currencyCode), $this->_table_country);
  }

  public function getDataDistrict($params = array())
  {
    $this->table = $this->_table_district;
    $this->column_search = array("$this->table.title","$this->table.id","$this->table.type","$this->table.name_with_type");
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.title", "$this->table.city_id", "$this->table.type", "$this->table.name_with_type", "$this->table.is_featured", "$this->table.longitude", "$this->table.latitude"); //thiết lập cột sắp xếp
    return $this->getDataLocation($params);
  }

  public function getDistrictById($districtId)
  {
    return $this->single(array('id' => $districtId), $this->_table_district);
  }

  public function getDataWard($params)
  {
    $this->table = $this->_table_ward;
    $this->column_search = array("$this->table.title","$this->table.id","$this->table.type","$this->table.name_with_type");
    $this->column_order = array("$this->table.id", "$this->table.id", "$this->table.title", "$this->table.district_id", "$this->table.city_id", "$this->table.type", "$this->table.name_with_type", "$this->table.latitude", "$this->table.longitude"); //thiết lập cột sắp xếp
    return $this->getDataLocation($params);
  }
  public function getDistrictByIdCity($city){
    $query =$this->db->where('city_id',$city)->order_by('title','asc')->get($this->_table_district);
    return $query->result();
  }

  public function getWardById($id)
  {
    return $this->single(array('id' => $id), $this->_table_ward);
  }

  public function getCityByLatLong($params = array())
  {
    $this->db->select(array('A.*', '(
1 * ACOS( COS( RADIANS( ' . $params['latitude'] . ' ) ) * COS( RADIANS( A.latitude ) ) * COS( RADIANS( A.longitude ) - RADIANS( ' . $params['longitude'] . ' ) ) + SIN( RADIANS( ' . $params['latitude'] . ' ) ) * SIN( RADIANS( A.latitude ) ) )
) AS distance'));
    $this->db->from($this->_table_city . ' A');
    $this->db->order_by('distance', 'ASC');
    $this->db->limit(1);
    return $this->db->get()->result();
  }

  public function getDataDistrictFeatured($limit = 20)
  {
    $this->db->select(array('id', 'title'));
    $this->db->from($this->_table_district);
    $this->db->order_by('is_featured', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result();
  }

  public function getDataCityFeatured($params = array())
  {
    $this->db->select('a.id,a.title,a.thumbnail');
    $this->db->from($this->_table_city . ' a');
    if (!empty($params['type'])) {
      $this->db->where('b.type', $params['type']);
      $this->db->join($this->_table_search . ' b', "a.id = b.city_id");
    }
    if (!empty($params['is_featured'])) $this->db->order_by('a.is_featured', 'DESC');
    $this->db->order_by('a.order', 'DESC');
    $this->db->limit($params['limit']);
    return $this->db->get()->result();
  }

  public function search_district($params = array())
  {
    $this->db->select('a.id, a.city_id, a.title, a.order, a.name_with_type ,b.title as city_title');
    $this->db->from($this->_table_district . ' a');
    $this->db->join($this->_table_city . ' b', 'b.id = a.city_id');
    $this->db->group_start();
    $this->db->like('a.title', $params['keyword']);
    $this->db->or_like('a.name_with_type', $params['keyword']);
    $this->db->group_end();
    $offset = ($params['page'] - 1) * $params['limit'];
    $this->db->limit($params['limit'], $offset);
    $this->db->order_by('order', 'DESC');
    return $this->db->get()->result();
  }

  public function seach_city($params = array())
  {
    $this->db->select('a.id, a.title, a.order, a.name_with_type');
    $this->db->from($this->_table_city . ' a');
    $this->db->group_start();
    $this->db->like('a.title', $params['keyword']);
    $this->db->or_like('a.name_with_type', $params['keyword']);
    $this->db->group_end();
    $offset = ($params['page'] - 1) * $params['limit'];
    $this->db->limit($params['limit'], $offset);
    $this->db->order_by('order', 'DESC');
    return $this->db->get()->result();
  }

}