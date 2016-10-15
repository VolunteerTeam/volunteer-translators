<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * CLASS
 * *********************************************************************
 * several functions to convert a menu, from a FLAT array into a MULTIDIM/NESTED array
 * or into an HTML dropdown OR HTML list
 * *********************************************************************
 ** ::_flat_to_nested ** convert FLAT into MULTIDIM/NESTED
 *
 ** ::_nested_to_ul ** convert MULTIDIM/NESTED into HTML LIST
 *
 ** ::_nested_to_dropdown ** convert MULTIDIM/NESTED into HTML SELECT/OPTGROUP/OPTION
 *    no nested optgroup allowed
 *    <optgroup> tag can only contains <options> tags = w3c standards
 *
 ** ::_nested_to_dropdown_html ** convert MULTIDIM/NESTED into HTML SELECT/OPTGROUP/OPTION
 *    multiple nested optgroups allowed
 *

 * USAGE:
 *   $list = {array of objects}
 *   $t = new myNested;
 *   $result = $t->index( $list );
 *
 */
class CI_Nested {

    //results
    var $parserStrFin="";

    //iterations
    var $it=0;

    //level
    var $profondeur=0;

    function  __construct()
    {

        $this->tree = '';
        $this->CI =& get_instance();

    }


    function get_joomla_tree($parent_id = 0,$table = 'jos_menu')
    {
        $this->CI->db->order_by('ordering');
        $result = $this->CI->db->get_where($table,array('parent'=>$parent_id));
        if($result->num_rows()>0)
        {
            foreach($result->result() as $k=> $item)
            {
                $data[$k]['item'] = $item->name;

                $data[$k]['childs'] = $this->get_joomla_tree($item->id,$table);
            }
            return $data;
        }
        else
        {
            return array();
        }

    }


    function get_tree($parent_id = 0,$table = 'catalog')
    {


        $result = $this->CI->db->query("
            SELECT c.*,ct.add,ct.edit,ct.toggle,ct.delete
            FROM catalog c
            LEFT JOIN catalog_types ct
            ON ct.ct_id=c.type
            WHERE c.pid=?
            ORDER BY c.position

        ",array($parent_id));

        if($result->num_rows()>0)
        {
            foreach($result->result() as $k=> $item)
            {
                $data[$k]['item'] = $item;
                $data[$k]['childs'] = $this->get_tree($item->id,$table);
            }
            return $data;
        }
        else
        {
            return array();
        }

    }


    function bulid_sortable_tree($data = array(),$_autoCall = false, $profondeur=0, $it=0)
    {

        if(!empty($data))
        {

            if (!$_autoCall)
            {
                $this->parserStrFin="";
                $this->it=0;
                $this->profondeur=0;
            }

            $this->it++;

            $firstListType = 'ol';//first level list type
            $itemsListType = 'ul';//sub level list type

            $firstListId = 'sections';

            $tag = (empty($this->profondeur) && $this->it==0) ? $firstListType : $itemsListType;

            $idAttr = (empty($this->profondeur) && $this->it==0) ? $firstListId : 'level-'.$this->profondeur;

            $this->tree .= "\n".'<'.$tag.' class="dd-list" id="'.$idAttr.'">'."\n";


            foreach($data as $k=>$v)
            {
                if(!empty($v['item']))
                {
                    $this->profondeur++;



                    $this->tree .= '<li id="'.$v['item']->id .'" data-id="'.$v['item']->id .'" class="dd-item">';
                    $this->tree .= '<div  class="dd-handle">'. strip_tags($v['item']->title) .'
                        <div class="controls dd-nodrag">';

      /*              $this->tree .='
                    <span class="small">Активен по умолчанию</span> <input data-id="'.$v['item']->id.'" class="toggle_pin" type="checkbox" name="my-checkbox" '.($v['item']->default==1?'checked':'').' >';
                    if($v['item']->deletable==1)
                        {
                            $this->tree .= ' <a id="'.$v['item']->id.'" href="/admin/remove_catalog_item/'.$v['item']->id.'" class="btn btn-xs btn-danger show-delete-dialog" title="Удалить">
                            <i class="glyphicon glyphicon-remove"></i></a>';
                        }
                        if($v['item']->editable==1){
                            $this->tree .= ' <a href="/cpanel/catalog/edit/'.$v['item']->id.'" class="btn btn-xs btn-default" title="Редактировать"><i class="glyphicon glyphicon-pencil"></i></a>';
                        }
                        if($v['item']->addable==1){
                            $this->tree .= ' <a href="/cpanel/catalog/add/'.$v['item']->id.'" class="btn btn-xs btn-default" title="Добавить подраздел"><i class="glyphicon glyphicon-plus"></i></a>';
                        }
                        $this->tree .= '
                            <a href="#" class="btn btn-xs btn-success" title="Опубликовано"><i class="glyphicon glyphicon-ok-circle"></i></a>
                            </div>';
                        */

                    $this->tree .='
                    ';

                    $this->tree .='
                    <div class="dropdown">';

                    if($v['item']->type !=11)
                    {
                        $this->tree .='
                        <span class="small">Раскрыт по умолчанию</span> <input data-id="'.$v['item']->id.'" class="toggle_pin" type="checkbox" name="my-checkbox" '.($v['item']->default==1?'checked':'').' >
                    ';
                    }



                    $this->tree .='    <i class="status glyphicons '.($v['item']->status ==1?'':'eye_close').' pull-right"></i>
                                            <a href="#" data-toggle="dropdown" class="glyphicons edit pull-right"></a>
                                            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">';

                    if($v['item']->add ==1)
                    {
                        $this->tree .='             <li><a role="menuitem" href="/cpanel/catalog/add/'.$v['item']->id.'" title="Добавить подраздел">Добавить подраздел</a></li>';
                    }

                    if($v['item']->edit ==1)
                    {
                        $this->tree .='             <li><a role="menuitem" tabindex="-1" href="'.base_url('cpanel/catalog/edit/'.$v['item']->id).'">Редактировать</a></li>';
                    }

                    if($v['item']->toggle ==1)
                    {
                        $this->tree .='             <li><a
                                                        data-show="Показывать"
                                                        data-hide="Скрыть"
                                                        data-module="catalog"
                                                        data-id="'.$v['item']->id.'"
                                                        class="toggle_status"  role="menuitem" tabindex="-1" href="#">'.($v['item']->status ==1?'Скрыть':'Показывать').'</a></li>';
                    }

                    if($v['item']->delete ==1)
                    {
                        $this->tree .='                           <li class="divider"></li>
                                                <li><a role="menuitem" class="i_delete" tabindex="-1" href="'.base_url('cpanel/catalog/remove/'.$v['item']->id).'"><i class="pull-left glyphicons remove_2"></i>Удалить</a></li>';
                    }


                      $this->tree .='                      </ul>
                                        </div>

                    ';

                    $this->tree .='</div></div>'."\n";







                }

                if(!empty($v['childs']))
                {
                    $this->tree .= $this->bulid_sortable_tree($v['childs'], true, $this->profondeur, $this->it );
                }


            }


            if ($this->profondeur == 0)       { $this->tree .= "</".$firstListType.">\n"; $this->profondeur--; }
            else if (($this->profondeur > 0)) { $this->tree .= "</".$itemsListType.">\n"; $this->profondeur--; }

        }
    }


    function build_sortable_navigation($data = array(),$_autoCall = false, $profondeur=0, $it=0)
    {

        if(!empty($data))
        {

            if (!$_autoCall)
            {
                $this->parserStrFin="";
                $this->it=0;
                $this->profondeur=0;
            }

            $this->it++;

            $firstListType = 'ol';//first level list type
            $itemsListType = 'ul';//sub level list type

            $firstListId = 'sections';

            $tag = (empty($this->profondeur) && $this->it==0) ? $firstListType : $itemsListType;

            $idAttr = (empty($this->profondeur) && $this->it==0) ? $firstListId : 'level-'.$this->profondeur;

            $this->tree .= "\n".'<'.$tag.' class="dd-list" id="'.$idAttr.'">'."\n";


            foreach($data as $k=>$v)
            {
                if(!empty($v['item']))
                {
                    $this->profondeur++;



                    $this->tree .= '<li id="nav'.$v['item']->id .'" data-id="'.$v['item']->id .'" class="dd-item">';
                    $this->tree .= '<div  class="dd-handle">'. htmlentities($v['item']->title) .'
                        <div class="controls dd-nodrag">';

                    $this->tree .= ' <a id="'.$v['item']->id.'" data-catalog_id="'.$v['item']->id.'" href="/admin/remove_nav_item/'.$v['item']->id.'" class="btn btn-mini btn-danger show-delete-dialog" title="Удалить"><i class="icon-remove"></i></a>';
                    $this->tree .= ' <a href="/admin/edit_nav_item/'.$v['item']->id.'" class="btn btn-mini" title="Редактировать"><i class="icon-pencil"></i></a>';
                    $this->tree .= ' <a href="/admin/add_nav_item/'.$v['item']->id.'" class="btn btn-mini" title="Добавить подменю"><i class="icon-plus-sign"></i></a>';
                    $this->tree .= '
                        <a href="#" class="btn btn-mini btn-success" title="Опубликовано"><i class="icon-ok-circle"></i></a>
                        </div>
                    </div>'."\n";

                    //$this->tree .= '<li>'.$v['item']->title;
                }

                if(!empty($v['childs']))
                {
                    $this->tree .= $this->build_sortable_navigation($v['childs'], true, $this->profondeur, $this->it );
                }


            }


            if ($this->profondeur == 0)       { $this->tree .= "</".$firstListType.">\n"; $this->profondeur--; }
            else if (($this->profondeur > 0)) { $this->tree .= "</".$itemsListType.">\n"; $this->profondeur--; }

        }


    }


}