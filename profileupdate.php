<?php
function profileupdate()
{
    $_POST = json_decode($this->input->raw_input_stream, true);
    extract($_POST);
    
    $profile_array = array();
    
    if(!empty($_FILES['shop_image']['name'])){
        $config['upload_path']          = './my-assets/image/shop';
        $config['allowed_types']        = '*';
        $config['max_size']             = 0;
        $config['max_width']            = 0;
        $config['max_height']           = 0;
        $config['encrypt_name'] = true;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('shop_image')){
        $profile_array['shop_image'] = 'my-assets/image/shop/'.$this->upload->data()['file_name'];
        $delete_array = array('shop_id'=>$shop_id);
        $shop_obj = $this->db->get_where("vendor_shop",$delete_array)->row();
        if($shop_obj->shop_image !='my-assets/image/shop/shop_default.png'){
        unlink($shop_obj->shop_image);
        }
        }
      }
      
      if(!empty($shop_name)){$profile_array['shop_name']=$shop_name;}
      if(!empty($shop_description)){$profile_array['shop_description']=$shop_description;}
      if(!empty($shop_category)){$profile_array['shop_category']=$shop_category;}
      if(!empty($shop_location)){$profile_array['address2']=$shop_location;}
      if(!empty($pincode)){$profile_array['pincode']=$pincode;}
      if(!empty($address1)){$profile_array['address1']=$address1;}
      if(!empty($profile_array)){
        $this->db->where('shop_id',$shop_id);
        $this->db->update('vendor_shop',$profile_array);
      }
      $response = $this->db->select("*")
                           ->from('vendor_shop')
                           ->where('shop_id',$shop_id)
                           ->get()->row();
      $response->bank_details = json_decode($response->bank_details);
        echo json_encode(array('status'=>true,'Message'=>"Profile Updated Successfully ","Data"=>$response));
  }
    else
    {
        echo json_encode(array('status'=>false,'Message'=>"Something Wrong" ));
    }
    $this->session->set_flashdata('success', 'Record Updated Successfully');
    redirect('admin/shop');
    {
        echo json_encode(array('status'=>false,'Message'=>"Profile Update Failed","Data"=>$this->input->post()));
    }
    $this->session->set_flashdata('flash_message', 'Profile Updated Successfully');
    redirect('admin/shop');