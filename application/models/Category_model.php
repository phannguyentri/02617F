<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Category_model extends CRM_Model
{
    private $statuses;
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Get task by id
     * @param  mixed $id task id
     * @return object
     */
    public function get_all($except = []) {
        if(is_admin()) {
            $where = [];
            foreach($except as $value) {
                $where["id !="] = $value;
            }
            $this->db->where($where);
            return $this->db->get('tblcategories')->result_array();
        }
    }
    public function get_full_detail($id='') {
        $categories = array();
        if($id == '') {
            $this->db->where('category_parent', '0');
            $categories = $this->db->get('tblcategories')->result();
        }
        else {
            $this->db->where('category_parent', $id);
            $categories = $this->db->get('tblcategories')->result();
        }
        if(count($categories) == 0) {
            return array();
        }
        else {
            foreach($categories as $key=>$category) {
                $categories[$key]->items = $this->get_full_detail($category->id);
            }
            return $categories;
        }
    }
    public function get_level1() {
        $this->db->where('category_parent', '0');
        $items = $this->db->get('tblcategories')->result_array();
        if($items) {
            return $items;
        }
        return false;
    }
    public function get_childs($parent_id) {
        if(is_numeric($parent_id)) {
            $this->db->where('category_parent', $parent_id);
            $items = $this->db->get('tblcategories')->result_array();
            if($items) {
                return $items;
            }
        }
        return array();
    }
    public function get_full_childs_id($parent_id, &$result) {
        array_push($result, $parent_id);
        $this->db->where('category_parent', $parent_id);
        $items = $this->db->get('tblcategories')->result();
        foreach($items as $value) {
            $this->get_full_childs_id($value->id, $result);
        }
    }
    public function get_single($id) {
        if(is_numeric($id)) {
            $this->db->where('id', $id);
            $item = $this->db->get('tblcategories')->row();
            if($item)
                return $item;
        }
        return false;
    }
    public function get_single_by_name($name) {
        if(trim($name) != '') {
            $this->db->where('category', $name);
            $item = $this->db->get('tblcategories')->row();
            
            if($item) {
                return $item;
            }
        }
        return false;
    }
    public function get_by_id($id_parent=0,&$array_category=[], $level=0) {
        if(is_admin() && is_numeric($level)) {
            $this->db->where(array('category_parent' => $id_parent));
            $current_level = $this->db->get('tblcategories')->result_array();
            
            foreach($current_level as $key=>$value) {
                $sub = "";
                for($i=0;$i<$level;$i++){
                    $sub.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                switch($level) {
                    case 1:
                        $sub.= "&rarr;";
                        break;
                    case 2:
                        $sub.= "&#8649;";
                        break;
                    case 3:
                        $sub.= "&#8667;";
                        break;
                }
                $current_level[$key]['category'] = $sub . " " .$current_level[$key]['category'];
                array_push($array_category, $current_level[$key]);
                if($level< 3)
                    $this->get_by_id($value['id'], $array_category, $level+1);
            }
        }
    }
    public function get_roles()
    {
        $is_admin = is_admin();
        $roles = $this->db->get('tblroles')->result_array();
        return $roles;
    }
    public function add_category($data)
    {
        if (is_admin()) {
            $this->db->insert('tblcategories',$data);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function update_category($data_vestion,$id)
    {
        if (is_admin()) {
            // var_dump($data_vestion);die();
            $this->db->where('id',$id);
            $this->db->update('tblcategories',$data_vestion);
            if ($this->db->affected_rows() >0) {
                return true;
            }
            return false;
        }
        return false;
    }
    public function delete_category($id)
    {
        if (is_admin()) {
            
            $childs = $this->db->where('category_parent', $id)->get('tblcategories')->result();
            if(count($childs) == 0) {
                $this->db->where('id', $id);
                $this->db->delete('tblcategories');
                if ($this->db->affected_rows() > 0) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }
    public function get_row_category($id)
    {
        if (is_admin()) {
            $this->db->select('tblcategories.*');
            $this->db->where('tblcategories.id', $id);
            return $this->db->get('tblcategories')->row();
        }
    }

//    public function get_call_logs_and_staff($id, $where = array())
//    {
//        $this->db->where('c.ID', $id);
//        $this->db->join('tblstaff s', 'c.assigned = s.staffid', 'left');
//        $this->db->select('s.*');
//        $this->db->from('tblcall_logs c');
////        $task = $this->db->get('tblcall_logs')->row();
////        $task->assignees       = $this->get_task_assignees($id);
//        return $this->db->get()->result_array();
//    }
//    public function get_task_assignees($id)
//    {
//        $this->db->select('id,tblstafftaskassignees.staffid as assigneeid,assigned_from,firstname,lastname');
//        $this->db->from('tblstafftaskassignees');
//        $this->db->join('tblstaff', 'tblstaff.staffid = tblstafftaskassignees.staffid', 'left');
//        $this->db->where('taskid', $id);
//        return $this->db->get()->result_array();
//    }
//    public function get_all_assignees()
//    {
//        $this->db->select('*');
//        $this->db->from('tblstaff');
////        $this->db->join('tblstaff', 'tblstaff.staffid = tblstafftaskassignees.staffid', 'left');
////        $this->db->where('taskid', $id);
//        return $this->db->get()->result_array();
//    }
//

//
//
//    public function remove_assignee($id, $taskid)
//    {
//        $task = $this->get($taskid);
//        $this->db->where('id', $id);
//        $assignee_data = $this->db->get('tblstafftaskassignees')->row();
//        $this->db->where('id', $id);
//        $this->db->delete('tblstafftaskassignees');
//        if ($this->db->affected_rows() > 0) {
//            if ($task->rel_type == 'project') {
//                $this->projects_model->log_activity($task->rel_id, 'project_activity_task_assignee_removed', $task->name . ' - ' . get_staff_full_name($assignee_data->staffid), $task->visible_to_client);
//            }
//            return true;
//        }
//        return false;
//    }
//
//
//
//    public function update($data,$id)
//    {
//        if (is_admin()) {
//            $this->db->where('ID', $id);
//            if(!isset($data['checkout']))
//            {
//                $data['checkout']=0;
//            }
//            $data['assigned']=implode(',',$data['assigned']);
//            $this->db->update('tblcall_logs',$data);
//            if ($this->db->affected_rows() >0) {
////                logActivity('Reminder Deleted [' . ucfirst($reminder->rel_type) . 'ID: ' . $reminder->id . ' Description: ' . $reminder->description . ']');
//                return true;
//            }
//            return false;
//        }
//        return false;
//    }
//    public function add($data,$idlead)
//    {
//        if (is_admin()) {
//            if(!isset($data['checkout']))
//            {
//                $data['checkout']=0;
//            }
//            $data['id_lead']=$idlead;
//            $data['assigned']=implode(',',$data['assigned']);
//            $this->db->insert('tblcall_logs',$data);
//            if ($this->db->affected_rows() >0) {
//                return true;
//            }
//            return false;
//        }
//        return false;
//    }
}
