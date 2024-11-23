<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Mahasiswa_model extends CI_Model
{
    public function getAllMahasiswa()
    {
        // $client = new Client();

        // $response = $client->request('GET','http://localhost/belajar/belajarbackend/belajarcirestapi/standard/mahasiswa',[
        //     'query' => [
        //         'X-API-KEY' => 'restapi123',
        //     ]
        // ]);

        // $result = json_decode($response->getBody()->getContents(),true);

        // return $result['data'];

    	$query = $this->db->get('mahasiswa');
    	return $query->result_array();
    }

    public function tambahDataMahasiswa($data)
    {
    	$this->db->insert('mahasiswa',$data);
        return $this->db->affected_rows();
    }

    public function hapusDataMahasiswa($nim)
    {
    	$this->db->where('nim',$nim);
    	$this->db->delete('mahasiswa');
        return $this->db->affected_rows();
    }

    public function getMahasiswaById($nim)
    {
    	return $this->db->get_where('mahasiswa',['nim'=>$nim])->row_array();
    }

    public function cariDataMahasiswa()
    {
    	$keyword = $this->input->post('keyword');
    	$this->db->like('nama',$keyword);
    	$this->db->or_like('nim',$keyword);
    	$this->db->or_like('email',$keyword);
    	return $this->db->get('mahasiswa')->result_array();
    }

    public function ubahDataMahasiswa($data, $id)
    {

    	$this->db->where('nim',$id);
    	$this->db->update('mahasiswa',$data);
        return $this->db->affected_rows();
    }

}
