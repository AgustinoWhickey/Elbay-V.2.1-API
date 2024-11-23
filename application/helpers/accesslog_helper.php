<?php 

 function is_logged_in()
 {
 	$ci = get_instance();
 	if(!$ci->session->userdata('email')){
		redirect('auth');
	} 
 }

 function check_access()
 {
 	$ci = get_instance();

 	$roleid = $ci->session->userdata('role_id');

	if($roleid == 3){
		redirect('auth/blocked');
	}

 }

 function pdf_generator($html, $filename)
 {
	$ci = get_instance();

	$dompdf = new Dompdf\Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('A4', 'landscape');
	$dompdf->render();

	$dompdf->stream($filename, array('Attachment' => 0));
 }

 function indo_currency($nominal)
 {
	$result = "Rp. ".number_format($nominal, 0, ',', '.');
	return $result;
 }

 function update_stock($data)
 {
	$ci = get_instance();

	$menuitems = array();
	$stockbahan = array();

	$ci->db->select('menu_item.*');
    $ci->db->from('menu_item');
	$ci->db->where('item_id',$data['item_id']);

    $result = $ci->db->get()->result();
	foreach($result as $res){
		$ci->db->select('*');
		$ci->db->from('menu_item');
		$ci->db->where('product_id',$res->product_id);

		array_push($menuitems, $ci->db->get()->result());
	}

	foreach($menuitems as $menuitem){
		foreach($menuitem as $item){
			$ci->db->select('*');
			$ci->db->where('id', $item->item_id);
			$stock = $ci->db->get('item')->row();
			array_push($stockbahan, intval($stock->stock/(int)$item->qty));
		}
		$newstock = min($stockbahan);
		$prodid = $menuitem[0]->product_id;
		$sql = "UPDATE product_item SET stock = '$newstock' WHERE id = '$prodid'";

        $ci->db->query($sql);
		$stockbahan = array();
	}

	return $ci->db->affected_rows() == 1;
 }

