<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
		$this->load->model('model_brands');
		$this->load->model('model_category');
		$this->load->model('model_stores');
		$this->load->model('model_attributes');
	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->render_template('products/index', $this->data);	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchProductData()
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();

		foreach ($data as $key => $value) {

            $store_data = $this->model_stores->getStoresData($value['store_id']);
			// button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
    			$buttons .= '<a href="'.base_url('Controller_Products/update/'.$value['id']).'" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-danger btn-sm" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
			

			// $img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';
			$img = '<img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxIPEhUQEhMWFhAXFxgZFRcYFxgXFhgaFhMYFhYXFhMYHSggHxooHRcXITEjJSorLi4uFx8zODMsNygtLisBCgoKDg0OGhAQGy0mICYvLS0tMi0tLS0tLS0tLS0tLS0tLS0tLS0tLy0tLS0tLS0tLS0vLS0tLS0tLS0tLS0tLf/AABEIAOEA4QMBIgACEQEDEQH/xAAcAAEAAgMBAQEAAAAAAAAAAAAABgcDBAUCAQj/xABBEAACAQIBCgIGCQIGAgMAAAAAAQIDEQQFBhIhMUFRYXGBIpEHEzJCobEUIzNSYnKCksFD0aKy0uHw8STCFkRj/8QAGgEBAAIDAQAAAAAAAAAAAAAAAAMEAQIFBv/EADMRAAIBAQQGCgICAwEAAAAAAAABAgMEESExBRITQXGhIlFhgZGxwdHh8BQyQvFSYrJD/9oADAMBAAIRAxEAPwC8QAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADzOSSu3ZcSLZx550cJenD62utTin4Yv8UuPJa+hXGWc4MRjH9bUejuhHwxX6d/V3ZHKokc+1aSpUOisZdS9X/ZZmUs9sHQutN1JcIK6/e7R8myMYz0kVX9lRhFcZScn5LRt8TiZEzRxWKtKMNCm/fleKf5VtflbmTTJvo8w1OzrSlVlvXsQ8o+L4mic5ZFNVLfaMYLVXh53vkQ/EZ746X9bRXBQppebTfxPFLL+UamuFWrL8sb/JFr4PJFCh9nRhHmoq/eW06BnZy3vzJlo+s/3rS7r/f0Kjp5YyvHY6/ein/mizYjn1jqP2sItfjg4vzSS+Bah8M7Nr+RIrDUj+taXfj6kHyb6R6E9Vam6b4xenHvqUvJMlmAylSxMdKjUjNb7PWusdq7mvjMgYWt9pRg296WjL98bMj+KzBhGXrMJWnRqLZduS6KSakvNmemu0kX5VPO6a7OjL28iaghlHLmLwTUMdS06Wz18FdL80YrZ2T5MleExMK0FUpyUoPY1sZspJlmnWjPBYPeng13eqvXabAANiUAAAAAAAAAAAAAAAAAAAHxgHipNRTbaSWtt6kktrbK1zuz2lVboYaTjT2SqLVKXJX2R57Xy34s+c63Xk8NRl9Qnacl/Ua12T+6vjt4EdyFkarjaqpU1zlJ+zGPF/wt5BOd+EThW23SqS2NDhet/YvV+ixwZMybVxE1SowcpPyS4yexLmWfm5mVRwtp1LVa/Frwxf4YvfzfwOzkTI9LB0/V0l+aT9qT4yf8bEdQ3hTSxZasejYUUpTxlyXD3AAJDpgAAAAAAAAHmST1NajgyyI6E3WwbUG3edF/ZVOi9yXBrVyJADDV5pOCln94fcd+BpYHFKrHY4zWqcJe1F8HbV0a1PajdMTpK6l7y1X5cHyMpk2V+8AAGQAAAAAAAAAAAAAAAQj0i5wOjD6LSdqtReJrbGO5dZa+yfFEsyjjI0KU60/ZhFt8+S5t6u5RmUMbPEVZ1pu85ybfLglySsl0IqsrlccvSlq2VPUjnLkt/jl4nnBYSdepGlTV5ydor/m7f2LpzfyNDBUlSjre2ct8nvfTckRv0bZD9XB4qa8c7qF90U7N92vJcydilG5Xmui7Js4bSWb5L53gAEp1gAAAAAAAAAAAAAcnLGXaGDV6s0pboLXJ9I/y7IN3GspKK1pO5HWNHKWVaOGWlWqRgtyftPpFa32RXGW8/wCvWvGgvUw4+1N99i7a+Zr5u5qV8dL11VtUXrc5a5VPyp7fzPV1InUvd0Vec6WkdeWzs8dZ9eS4/biV/wDzOWIn6nBUJVJb5z8MYr7zW23VpkhybhqsVpV6unUe6K0KceUY7X1k2+hkybk2lhoerpQUY8trfGT2tm6bxT3suUqc1jUle+zBLgvVgAGxOAAAAAAAAAAAAQH0pZT0YU8LF65vSn+WLSinycrv9BAskYB4mvToR2zklfgtsn2Sb7HSz4xrrY2rr8MWoR5KKs/8Wk+52vRbgtKrUrNaqcEl1nfX5Ra/UVn05nmKq/Kt2q8r7u6Ofr4lk0KMacYwirRilGKW5JWSMwBZPTgAAAAAAAAAAAAw4ivCnFznJRhFXcm7JdWzQy5lqjgqfrKr1v2Yr2pPgl/OxFTZw5xVsdK83amn4YL2Y83xlzfaxpOoo8SjbLdCzq7OXV7klzkz/lK9PCeGO+q14n+WD2dXr5Ig05yqSu3KU5PW23KUm+e1sYehKpJQhFynJ2ilrbfQtTNHNCGESq1EpYm2r7tO+6PGXGXlzhSlNnFhCvb6nSeC8FwW9/WzlZpZjWtXxcbvbGk9i5z/ANPnwLBSt0PQLEYqKuR6Gz2enQjqwXu+JjrSai2ldpXS423HyjVU4qUXeLSafFNXTMpyMg1LKrR30akor8krVIdkpKP6TN5K3c0jrgAGwAAAAAAAAAMdWooxcnsSbfZXMhz8uz0cNXktqpVH5QYyMN3YlFVKjnJyftSbb6t3ZanowoaOEc985yfaNor4p+ZVJcno/jbAUeem/OrMr0l0jzWhlfXbf+L80SMAFg9MCP5Gzkp4qvWoLU6cvA/vxVlJrpK/Zrmes8crfRMLOadqkvBT46Ut/ZXfYp/AYqeHqQrU3acXePbU0+TV10ZFOpqtHMttv2FSMVxlwy+f7L+Bzsi5Thi6MK8NklrW+MlqlF9H/c6JKdKMlJJrJgAAyDh5y5wU8BT0peKpL7OF9cnxfCK3syZwZbp4Ki6s9b2Qjezk+HJcXuKex2PqYms6tZ3nJ9EluiluiR1J6uCzOdb7cqC1Ifs+Xb7eJ5ynlGriqjq1ZaU35JboxW5Lh/Jgw9CVSShCLlOTtFLa2z3VjeyS8V7JJa3w1JFpZlZrrCQ9bUX/AJEl+xPcvxcX26wRi5M4dmss7TVab7W/u8zZpZrwwMdOVpYiS8UtyXCP8veScAtJJK5HqqdONOKhBXJAAGTcEbwkvV5SrR3VaVOfen4fk35EkIdlOro5Yw34qMk/Kq/mkaT3cSCu1HVl/sufR9SYgA3JwAAAAAAAAAc7OCN8NXX/AOVT/JI6JgxdL1lOcPvRlHzTRh5GGr1cfn8uT0fSvgKPLTXlVkU2kWt6MK+lhHDfCcl2l4l8XIgpPpHmdDO6vxi/NMmIBzcv5Q+jYepWSu4Rula+t6o35XavyuWG7j00pKKbeSK69IeVfXYn1UX4KKa5aTs5vtqjysyK7bd/i/8Acxym5Nybbk3dve29bYuym3e7zxtau6tRze/6uRJ8x8v/AEWroTdqFRpS4RlqUZfw+WvcWw92vUfn8tP0e5e+kUvo9R3rU1qb2zjsT6rUn25ktKX8TraJtf8A4y7vVeq8OolmlqMOLxUKMJVZytCCbk+S/ndbmblkVb6Rcv8Aran0Wm/qqb8bWyUuHSOzrfgiWUtVHUtdoVCm5vu7X9xZxM4sszxlV1Zao7IR+4uF/vb3zfQ5cn8n82+PTzMbZ38z8hPHVlGV/UwtKfT3Uub19kyri2eVjr16lyxlJ/fvUSP0f5urw4yquVGLXP27fLz4E9TPcKaSSSSS2JbFbZZHvRRbjFRVx6yz0I0YKEf7fWYltMkZJ7D7YJWMk59AAAIFlippZaw6+6kv8E5f+xPStMBP1+XJVFrUZSXaFFw+aI6m7iUra8KceuceTv8AQssAEhdAAAAAAAAAAAAKNznwvqcXXp7lO66SWlH4SRIfRbjdCvUovZUgmvzQbdl2lJ/pM3pTydo1KeJS1SWjL80dce7Tf7CHZLx0sNWp147YSTtxXvLurruVf1keWm/xbbrPJO/ufwy/DHOCaaaumrNPWmnuaPGGrxqQjODvCSUovimrozlo9SVJntmu8JL11JN4eT6+rb918uD7cLxM/QOIoRqRlCaUoSVpJ7GnuKfzuzblgal1d0JvwS4b9GT+9813tXqQuxWR5vSVg2bdWn+u9dXx5eUfNnJuOnh6sK1N2nB3XB8U+TV13NU+kRyYtxd6zLXy7nbCOCVei/rKqcaa3xlsm3zj83HcyqBfdu/v/wBLyPhtKTlmWrXa52iSctyu9/H24vJQpSnJQim5SaUUtrbdkkXZm1keOCoRpKzltqS+9J632WxckQ30Z5D0pPFzWqN40ub2Sn22Lq+BZJLSjcrzsaJsupDayzeXD58gACY7AAAAAABpZVxiw9GpWfuRb6v3V3dl3K+9F2Hc69as9ejC1+Mpyevr4H5m/wClDKtoQwsXrl4p9E1oJ9Xd/pR1PR3k/wBThFJrxVZOX6b6Me1lf9RE8aiXUcyb2ttjFZQV74v6uZKwASnTAAAAAAAAAAAAORnLktYzDzo+81eD4SjrXns6NlIyi4tpqzTs09qa2pn6FKv9JGQfVVPpdNeCo7VEvdlx6S+fUhqx/kcbS9m1oKrHNZ8PjyOj6NMuaUXg5vxRvKlffF3co9U9fRvgT8/P2GxEqU41INxlFpxa3NFzZs5chjqKmtVRaqkPuvl+F7n/ACmZpSv6Jvou17SGylmsu1e6y4HbNPKGCp4inKlUjpQkrNfJp7mtqZuAlOq1fgykM5chVMDV0Ja6bu4S3SX+pb1/c45euWclU8ZSdGotT1p+9GW6UXx/3RTOW8k1MHVdGota1xlulHdJf81FapDV4Hl9IWHYS1o/q+XZ7GgbeSsDPE1oUIe1N2vwW1yfJK77GoWZ6Nci6EJYqa8U/DDlFPxPu15R5msY6zuK9js+3qqG7N8PnImOBwcKNONKCtCCSXbe+e/ubQBbPYJXYIAAGQAAAaeUsdDD0p1qjtCKu+L3JLm3ZLqbUpJK72IqXPnOX6XU9VTf1EH+97NLpuXd79Wk5aqKtstUbPT1nnu4+yzZzqMZ5Uxmv2qstdvcittuUUtXRcS6KFJQioRVoxSSXBJWSIf6OcheopPETVqlVeFPbGF7ru9T6KPMmpilG5X9ZBo2hKFNzn+0sX958twABIdEAAAAAAAAAAAAGtjMLCtCVOotKEk1JcmbIBhq/ApHObIU8DVcJXdN3cJ/eXP8S3r+5qZHypUwtRVaTtJbVukt8ZLgXRljJdLF0nSqq8XrTXtRe6UXuf8A0VBnDm/VwM9GavB+xNLwy/tLivntK04auKPNW2xTs09pS/X/AJLXzfy7Sx1PTg7SXtwftRf8rg/+jslAYLGVKE1UpScJrY18ua5MsnN3PylVtTxFqVT739OXV+6+urnuJIVE8zo2PScKvRqYS5P2fZ4E2OLnHkSnjqTpy1SWuE98X/Ke9fzY68ZppNO6exrYeyVrczpzhGcXGSvTKSweblaeLWDnFxnpeN7lFeJyT3q2zm0i56FGNOMYRVoRSUUtySskfPo8NP1mivWaOjpar6Ld2r8LpMzmkIapVsljhZ9bV3vluQABuXAAAAY6lRRTlJpRSu23ZJLa22cvLWcOHwa+tn4raoR1yfZbFzdkVhnLnXWxz0X4KF9VNPbwcn7z+HLeaSmolK1W6nZ1c8ZdXv1efYdXPPPD6RfD4dtUdk57HPkuEfn0262Y2bDxVT11Vf8AjQe/+pJe6vw8fLjbBmjmrPGyVSd44dPXLfO22MO+17upbeFw8KUIwhFRhFWilsSI4xc3rSOfZbPO1VPyK+W5dfx58M81j6ATndAAAAAAAAAAAAAAAAAABq43BU68HTqRUoS2p/NcHzRtAGGk1cyq848w6tG9TD3qUtuj78f7rpr5byGNWP0OcTLObGGxl3Uhaf34PRl3ex90yGVLejjWnREZdKk7ux5d3V5cCqMj5wYnCfZVGo74PxRf6Xs6qzJjk70kR1LEUWvxQd1+yVrebOblT0eV4XdGUaseD8EvJvRfmuhF8bkyvQ+1pShzlFpdpbH2I75wKCnbLJg77uF68cVzLbweeGCq6lXSfCScfjJW+J06eVKEvZrU30nF/JlCg3VZk8dNzu6UU+Du9y/Xj6S21IL9cf7mniM4cJT9rEU+ilpPyjdlHWA2z6jL03LdBePwi1cd6Q8LBfVKVV7rLQj3ctfwIrlbPzFVrxg1Rj+HW/3vX5JHFwGRMTiLeqoyknv0bR/fK0fiSvJPo4nLXiKiivux8Uu8nqT8zXWnI121vtOEU0uxXLxePMhEITqysk5VJPYruUm/i2TzNnMF6quL1LaqSev9U18l57iZZIyJh8IrUaai98nrk+snr7bDqG8aSWZcsuioQetV6T5fP3Ax0qaglGKSilZJKySWxJGQAmOuAAAAAAAAAAAAAAAAAAAAAAAAAAAD4fQAc/EZGw1TXOhTk+LhG/na5pyzTwT/APrx7OS+TO4DDijR04SzS8EcSGamCj/Qj3cn82b2GyXQp/Z0qcXxjCKfmkboCiluEacI5JLuQABk3AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/Z" class="img-circle" width="50" height="50" />';

            $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $qty_status = '';
            if($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            } else if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }


			$result['data'][$key] = array(
				$img,
				// $value['sku'],
				$value['name'],
				$value['price'],
                $value['qty'] . ' ' . $qty_status,
                $store_data['name'],
				$availability,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}	

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function create()
	{
        // echo 'came';
        // exit();
		if(!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
		// $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
		$this->form_validation->set_rules('availability', 'Availability', 'trim|required');
		
	
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$upload_image = $this->upload_image();

        	$data = array(
        		'name' => $this->input->post('product_name'),
        		// 'sku' => $this->input->post('sku'),
        		'price' => $this->input->post('price'),
        		'qty' => $this->input->post('qty'),
        		'image' => $upload_image,
        		'description' => $this->input->post('description'),
        		'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
        		'brand_id' => json_encode($this->input->post('brands')),
        		'category_id' => json_encode($this->input->post('category')),
                'store_id' => $this->input->post('store'),
        		'availability' => $this->input->post('availability'),
        	);

        	$create = $this->model_products->create($data);
        	if($create == true) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		redirect('Controller_Products/', 'refresh');
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('Controller_Products/create', 'refresh');
        	}
        }
        else {
            // false case

        	// attributes 
        	$attribute_data = $this->model_attributes->getActiveAttributeData();

        	$attributes_final_data = array();
        	foreach ($attribute_data as $k => $v) {
        		$attributes_final_data[$k]['attribute_data'] = $v;

        		$value = $this->model_attributes->getAttributeValueData($v['id']);

        		$attributes_final_data[$k]['attribute_value'] = $value;
        	}

        	$this->data['attributes'] = $attributes_final_data;
			$this->data['brands'] = $this->model_brands->getActiveBrands();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
			$this->data['stores'] = $this->model_stores->getActiveStore();        	

            $this->render_template('products/create', $this->data);
        }	
	}

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
	public function upload_image()
    {
    	// assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        // $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            
            $data = array(
                'name' => $this->input->post('product_name'),
                // 'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'attribute_value_id' => json_encode($this->input->post('attributes_value_id')),
                'brand_id' => json_encode($this->input->post('brands')),
                'category_id' => json_encode($this->input->post('category')),
                'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
            );

            
            if($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);
                
                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('Controller_Products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('Controller_Products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
            $attribute_data = $this->model_attributes->getActiveAttributeData();

            $attributes_final_data = array();
            foreach ($attribute_data as $k => $v) {
                $attributes_final_data[$k]['attribute_data'] = $v;

                $value = $this->model_attributes->getAttributeValueData($v['id']);

                $attributes_final_data[$k]['attribute_value'] = $value;
            }
            
            // false case
            $this->data['attributes'] = $attributes_final_data;
            $this->data['brands'] = $this->model_brands->getActiveBrands();         
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            $this->render_template('products/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
	}

}