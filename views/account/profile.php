        <style>
.avatar-view {
    width: 220px; /* Fixed width */
    height: 220px; /* Fixed height */
    border-radius: 50%; /* Circular image */
    object-fit: cover; /* Cover the box without distortion */
}

</style>
        <div class="row">
        	<div class="col-md-12 col-sm-12 ">
        		<div class="x_panel">
        			<div class="x_title">
        				<h2>User Report <small>Activity report</small></h2>
        				<ul class="nav navbar-right panel_toolbox">
        					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
        					</li>
        					<li class="dropdown">
        						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
        						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        							<a class="dropdown-item" href="#">Settings 1</a>
        							<a class="dropdown-item" href="#">Settings 2</a>
        						</div>
        					</li>
        					<li><a class="close-link"><i class="fa fa-close"></i></a>
        					</li>
        				</ul>
        				<div class="clearfix"></div>
        			</div>
        			<div class="x_content">
        				<div class="col-md-3 col-sm-3  profile_left">
        					<div class="profile_img">
        						<div id="crop-avatar">
        							<!-- Current avatar -->
        							<img class="img-responsive avatar-view" src="assets/profilePic/default.png" alt="Avatar" title="Change the avatar">
        						</div>
        					</div>
        					<h3><?php echo ucwords($_SESSION["user_data"]["profile"]["first_name"]. " ". $_SESSION["user_data"]["profile"]["last_name"])?></h3>

        					<ul class="list-unstyled user_data">
        						<li><i class="fa fa-map-marker user-profile-icon"></i> <?php  echo $_SESSION['user_data']['barangay_name']. ', ' . $_SESSION['user_data']['city_name'] ?>
        					</li>

        					<li>
        						<i class="fa fa-briefcase user-profile-icon"></i> <?php echo $_SESSION['role']['name'] ?>
        					</li>


        				</ul>

        				<a class="btn btn-success uploadModal" style="color: white;"><i class="fa fa-upload m-right-xs"></i>Change Profile Picture</a>
        				<br />


        			</div>
        			<div class="col-md-9 col-sm-9 ">

        				<div class="row">

        					<form id="updateProfileForm" method="post" enctype="multipart/form-data">
        						<!-- User Info Fields -->
        						<div class="col-md-3 col-sm-3  form-group">
        							<label>Given Name</label>
        							<input type="text" name="first_name" placeholder="Given Name" class="form-control" >
        						</div>
        						<div class="col-md-3 col-sm-3  form-group">
        							<label>Middle Name</label>
        							<input type="text" name="middle_name" placeholder="Middle Name" class="form-control" >
        						</div>
        						<div class="col-md-3 col-sm-3  form-group">
        							<label>Family Name</label>
        							<input type="text" name="last_name" placeholder="Family Name" class="form-control" >
        						</div>
        						<div class="col-md-3 col-sm-3  form-group">
        							<label>Suffix</label>
        							<input type="text" name="suffix" placeholder="Suffix" class="form-control" >
        						</div>

        						<div class="col-md-6 col-sm-6  form-group">
        							<label>Birth Date</label>
        							<input type="date" name="birth_date" placeholder="Birth Date" class="form-control" 
        							>
        						</div>


        						<div class="col-md-6 col-sm-6  form-group">
        							<label>Birth Place</label>
        							<input type="text" name="birth_place" placeholder="Birth Place" class="form-control"> 
        						</div>

        						<div class="col-md-6 col-sm-6  form-group">
        							<label>Contact No.</label>
        							<input type="text" name="contact_no" placeholder="Contact No." class="form-control">
        						</div>

        						<div class="col-md-6 col-sm-6  form-group">

        							

        						</div>

        						<div class="col-md-12 col-sm-12  form-group">
        							<label>Address</label>
        							<textarea name="address" class="form-control" rows="5" style="resize: none;"></textarea>
        						</div>

        						<div class="col-md-12 col-sm-12">
        							<button type="submit" class="btn btn-primary"><i class="fa fa-edit m-right-xs"></i> Edit Profile</button>
        						</div>
        					</form>


        				</div>

        			</div>
        		</div>
        	</div>
        </div>
    </div>



<!-- Upload Profile Picture Modal -->
<div class="modal fade" id="uploadProfilePicModal" tabindex="-1" role="dialog" aria-labelledby="uploadProfilePicModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadProfilePicModalLabel">Upload Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadProfilePicForm" enctype="multipart/form-data">
                    <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*" required>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_data"]['id']; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitProfilePic">Upload</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	    function fetchProfile() {
    	$.ajax({
    		url: 'controllers/ProfileController.php?action=fetchprofile',
    		method: 'POST',
    		contentType: 'application/json',
    		data: JSON.stringify({ action: 'fetchProfile' }),
    		dataType: 'json',
    		success: function(response) {
    			if (response.success) {
    				let profile = response.profile;

                // Populate the form fields with the fetched profile data
                $('input[name="first_name"]').val(profile.first_name);
                $('input[name="middle_name"]').val(profile.middle_name);
                $('input[name="last_name"]').val(profile.last_name);
                $('input[name="suffix"]').val(profile.suffix);

                if (profile.birthdate) {
        // Extract just the date part from the birthdate string
        let birthdate = profile.birthdate.split(' ')[0]; // This will give you "1993-10-13"
        
        // Set the value of the date input
        $('input[name="birth_date"]').val(birthdate);
    } else {
        // If birthdate is not available, you can set it to an empty value
        $('input[name="birth_date"]').val('');
    }

    $('input[name="birth_place"]').val(profile.birthplace);
    $('input[name="contact_no"]').val(profile.contact_number);
    $('textarea[name="address"]').val(profile.address);

                // If profile picture is available, update the image source
                let profilePic = profile.profile_pic ? profile.profile_pic : 'assets/profilePic/default.png';
                $('.avatar-view').attr('src', profilePic);
                $('.profile_img').attr('src', profilePic);
                $('.gilidphoto').attr('src', profilePic);

            } else {
            	alert('Failed to load profile.');
            }
        },
        error: function(xhr, status, error) {
        	alert('An error occurred while fetching profile data.');
        }
    });
    }
</script>
    <script type="text/javascript">
    	$(document).on('submit', '#updateProfileForm', function(e) {
    		e.preventDefault();
    		let formData = new FormData(this); 
    		$.ajax({
    			url: 'controllers/ProfileController.php?action=UpdateData', 
    			type: 'POST',
    			data: formData,
    			contentType: false, 
    			processData: false, 
    			success: function(response) {
        //let result = JSON.parse(response);
        if (response.success) {
        	location.reload(); 
        } else {
        	alert(result.message);
        }
    },
});
    	});
    </script>



    <script type="text/javascript">
    	window.onload = function() {


    fetchProfile();




};

</script>
<script type="text/javascript">
	$(document).on('click', '.uploadModal', function() {
    // Check if profile data exists
    if ($.trim($('input[name="first_name"]').val()) !== '') { // Checking if first_name is populated
        $('#uploadProfilePicModal').modal('show');
    } else {
        alert('Please complete your profile information before uploading a profile picture.');
    }
});

// Handle the profile picture upload
$(document).on('click', '#submitProfilePic', function() {
    var formData = new FormData($('#uploadProfilePicForm')[0]);
    $.ajax({
        url: 'controllers/ProfileController.php?action=uploadProfilePic',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
        	console.log(response)
         fetchProfile();
                $('#uploadProfilePicModal').modal('hide');
        },
        error: function() {
            alert('An error occurred while uploading the profile picture.');
        }
    });
});

</script>