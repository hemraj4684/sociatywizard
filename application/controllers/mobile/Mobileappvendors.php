<?php
class Mobileappvendors extends Controlmobile {
	public function __construct(){
		parent::__construct();
		if(!$this->authenticated_user()){$this->output->set_header('HTTP/1.0 403 Forbidden');}
		$this->load->model('mobilemodel');
	}
	public function vendors_list(){
		$vendors = $this->mobilemodel->vendors_list($this->input->server('HTTP_SOCIETY'))->result();
		echo '<h5 class="blue-grey lighten-5 font-25 bold-700 center gallery-folder-h5"><i class="red-text text-accent-3 fa fa-male"></i> Vendors</h5>';
		if(empty($vendors)){
			echo '<div class="na-data">No Vendors Available</div>';
		} else {
			echo '<div class="row"><div class="col s12">';
			foreach ($vendors as $key => $vendor) {
				echo '<div class="card"><div class="card-content">';
				echo '<span class="card-title">'.h($vendor->contact_name);
				if(!empty($vendor->category)){
					echo ' - '.h($vendor->category);
				}
				echo '</span><table class="bordered table">';
				echo '<tr><th>Contact No</th><td>';
				if(!empty($vendor->contact_number_1)){
					echo h($vendor->contact_number_1);
					echo ' <a href="tel:'.h($vendor->contact_number_1).'" class="btn blue white-text btn-small"><i class="fa fa-phone"></i> call</a>';
				} else {
					echo '<i>N/A</i>';
				}
				echo '</td></tr>';
				echo '<tr><th>Alternate No</th><td>';
				if(!empty($vendor->contact_number_2)){
					echo h($vendor->contact_number_2);
					echo ' <a href="tel:'.h($vendor->contact_number_2).'" class="btn blue white-text btn-small"><i class="fa fa-phone"></i> call</a>';
				} else {
					echo '<i>N/A</i>';
				}
				echo '</td></tr>';
				echo '<tr><th>Address</th><td>';
				if(!empty($vendor->address)){
					echo h($vendor->address);
				} else {
					echo '<i>N/A</i>';
				}
				echo '</td></tr>';
				echo '</table>';
				echo '</div></div>';
			}
			unset($vendor);
			echo '</div></div>';
		}
	}
}