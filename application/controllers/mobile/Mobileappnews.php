<?php
class Mobileappnews extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
		$this->load->model('newsmodel');
	}
	public function category_list(){
		$category = $this->newsmodel->news_category_parent();
		echo '<div class="row data-list home-list mb0">';
		foreach ($category as $key => $value) {
			echo '<a href="#parent_news_list" data-id="'.$value->id.'" data-transition="slide" class="n_cat_obj grey-text text-darken-3 waves-effect col s12 data-list-item"><div class="col s3"><i class="fa '.$value->c_icon.'"></i></div><div class="col s9">'.h($value->category_name).'</div></a>';
		}
		unset($value);
		echo '</div>';
	}
	public function category_listing($id=''){
		if(is_valid_number($id)){
			if($this->newsmodel->news_category_exist($id,'no_of_news,category_name',$category)){
				echo '<h5 class="blue-grey lighten-5 font-25 bold-700 center help-list-h5"><i class="fa fa-newspaper-o"></i> News - '.h($category->category_name).'</h5><div class="col s12">';
				$data = $this->newsmodel->get_category_wise_news_list($id)->result();
				if(!empty($data)){
					foreach ($data as $key => $value) {
						echo '<a href="#reading_news" data-id="'.h($value->id).'" data-transition="slide" class="nr_cat_obj card block grey-text text-darken-3"><div class="card-image"><img src="'.base_url('assets/news_img/'.h($value->news_cover)).'"></div><div class="card-content font-20 bold-500">'.h($value->title).'</div></a>';
					}
					unset($value);
				} else {
					echo '<div class="na-data">No Data Available</div>';
				}
			} else {
				echo '<div class="na-data">No Data Available</div>';
			}
		} else {
			echo '<div class="na-data">No Data Available</div>';
		}
	}
	public function show_news($id=''){
		echo '<div class="col s12">';
		if(is_valid_number($id)){
			if($this->newsmodel->news_exist($id,'id,title,news_body,news_cover,date_added',$data)){
				echo '<h4>'.h($data->title).'</h4>';
				echo '<img src="'.base_url('assets/news_img/'.h($data->news_cover)).'" class="responsive-img z-depth-1">';
				echo '<div class="text-bs">'.strip_tags($data->news_body,'<hr><u><br><b><p><strong><i><em><span><div><h1><h2><h3><h4><h5><h6><address><pre><ol><li><blockquote>').'</div>';
			} else {
				echo '<div class="na-data">No Data Available</div>';
			}
		}
		echo '</div>';
	}
}