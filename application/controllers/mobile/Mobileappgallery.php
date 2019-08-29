<?php
class mobileappgallery extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
	}
	public function folders(){
		echo '<h5 class="blue-grey lighten-5 font-25 bold-700 center gallery-folder-h5"><i class="red-text text-accent-3 mdi-image-photo-library"></i> Gallery</h5>';
		if($this->input->server('HTTP_SOCIETY')){
			$this->load->model('mobilemodel');
			$soc = $this->input->server('HTTP_SOCIETY');
			$folders = $this->mobilemodel->get_gallery_folders($soc)->result();
			if(!empty($folders)){
				foreach ($folders as $folder) {
					echo '<div class="col s6"><a class="block" data-transition="slide" href="gallery-items.html?id='.$folder->id.'"><h1 class="mb0"><i class="folder-icon mdi-file-folder"></i></h1><p class="black-text ellipsis-txt mt0">('.h($folder->no_of_pics).') '.h($folder->folder_name).'</p></a></div>';
				}
				unset($folder);
			} else {
				echo '<div class="na-data">No Photos In This Gallery</div>';
			}
			echo '</div>';
		} else {
			echo '<div class="na-data">No Photos In This Gallery</div>';
		}
	}
	public function gallery_files($id=0){
		if(is_valid_number($id)){
			$this->load->model('mobilemodel');
			$soc = $this->input->server('HTTP_SOCIETY');
			$files = $this->mobilemodel->get_gallery_files($id,$soc);
			if(empty($files)){
				echo '<div class="na-data">No photos available in this album</div>';
			} else {
				foreach ($files as $file) {
					echo '<div class="col s6 gal-item-parent"><a title="'.h($file->caption).'" class="swipebox block" href="'.base_url('assets/gallery/'.$soc.'/'.$file->image_name).'"><span class="gallery-image" data-img="'.base_url('assets/gallery/'.$soc.'/'.$file->image_name).'" style="background-image:url('.base_url('assets/gallery/'.$soc.'/'.$file->image_name).');"><i class="fa fa-spin fa-spinner"></i></span></a></div>';
				}
				unset($file);
			}
		}
	}
}