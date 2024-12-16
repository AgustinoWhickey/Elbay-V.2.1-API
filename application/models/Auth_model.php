<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function ceklogin($email)
    {
        $this->db->select('user.id, user.name as username, user.email, user.password, user.role_id, user.is_active, company.name as company_name, company.id as company_id, company.logo, company.address');
        $this->db->where('user.email', $email);
        $this->db->join('company', 'user.company_id = company.id');
        $aksi = $this->db->get('user')->result_array();
        return $aksi;
    }

    
    public function updateRegister($token)
	{
        $updated = time();

        $sql = "UPDATE user SET is_active = 1 WHERE token = '$token'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

    public function insertRegister($data)
    {
        $user = [
            'name' => $data['username'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'image' => 'default.jpg',
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role_id' =>  2,
            'is_active' => 0, 
            'token' => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,7), 
            'date_created' => time()
        ];

        $this->db->insert('user', $user);
        $pic_id = $this->db->insert_id();
        if($this->db->affected_rows() == 1){
            $company = [
                'pic_id' => $pic_id,
                'name' => $data['company'],
                'address' => '',
                'logo' => $data['image'],
                'phone_number' =>  '',
                'created_at' => time()
            ];

            $this->db->insert('company', $company);
            return $this->db->affected_rows();
        } 
        
        return 0;
        
    }
}
