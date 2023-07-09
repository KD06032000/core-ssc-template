<?php
	/*
	*/
	function getDataRedis($key = null, $result_array = false)
	{
		if (ACTIVE_REDIS == TRUE) {
			$_this = &get_instance();
			$key_redis = REDIS_PREFIX . $key;
			return json_decode($_this->_redis->get($key_redis), $result_array);
		}
		return null;
	}

	function updateDataRedis($key, $data_change, $time = 7 * 24 * 60 * 60)
	{

	}

	function setDataRedis($key = null, $data, $time = 7 * 24 * 60 * 60)
	{
		if (ACTIVE_REDIS == TRUE) {
			$_this = &get_instance();
			$key_redis = REDIS_PREFIX . $key;
			return $_this->_redis->set($key_redis, json_encode($data), $time);
		}
		return null;
	}
	function deleteMultiRedis($list_id, $list_prefix = '')
	{
		if (ACTIVE_REDIS == FALSE) return;
		$_this = &get_instance();
		$list_key = [];
		foreach ($list_id as $id) {
			$list_key[] = REDIS_PREFIX . $list_prefix . $id;
		}
		$_this->_redis->del($list_key);
	}

	function deleteDataRedis($key = null)
	{
		if (ACTIVE_REDIS == TRUE) {
			$_this = &get_instance();
			if (is_array($key)) {
				$list_key = [];
				foreach ($key as $item) {
					$list_key[] = REDIS_PREFIX . $item;
				}
				$_this->_redis->del($list_key);
			} else {
				$key_redis = REDIS_PREFIX . $key;
				$_this->_redis->del($key_redis);
			}

		}
	}

	function updateItemDataRedis($key_redis, $item, $primary_key = 'id')
	{
		if (ACTIVE_REDIS == TRUE) {
			$data = (array)getDataRedis($key_redis);
			if (!empty($data)) {
				$_this = &get_instance();
				$key_redis_item = is_object($item) ? $item->{$primary_key} : $item[$primary_key];
				$data[$key_redis_item] = $item;
				$key_redis_time = 'redis_time_' . $key_redis;
				$time = !empty($_this->config->item($key_redis_time)) ? $_this->config->item($key_redis_time) : $_this->config->item('redis_time_default');
				setDataRedis($key_redis, $data, $time);
			}
		}
	}

	function deleteItemDataRedis($index, $key_redis, $time = '')
	{
		if (ACTIVE_REDIS == TRUE) {
			$data = (array)getDataRedis($key_redis);
			if (!empty($data)) {
				unset($data[$index]);
				if (empty($time)) {
					$_this = &get_instance();
					$time = $_this->config->item('redis_time_default');
				}
				setDataRedis($key_redis, $data, $time);
			}
		}
	}

	function getItemDataRedis($index, $key_redis)
	{
		if (ACTIVE_REDIS == TRUE) {
			$data = (array)getDataRedis($key_redis);
			return isset($data[$index]) ? $data[$index] : false;
		}
		return false;
	}

	function getKeyRedisAllTable($table)
	{
		return 'all_' . $table;
	}



