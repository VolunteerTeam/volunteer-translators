<?php if (!defined('BASEPATH')) exit('Нет доступа к скрипту');

class CI_Tree {

    var $path_array=array(); //УРЛ разобранный в массив по элементно
    var $path_array_level=array(); //УРЛ разобранный в массив по левелам
    var $uri_string='';
    var $class='';
    var $method='';
    var $parameters='';
    var $level='';
    var $db; //обьект DB
    var $uri; //обьект uri
    /**
     * Конструктор
     * @return
     * @param object $default_cfg[optional]
     */
    function __construct()
    {

        require_once(BASEPATH.'database/DB'.EXT);
        $this->db =& DB();
        $this->uri =& load_class('URI');
    }

    /**
     * Инициализация библиотеки
     * @return
     */
    function init()
    {
        $this->uri_string=$this->uri->uri_string();
        if($this->uri_string=='')$this->uri_string='/';
        $this->level=substr_count($this->uri_string,"/")-1;
        $this->_path_array();
        $this->_path_array_level();
        $query=$this->db->query("SELECT `class`,`method`,`parameters`,`name`,`title` FROM (`".$this->db->dbprefix."routes`) WHERE `path` IN ('".implode("','",$this->path_array_level)."') LIMIT ".count($this->path_array_level));
        $row=$query->result_array();
        $this->class=$row[count($row)-1]['class'];
        $this->method=$row[count($row)-1]['method'];
        $this->parameters=$row[count($row)-1]['parameters'];
    }


    function ruri_string()
    {
        return explode('/',$this->class."/".$this->method."/".$this->parameters);
    }

    /**
     * Преобразование пути в массив
     * @return
     */
    function _path_array()
    {
        $this->path_array=array();
        foreach(explode("/",$this->uri_string) as $val){if(trim($val)!='')$this->path_array[]=$val;}
    }

    /**
     * Преобразование пути в массив с уровнями
     * @return
     */
    function _path_array_level()
    {
        $this->path_array_level=array("/");
        foreach ($this->path_array as $val) {
            if(count($this->path_array_level) != count($this->path_array))$this->path_array_level[] = $this->path_array_level[count($this->path_array_level)-1].$val."/";
            else $this->path_array_level[] = $this->path_array_level[count($this->path_array_level)-1].$val;
        }
    }

}
?>