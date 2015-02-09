<?php
class Cacheable_model extends CI_model
{
	private $meminstance;
	private $_memcache_host = 'localhost';
	private $_memcache_port = 11211;
	private $_cache_timeout = 300;

	function __construct()
	{
		parent::__construct();
		$this->meminstance = new Memcache();
	}

	function add_to_cache($key, $value)
	{
		$this->meminstance->connect($this->_memcache_host, $this->_memcache_port);
		$this->meminstance->add($key, $value, False, $this->_cache_timeout);
		$this->meminstance->close();
	}

	function get_from_cache($key)
	{
		$this->meminstance->connect($this->_memcache_host, $this->_memcache_port);
		$value = $this->meminstance->get($key);
		$this->meminstance->close();
		return $value;
	}
}