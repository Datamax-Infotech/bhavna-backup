<?php 
ob_start(); // Turn on output buffering
if(isset($_REQUEST['action'] ) && $_REQUEST['action'] == 'logout'){
  $date_of_expiry = time() - 2 ;
  setcookie( "loginid", "", $date_of_expiry );
  header("Location: index.php");
  exit('Redirecting...'); // Ensure the remaining script doesn't execute
}
?>
<title>My Profile - Boomerange Portal</title>
<?php
require("inc/header_session_client.php");
require("mainfunctions/database.php");
require("mainfunctions/general-functions.php");
require_once('boomerange_common_header.php'); 

?>
<main>
  <div class="container mt-5">
    <div class="row">
      <div class="col-3">
        <div class="nav flex-column nav-pills my-account-tab" id="my-account-tab" role="tablist" aria-orientation="vertical">
          <button class="nav-link active" id="my-profile-tab" data-toggle="pill" data-target="#my-profile" role="tab" aria-controls="my-profile" aria-selected="true">My Profile</button>
          <button class="nav-link" id="change-password-tab" data-toggle="pill" data-target="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</button>
          <button class="nav-link" id="manage-address-tab" data-toggle="pill" data-target="#manage-address" role="tab" aria-controls="manage-address" aria-selected="false">Manage Addresses</button>
          <a href="user_profile.php?action=logout" class="nav-link" >Logout</a>
        </div>
      </div>
      <div class="col-9">
        <div class="tab-content" id="my-account-tabContent">
          <div class="tab-pane fade show active" id="my-profile" role="tabpanel" aria-labelledby="my-profile-tab">
            <?php
            $loginid = $_COOKIE['loginid'];
            db();
            $user_info_qry = db_query("SELECT user_name,user_email,password FROM boomerang_usermaster WHERE loginid = '" . $loginid . "'");
            $user_info = array_shift($user_info_qry);
            $user_name = $user_info['user_name'];
            ?>
            <div class="container_fluid">
              <div class="row">
                <div class="col-md-8">
                  <form id="edit_profile_form">
                    <div class="form-group">
                      <label>Name</label>
                      <input id="user_name" type="text" class="form-control form-control-sm" name="user_name" placeholder="Enter Name" value="<?php echo $user_name; ?>">
                      <span class="form_error d-none" id="user_name_error">Username Can't Be Blank</span>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input id="user_email" type="email" class="form-control form-control-sm" name="user_email" placeholder="Enter Email" value="<?php echo $user_info['user_email']; ?>">
                      <span class="form_error d-none" id="user_email_error">Email Can't Be Blank</span>
                    </div>
                    <div class="col-md-12 mb-3">
                      <input type="hidden" name="form_action" value="edit_profile">
                      <input type="hidden" name="user_id" value="<?php echo $loginid; ?>">
                      <button type="submit" class="btn btn-custom">Apply Changes</button>
                    </div>
                  </form>
                </div>
                <div class="col-md-4">
                  <p class="mt-2"><b>Name:</b> <?php echo $user_name; ?></p>
                  <p class="mt-2"><b>Email:</b> <?php echo $user_info['user_email']; ?></p>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
            <div class="col-md-8">
              <form id="change_password_form">
                <div class="form-group">
                  <label>Old Password</label>
                  <input id="user_password" type="password" class="form-control form-control-sm" placeholder="Enter Old Password">
                  <span class="form_error d-none" id="user_password_error"></span>
                </div>
                <div class="form-group">
                  <label>New Password</label>
                  <input id="new_password" type="password" class="form-control form-control-sm" name="new_password" placeholder="Enter New Password">
                  <span class="form_error d-none" id="new_password_error"></span>
                </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input id="conf_password" type="password" class="form-control form-control-sm" placeholder="Renter New Password">
                  <span class="form_error d-none" id="conf_password_error"></span>
                </div>
                <div class="mb-3">
                  <input id="user_old_password" type="hidden" class="form-control form-control-sm" value="<?php echo base64_decode($user_info['password']); ?>">
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo $loginid; ?>">
                  <button type="submit" class="btn btn-custom">Apply Changes</button>
                </div>
              </form>
            </div>
          </div>
          <div class="tab-pane fade" id="manage-address" role="tabpanel" aria-labelledby="manage-address-tab">
            <div class="text-right mb-3">
              <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAddressModal">Add Address</button>
            </div>
            <?php
            db();
            $address_qry = db_query("SELECT * FROM boomerang_user_addresses WHERE status = 1 && user_id = '" . $loginid . "' ORDER BY id DESC");
            if (tep_db_num_rows($address_qry) > 0) {
              while ($address = array_shift($address_qry)) { ?>
                <div class="card mt-3 <?php echo $address['mark_default'] == 1 ? "border-success" : ""; ?>">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-8">
                        <p><b>Name:</b> <?php echo $address['first_name']." ".$address['last_name'] ?></p>
                        <p><b>Company:</b> <?php echo $address['company'] ?></p>
                        <p><b>Address: </b><?php echo $address['addressline1'] ?></p>
                        <p><?php echo $address['addressline2'] ?></p>
                        <p><?php echo $address['city'] ?>, <?php echo $address['state'] ?>, <?php echo $address['zip'] ?></p>
                        <p><b>Country:</b> <?php echo $address['country'] ?></p>
                        <p><b>Mobile No:</b> <?php echo $address['mobile_no'] ?></p>
                        <p><b>Email:</b> <?php echo $address['email'] ?></p>
                        <p><b>Dock Hours:</b> <?php echo $address['dock_hours'] ?></p>
                      </div>
                      <div class="col-md-4">
                        <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" class="edit_address_btn text-primary"><span class="fa fa-pencil"></span> Edit</a><br>
                        <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" class="delete_address_btn text-danger"><span class="fa fa-trash"></span> Delete</a><br>
                        <?php if ($address['mark_default'] == 1) {
                          echo "<span class='text-success'><span class='fa fa-check'></span>Default Address</span>";
                        } else { ?>
                          <a href="javascript:void(0);" address_id="<?php echo $address['id']; ?>" class="text-success mark_default_add"><span class="fa fa-map-pin"></span> Set As Default</a>
                        <?php } ?>
                      </div>

                    </div>
                  </div>
                </div>
            <?php }
            } else {
              echo "<div class='card'><div class='card-body'><p class='text-danger'>You dont have any address added!</p></div></div>";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<div class="modal fade" id="addAddressModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
  <form id="add_address_form">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAddressModalLabel">Add Address</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>First Name</label>
              <input id="add_first_name" type="text" class="form-control form-control-sm" name="first_name" placeholder="Enter First Name">
              <span class="form_error d-none" id="add_first_name_error">First Name Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Last Name</label>
              <input id="add_last_name" type="text" class="form-control form-control-sm" name="last_name" placeholder="Enter Last Name">
              <span class="form_error d-none" id="add_last_name_error">Last Name Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Company</label>
              <input id="add_company" type="text" class="form-control form-control-sm" name="company" placeholder="Enter Company">
              <span class="form_error d-none" id="add_company_error">Company Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Country</label>
              <input id="add_country" type="text" class="form-control form-control-sm" name="country" placeholder="Enter Country" value="USA">
              <span class="form_error d-none" id="add_country_error">Country Can't Be Blank</span>
            </div>
            <div class="form-group col-md-12">
              <label>Adress Line 1</label>
              <input id="add_addressline1" type="text" class="form-control form-control-sm" name="addressline1" placeholder="Enter addressline1">
              <span class="form_error d-none" id="add_addressline1_error">Address Line 1 Can't Be Blank</span>
            </div>
            <div class="form-group col-md-12">
              <label>Suite number (optional)</label>
              <input id="add_addressline2" type="text" class="form-control form-control-sm" name="addressline2" placeholder="Enter Suite number (optional)">
            </div>
            <div class="form-group col-md-6">
              <label>City</label>
              <input id="add_city" type="text" class="form-control form-control-sm" name="city" placeholder="Enter city">
              <span class="form_error d-none" id="add_city_error">City Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>State/Province</label>
              <select id="add_state" class="form-control form-control-sm" name="state">
                <option value=""></option>
                <?php
                $tableedit  = "SELECT * FROM zones where zone_country_id in (223,38,37) ORDER BY zone_country_id desc, zone_name";
                $dt_view_res = db_query($tableedit, db_b2b());
                while ($row = array_shift($dt_view_res)) {
                ?>
                  <option <?
                          if ((trim($state) == trim($row["zone_code"])) ||  (trim($state) == trim($row["zone_name"])))
                            echo " selected ";
                          ?> value="<?php echo trim($row["zone_code"]) ?>">

                    <?php echo $row["zone_name"] ?>

                    (<?php echo $row["zone_code"] ?>)

                  </option>

                <?
                }
                ?>
              </select>
              <span class="form_error d-none" id="add_state_error">State/Province Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Zip</label>
              <input id="add_zip" type="text" class="form-control form-control-sm" name="zip" placeholder="Enter Zip">
              <span class="form_error d-none" id="add_zip_error">Zip Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Mobile No</label>
              <input id="add_mobile_no" type="text" class="form-control form-control-sm" name="mobile_no" placeholder="Enter Mobile No">
              <span class="form_error d-none" id="add_mobile_no_error">Mobile No Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Email </label>
              <input id="add_email" type="text" class="form-control form-control-sm" name="email" placeholder="Enter Mobile No">
              <span class="form_error d-none" id="add_email_error">Email Can't Be Blank</span>
            </div>
            <div class="form-group col-md-6">
              <label>Your Dock Hours</label>
              <input id="dock_hours" type="text" class="form-control form-control-sm" name="dock_hours" placeholder="Your Dock Hours (days open, open time - close time)">
              <span class="form_error d-none" id="add_dock_hours_error">Dock Hours Can't Be Blank</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="hidden" id="address_form_action" name="form_action" value="add_address">
          <button type="submit" id="add_address_btn" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
/* function saveActiveTab() {
    let activeTab = document.querySelector('.nav-pills .active').getAttribute('id');
    localStorage.setItem('activeTab', activeTab);
  }

  // Function to load the active tab from local storage
  function loadActiveTab() {
    let activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
      let tab = document.getElementById(activeTab);
      let tabContent = document.querySelector(tab.getAttribute('data-target'));

      // Remove active class from all tabs and tab contents
      document.querySelectorAll('.nav-pills .nav-link').forEach((btn) => btn.classList.remove('active'));
      document.querySelectorAll('.tab-pane').forEach((pane) => pane.classList.remove('show', 'active'));

      // Add active class to the saved tab and its content
      tab.classList.add('active');
      tabContent.classList.add('show', 'active');
    }
  }

  // Load the active tab on page load
  document.addEventListener('DOMContentLoaded', function() {
    loadActiveTab();

    // Save the active tab on tab shown event
    document.querySelectorAll('.nav-pills .nav-link').forEach((tab) => {
      tab.addEventListener('shown.bs.tab', saveActiveTab);
      tab.addEventListener('click', saveActiveTab);
    });
  });
*/
  $(document).ready(function() {
    $("#edit_profile_form").submit(function() {
      var user_name = $('#user_name').val();
      var user_email = $('#user_email').val();
      var flag = true;
      if (user_name == '') {
        $('#user_name_error').removeClass('d-none');
        flag = false;
      } else {
        $('#user_name_error').addClass('d-none');
      }
      if (user_email == '') {
        $('#user_email_error').removeClass('d-none');
        flag = false;
      } else {
        $('#user_email_error').addClass('d-none');
      }
      if (flag == true) {
        var all_data = new FormData(this);
        $.ajax({
          url: 'user_profile_action.php',
          data: all_data,
          method: "post",
          processData: false,
          contentType: false,
          success: function(response) {
            console.log(response);
            if (response == 1) {
              alert('User updated successfully');
              location.reload();
            } else {
              alert('Something went wrong, try again later');
            }
          }
        })
      }
      return false;
    });
    $("#change_password_form").submit(function() {
      var user_password = $("#user_password").val();
      var user_old_password = $("#user_old_password").val();
      var new_password = $("#new_password").val();
      var conf_password = $("#conf_password").val();
      var flag = true;
      if (user_password == '') {
        $("#user_password_error").removeClass('d-none');
        $("#user_password_error").text("Old Password Can't Be Blank");
        flag = false;
      } else {
        $("#user_password_error").addClass('d-none');
        $("#user_password_error").text("");
        if (user_password != user_old_password) {
          $("#user_password_error").removeClass('d-none');
          $("#user_password_error").text("Old Password Doesn't Match");
          flag = false;
        } else {
          $("#user_password_error").addClass('d-none');
          $('#user_password_error').text("");
        }

      }
      if (new_password == '') {
        $("#new_password_error").removeClass('d-none');
        $("#new_password_error").text("New Password Can't Be Blank");
        flag = false;
      } else {
        $("#new_password_error").addClass('d-none');
        $("#new_password_error").text("");
      }
      if (conf_password == '') {
        $("#conf_password_error").removeClass('d-none');
        $("#conf_password_error").text("Confirm Password Can't Be Blank");
        flag = false;
      } else {
        $("#conf_password_error").addClass('d-none');
        $("#conf_password_error").text("");

        if (new_password != conf_password) {
          $("#conf_password_error").removeClass('d-none');
          $("#conf_password_error").text("Password Doesn't Match");
          flag = false;
        } else {
          $("#conf_password_error").addClass('d-none');
          $('#conf_password_error').text("");
        }
      }

      if (flag == true) {

        $.ajax({
          url: 'user_profile_action.php',
          data: {
            user_id: $("#user_id").val(),
            new_password: new_password,
            form_action: 'change_password'
          },
          method: "post",
          async: false,
          success: function(response) {
            console.log(response);
            if (response == 1) {
              alert('Password updated successfully');
              location.reload();
            } else {
              alert('Something went wrong, try again later');
            }
          }
        })
      }
      return false;
    });
    $("#add_address_form").submit(function() {
      var flag = true;
      var add_first_name = $('#add_first_name').val();
      if (add_first_name == '') {
        $('#add_first_name_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_first_name_error').addClass('d-none');
      }

      var add_last_name = $('#add_last_name').val();
    if (add_last_name == '') {
      $('#add_last_name_error').removeClass('d-none');
      flag = false;
    } else {
      $('#add_last_name_error').addClass('d-none');
    }

    var add_dock_hours = $('#add_dock_hours').val();
    if (add_dock_hours == '') {
      $('#add_dock_hours_error').removeClass('d-none');
      flag = false;
    } else {
      $('#add_dock_hours_error').addClass('d-none');
    }

      var add_mobile_no = $('#add_mobile_no').val();
      if (add_mobile_no == '') {
        $('#add_mobile_no_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_mobile_no_error').addClass('d-none');
      }

      var add_email = $('#add_email').val();
      if (add_email == '') {
        $('#add_email_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_email_error').addClass('d-none');
      }

      var add_company = $('#add_company').val();
      if (add_company == '') {
        $('#add_company_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_company_error').addClass('d-none');
      }
      var add_country = $('#add_country').val();
      if (add_country == '') {
        $('#add_country_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_country_error').addClass('d-none');
      }

      var add_addressline1 = $('#add_addressline1').val();
      if (add_addressline1 == '') {
        $('#add_addressline1_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_addressline1_error').addClass('d-none');
      }
      var add_city = $('#add_city').val();
      if (add_city == '') {
        $('#add_city_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_city_error').addClass('d-none');
      }
      var add_state = $('#add_state').val();
      if (add_state == '') {
        $('#add_state_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_state_error').addClass('d-none');
      }
      var add_zip = $('#add_zip').val();
      if (add_zip == '') {
        $('#add_zip_error').removeClass('d-none');
        flag = false;
      } else {
        $('#add_zip_error').addClass('d-none');
      }
      if (flag == true) {
        var all_data = new FormData(this);
        $.ajax({
          url: 'user_profile_action.php',
          data: all_data,
          method: "post",
          processData: false,
          contentType: false,
          success: function(response) {
            console.log(response);
            if (response == 1) {
              alert('Address Added successfully');
              location.reload();
            } else {
              alert('Something went wrong, try again later');
            }
          }
        })
      }
      return false;
    });
    $("body").on('click', '.edit_address_btn', function() {
      var address_id = $(this).attr('address_id');
      $.ajax({
        url: 'user_profile_action.php',
        data: {
          address_id: address_id,
          form_action: 'get_edit_address'
        },
        method: "post",
        type: 'json',
        async: false,
        success: function(res) {
          var response = JSON.parse(res);
          $('#add_first_name').val(response.first_name);
          $('#add_last_name').val(response.last_name);
          $('#add_email').val(response.email);
          $('#add_dock_hours').val(response.dock_hours);
          $('#add_mobile_no').val(response.mobile_no);
          $("#add_company").val(response.company);
          $("#add_country").val(response.country);
          $("#add_addressline1").val(response.addressline1);
          $("#add_addressline2").val(response.addressline2);
          $("#add_city").val(response.city);
          $("#add_state").val(response.state);
          $("#add_zip").val(response.zip);
          $("#add_address_btn").text('Update Address');
          $("#add_address_form").append('<input type="hidden" name="address_id" value="' + address_id + '">');
          $("#address_form_action").val("update_address");
          $("#addAddressModalLabel").html("Edit Address");
          $("#addAddressModal").modal('show');
        }
      })
    });
    $('.mark_default_add').click(function() {
      var address_id = $(this).attr('address_id');
      $.ajax({
        url: 'user_profile_action.php',
        data: {
          address_id: address_id,
          form_action: 'mark_default'
        },
        method: "post",
        async: false,
        success: function(res) {
          if (res == 1) {
            alert('Address marked as default');
            location.reload();
          } else {
            alert('Something went wrong, try again later');
          }
        }
      })
    });

    $(".delete_address_btn").click(function(){
      var address_id = $(this).attr('address_id');
      $.ajax({
        url: 'user_profile_action.php',
        data: {
          address_id: address_id,
          form_action: 'delete_address'
        },
        method: "post",
        async: false,
        success: function(res) {
          if (res == 1) {
            alert('Address deleted successfully');
            location.reload();
          } else {
            alert('Something went wrong, try again later');
          }
        }
      })
    })

  });
</script>
<?php require_once("boomerange_common_footer.php"); ?>