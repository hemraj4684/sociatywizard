<?php
class Docsform extends Requestcontrol {
	public function add(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('folder', 'Folder Name', 'trim|required|max_length[50]|callback_foldername_check');
		if($this->form_validation->run()){
			$this->load->model('documentsmodel');
			$data = array('folder_name'=>$this->input->post('folder'),'description'=>$this->input->post('description'),'created_by'=>$this->session->user,'society_id'=>$this->session->soc);
			$this->documentsmodel->add($data);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('folder'=>form_error('folder'),'description'=>form_error('description')));
		}
	}
	public function update_folder(){
		$this->form_validation->set_error_delimiters('', '');
		$is_u='';
		if(trim($this->input->post('folder')) !== trim($this->input->post('old_folder'))){
			$is_u='|callback_foldername_check';
		}
		$this->form_validation->set_rules('folder', 'Folder Name', 'trim|required|max_length[50]'.$is_u);
		$this->form_validation->set_rules('id', 'Folder', 'required|is_natural_no_zero',array('required'=>'Please Select A Valid Folder'));
		if($this->form_validation->run()){
			$this->load->model('documentsmodel');
			$data = array('folder_name'=>$this->input->post('folder'),'description'=>$this->input->post('description'));
			$this->documentsmodel->update($data,$this->input->post('id'),$this->session->soc);
			echo json_encode(array('success'=>1));
		} else {
			echo json_encode(array('folder'=>form_error('folder'),'id'=>form_error('id'),'description'=>form_error('description')));
		}
	}
	public function foldername_check($name){
		$this->load->model('documentsmodel');
		$res = $this->documentsmodel->folder_exist($name,$this->session->soc);
		if($res){
			$this->form_validation->set_message('foldername_check', 'The folder with same name already exist');
			return false;
		}
		return true;
	}
	public function verify_folder($id){
		$this->load->model('documentsmodel');
		$res = $this->documentsmodel->verify_folder($id,$this->session->soc);
		if($res){
			return true;
		}
		$this->form_validation->set_message('verify_folder', 'This folder does not belong to you');
		return false;
	}
	public function delete_folder($id){
		if(is_valid_number($id)){
			$this->load->model('documentsmodel');
			$files = $this->documentsmodel->folder_files($id)->result();
			$this->documentsmodel->delete_folder($id,$this->society);
			if(!empty($files)){
				foreach ($files as $file) {
					if(!empty($file->file_name)){
						if(file_exists('assets/documents/'.$this->society.'/'.$file->file_name)){
							unlink('assets/documents/'.$this->society.'/'.$file->file_name);
						}
					}
				}
			}
		}
		$this->session->set_flashdata('folder_removed','<div class="card-panel white-text success-area green"><i class="fa fa-thumbs-o-up"></i> Folder has been successfully deleted!</div>');
		echo json_encode(array('success'=>1));
	}
	public function delete(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'File', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('folder', 'Folder', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('documentsmodel');
			$files = $this->documentsmodel->remove_file($this->input->post('id'),$this->input->post('folder'),$this->session->soc);
			if(!empty($files)){
				foreach ($files as $file) {
					if(!empty($file->file_name)){
						if(file_exists('assets/documents/'.$this->session->soc.'/'.$file->file_name)){
							unlink('assets/documents/'.$this->session->soc.'/'.$file->file_name);
						}
					}
				}
			}
		}
		$this->session->set_flashdata('file_removed','<div class="card-panel success-area white-text green"><i class="fa fa-thumbs-o-up"></i> File has been successfully removed!</div>');
		echo json_encode(array('success'=>1));
	}
	public function add_files(){
		$this->form_validation->set_error_delimiters('', '');
		if($this->input->server('CONTENT_LENGTH') < 10480000){
			$this->form_validation->set_rules('folder', 'Folder', 'required|is_natural_no_zero|callback_verify_folder',array('required'=>'Please Select A Folder','is_natural_no_zero'=>'Please Select A Valid Folder'));
			$files = $_FILES;
			if(isset($files['userfile']['name'])){
				$cpt = count($files['userfile']['name']);
				if($cpt < 6){
					if($cpt===1 && empty($files['userfile']['name'][0])){
						echo json_encode(array('error'=>'You have not selected any file to upload.'));
						exit();
					}
					if($this->form_validation->run()){
						$this->load->library('upload');
						$insert = array();
						if (!is_dir('assets/documents/'.$this->session->soc)) {
						    mkdir('assets/documents/' . $this->session->soc, 0777, TRUE);
						}
						for($i=0; $i<$cpt; $i++) {
							$_FILES['userfile']['name'] = $files['userfile']['name'][$i];
					        $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
					        $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
					        $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
					        $_FILES['userfile']['size'] = $files['userfile']['size'][$i];
					        $this->upload->initialize($this->set_upload_options($this->session->soc));
					        if($this->upload->do_upload()){
					        	array_push($insert, array('file_name'=>$this->upload->data()['file_name'],'folder_id'=>$this->input->post('folder'),'uploaded_by'=>$this->session->user));
					        	$this->session->set_flashdata('file_upload_success','<div style="display:block" class="card-panel white-text green success-area"><i class="fa fa-thumbs-o-up"></i> Files have been successfully uploaded</div>');
					        } else {
					        	$this->session->set_flashdata('file_upload_error','<div class="card-panel white-text accent-3 red"><i class="fa fa-warning"></i> Unable to upload 1 or more files.<br>May be the file size is too large or invalid file type.</div>');
					        }
						}
						$this->load->model('documentsmodel');
						$this->documentsmodel->insert_files($insert);
						echo json_encode(array('success'=>1));
					} else {
						echo json_encode(array('error'=>form_error('folder')));
					}
				} else {
					echo json_encode(array('error'=>'You can upload only 5 files at a time.'));
				}
			} else {
				echo json_encode(array('error'=>'You have not selected any file to upload.'));
			}
		} else {
			echo json_encode(array('error'=>'The files you are trying to upload is too large. You can upload only 5 files at a time. Each file must not be greater than 2 MB.'));
		}
	}
	private function set_upload_options($soc) {
	    $config = array();
	    $config['upload_path'] = 'assets/documents/'.$soc;
	    $config['max_filename'] = 50;
	    $config['allowed_types'] = 'doc|docx|jpg|png|pdf|xls|xlsx|zip|rar|xml|txt|csv|gif|jpeg|pptx|ppt';
	    $config['overwrite'] = FALSE;
	    $config['max_size'] = 2048;
	    return $config;
	}
	public function download_files(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id[]', 'File', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('folder', 'Folder', 'required|is_natural_no_zero');
		if($this->form_validation->run()){
			$this->load->model('documentsmodel');
			$res = $this->documentsmodel->get_files_by_id($this->input->post('id'),$this->input->post('folder'),$this->session->soc);
			if(!empty($res)){
				$files = array();
				$soc_id = $this->session->soc;
				foreach ($res as $key => $value) {
					if(file_exists('assets/documents/'.$soc_id.'/'.$value->file_name)){
						array_push($files,'assets/documents/'.$soc_id.'/'.$value->file_name);
					}
				}
				unset($value);
				if(!empty($files)){
					$zipname = 'assets/documents/files.zip';
					$zip = new ZipArchive;
					$zip->open($zipname, ZipArchive::CREATE);
					foreach ($files as $file) {
						$new_filename = substr($file,strrpos($file,'/') + 1);
						$zip->addFile($file,$new_filename);
					}
					$zip->close();
					header('Content-Type: application/zip');
					header('Content-disposition: attachment; filename='.$zipname);
					header('Content-Length: ' . filesize($zipname));
					readfile($zipname);
					unlink($zipname);
				} else {
					echo 'No File Selected';
				}
			} else {
				echo 'No File Selected';
			}
		}
	}
}