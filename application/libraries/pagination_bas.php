<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Pagination_Bas {
    private $table;
	private $where = array();
    private $ci;
    private $url='';
    private $attr_table = array();
    private $field = array();
    private $link = array();
    private $result=array();
    private $data;
    private $offset;
    private $limit;
    private $page;
    private $table_id=null;
	private $component_id='';
    
    function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->database();
    }
    
    public function set_table_id($id){
        $this->table_id = $id;
    }
    
    public function set_component_id($id){
        $this->component_id = $id;
    }
    
    public function set_paging($data,$limit,$page){
        $this->data = $data;
        $this->limit = $limit;
        $this->page = $page;
    }
    
    public function set_table($table){
        $this->table = $table;
    }
    
    public function set_where($where){
		$this->where = $where;
    }
    
    public function get_rows_limit(){
		$this->ci->db->where($this->where);
        $this->ci->db->like($this->data);
        $this->ci->db->limit($this->limit,$this->offset);
        $select = $this->ci->db->get($this->table);
        return $select->result_array();
    }
    
    public function get_rows_count(){
		$this->ci->db->where($this->where);
        $this->ci->db->like($this->data);
        $select = $this->ci->db->get($this->table); 
        return $select->num_rows();
    }
    
    public function page()
    {		
        $result['count'] =  $this->get_rows_count();
        $this->page =  ($this->page>0) ? $this->page : 1;
        $page_count = ceil($result['count'] / $this->limit);
        $this->offset = ($this->page - 1) * $this->limit;
        if ($this->offset < 0) $this->offset = 0;		
		if ($this->ci->db->platform() == 'oci8') { // fix bug : offset oracle start from 1 (20140704)
			$this->offset = $this->offset + 1;
		}
		$result['platform'] = $this->ci->db->platform();
        $result['page'] = $this->page;
        $result['page_offset'] = $this->offset;
        $result['limit'] = $this->limit;
        $result['page_back'] = $this->offset + 1;		
        $result['page_next'] = $this->offset + $this->limit;	
        $result['page_count'] = $page_count;
        if ($result['page_next'] > $result['count']) $result['page_next'] = $result['count'];
        $result['result_array']= $this->get_rows_limit();
        return $result;
    }
    
    public function set_ajax_url($url){
        $this->url = $url;
    }
    
    public function set_field($field,$label='',$attribut=null){
        if(is_array($field)){
            $this->field = $field;
        }else{
            $this->field[] = array('field'=>$field,'label'=>$label,'attribut'=>$attribut);
        }
    }
    
    public function set_link($link,$label,$attribut=null){
        if(is_array($link)){
            $this->link = $link;
        }else{
            $this->link[] = array('link'=>$link,'label'=>$label, 'attribut'=>$attribut);
        }
    }
    
    public function set_attr_table($attribut){
        if(is_array($attribut)){
            foreach ($attribut as $key => $value) {
                $this->attr_table[]="$key='$value'";
            }
        }else{
            $this->attr_table[]=$attribut;
        }
    }
    
    public function set_attr($attribut){
        if(is_array($attribut)){
            foreach ($attribut as $key => $value) {
                $attr="$key='$value'";
            }
        }else{
            $attr=$attribut;
        }
        return $attr;
    }
    
    private function get_thead(){
        $res = '<thead><tr>';
        foreach($this->field as $field){
            $res .= '<th>'.$field['label'].'</th>';
        }
		if (count($this->link) > 0) {
			$res .= '<th>Aksi</th>';
		}
        $res .= '</tr>';
        $res .= '<tr>';
        foreach($this->field as $field){
            $res .= '<td><input type="text" id="'.$field['field'].'" name="'.$field['field'].'" onkeyup="'.$this->component_id.'_cari(1)" '.$this->set_attr($field['attribut']).'/></td>'; 
        }
		if (count($this->link) > 0) {
			$res .= '<td></td>';
		}
        $res .= '</tr></thead>';
        return $res;
    }
    
    public function generate_table_content(){
        $s = 
        "<form id=\"{$this->component_id}_frm_src\" action=\"#\">
            <table ".implode(' ',$this->attr_table).">
              ".$this->get_thead()."
              <tbody id=\"{$this->component_id}_body_table\">
              </tbody>
            </table>
        </form>
        " ;
        return $s;
    }
    
    public function generate_link_pagination(){
        $s = '<ul id="'.$this->component_id.'_pagination" class="pagination"></ul>';
        return $s;
    }
    
    public function generate_ajax_script(){
        $s = '<script type="text/javascript">
                    function '.$this->component_id.'_cari(page)
					{
                        $.ajax({
                            url : "'.$this->url.'/"+page,
                            data : $("#'.$this->component_id.'_frm_src").serialize(),
                            method: "post",
                            complete: '.$this->component_id.'_cari_complete
                        })
                    }

                    function '.$this->component_id.'_cari_complete(data,status)
					{
						var xhr = data.responseText;
						eval("var rs = " + xhr + ";");
						
						var tbody = document.getElementById("'.$this->component_id.'_body_table");
						// proses Delete DOM Element
						while (tbody.childNodes.length > 0) {
							tbody.removeChild(tbody.childNodes[0]);
						}
						
						// proses Add DOM Element
						for (var i=0; i<rs.isi_table.length; i++) {
							var tr = document.createElement("TR");
							for (var j=0; j<rs.isi_table[i].length; j++) {
								var td = document.createElement("TD");
								td.innerHTML = rs.isi_table[i][j];
								tr.appendChild(td);
							}
							tbody.appendChild(tr);
						}
						//document.getElementById("'.$this->component_id.'_body_table").innerHTML  = rs.isi_table;
						
						var limit = rs.rs.limit;
						var count = rs.rs.count;
						var page = rs.rs.page;
						var page_back = rs.rs.page_back;
						var page_next = rs.rs.page_next;
						var page_offset = rs.rs.offset;
						var page_count = rs.rs.page_count;
						// var dis1= (page == 1)?"disabled":"" ;
						// var dis2=(page == page_count)?"disabled":"" ;
						var list=""
                         
						list += "<li><a href=\'javascript:'.$this->component_id.'_cari(1);\' onclick=\"\">&laquo;</a></li>";

						for(var i=1;page_count>=i;i++){
							// var dis = "";
							// if(parseInt(page) == i){
								// dis = "disabled";
							// }
							list += "<li><a href=\"javascript:'.$this->component_id.'_cari("+ i +");\" onclick=\'\'>"+i+"</a></li>";
						}
						list += "<li><a href=\"javascript:'.$this->component_id.'_cari("+ page_count +");\" onclick=\"\">&raquo;</a></li>";
						$("#'.$this->component_id.'_pagination").html(list);
                    }
                    $(document).ready(function(){
						'.$this->component_id.'_cari(1);
                    });
                </script>
                ';
        return $s;
    }
	
	public function generate_all(){
        $table_content    = $this->generate_table_content();
        $ajax_script      = $this->generate_ajax_script();
        $link_pagination  = $this->generate_link_pagination();
		return "
			$table_content  
			$ajax_script  
			$link_pagination
		";
	}
    
    public function generate_table_data(){
        $isi_table = array();
        $arr_result = '';
        $arr_table = $this->page();

        //var_dump($arr_table['result_array']);
        foreach($arr_table['result_array'] as $value){ 
            $id = ($this->table_id != null) ? '/'.$value[$this->table_id] : '#' ;
			$tr = array();
			
            foreach($this->field as $val){
				if (!isset($val['script'])) {
					$s = $val['field'];
					// $isi_table .= '<td>'.$value[$s].'</td>';
					$tr[] = $value[$s];
				} else {
					eval("\$temp = {$val['script']}");
					// $isi_table .= '<td>'.$temp.'</td>';
					$tr[] = $temp;
				}
            }

			if (count($this->link) > 0) {
				// $isi_table .= "<td>";            
				$td = "";
				foreach($this->link as $val){
					// $isi_table .= '<a href="'.$val['link'].$id.'" '.$this->set_attr($val['attribut']).'>'.$val['label'].'</a>';
					$td .= '<a href="'.$val['link'].$id.'" '.$this->set_attr($val['attribut']).'>'.$val['label'].'</a>';
				}
				// $isi_table .= "</td>";
				$tr[] = $td;
			}

			$isi_table[] = $tr;
        }
        $arr_result['isi_table'] = $isi_table;
        $arr_result['rs'] = $this->page();
        //$arr_result['halaman'] = $page['halaman'];	

        echo json_encode($arr_result);
    }
}
