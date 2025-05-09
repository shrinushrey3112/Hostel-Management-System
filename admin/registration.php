<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
if ($_POST['submit']) {
    $roomno = $_POST['room'];
    $seater = $_POST['seater'];
    $feespm = $_POST['fpm'];
    $foodstatus = $_POST['foodstatus'];
    $stayfrom = $_POST['stayf'];
    $duration = $_POST['duration'];
    $course = $_POST['course'];
    $regno = $_POST['regno'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $contactno = $_POST['contact'];
    $emailid = $_POST['email'];
    $emcntno = $_POST['econtact'];
    $gurname = $_POST['gname'];
    $gurrelation = $_POST['grelation'];
    $gurcntno = $_POST['gcontact'];
    $gur_email = $_POST['gemail'];
    $caddress = $_POST['address'];
    $ccountry = $_POST['ccountry'];
    $ccity = $_POST['city'];
    $cstate = $_POST['cstate'];
    $cpincode = $_POST['pincode'];
    $paddress = $_POST['paddress'];
    $pcountry = $_POST['pcountry'];
    $pcity = $_POST['pcity'];
    $pstate = $_POST['pstate'];
    $ppincode = $_POST['ppincode'];

    $checkQuery = "SELECT id FROM registration WHERE regno = ?";
    $stmtCheck = $mysqli->prepare($checkQuery);
    $stmtCheck->bind_param('s', $regno);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo "<script>alert('Error: This student is already registered for a room!');</script>";
    } else {
        $query = "INSERT INTO registration(roomno, seater, feespm, foodstatus, stayfrom, duration, course, regno, firstName, middleName, lastName, gender, contactno, emailid, egycontactno, guardianName, guardianRelation, guardianContactno, guardianEmail, corresAddress, corresCountry, corresCity, corresState, corresPincode, pmntAddress, pmntCountry, pmntCity, pmnatetState, pmntPincode) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('iiiisisssssssssssssssssissssi', $roomno, $seater, $feespm, $foodstatus, $stayfrom, $duration, $course, $regno, $fname, $mname, $lname, $gender, $contactno, $emailid, $emcntno, $gurname, $gurrelation, $gurcntno, $gur_email, $caddress, $ccountry, $ccity, $cstate, $cpincode, $paddress, $pcountry, $pcity, $pstate, $ppincode);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Student successfully registered');</script>";
    }
    $stmtCheck->close();
}

?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Student Hostel Registration</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
    <script>
        function getSeater(val) {
            $.ajax({
                type: "POST",
                url: "get_seater.php",
                data: 'roomid=' + val,
                success: function (data) {
                    $('#seater').val(data);
                }
            });

            $.ajax({
                type: "POST",
                url: "get_seater.php",
                data: 'rid=' + val,
                success: function (data) {
                    $('#fpm').val(data);
                    calculateTotalAmount();
                }
            });
        }


        function calculateTotalAmount() {
            var feesPerMonth = parseFloat($('#fpm').val()) || 0;
            var foodStatus = $('input[name="foodstatus"]:checked').val();
            var duration = parseInt($('#duration').val()) || 0;

            if (foodStatus == 1) {
                feesPerMonth += 2000;
            }

            var totalAmount = feesPerMonth * duration;
            $('#ta').val(totalAmount);
        }

        $(document).ready(function () {
            $('#duration').change(calculateTotalAmount);
            $('input[name="foodstatus"]').change(calculateTotalAmount);
        });


        function fetchUserDetails(regNo) {
            if (regNo) {
                $.ajax({
                    type: "POST",
                    url: "get_user_details.php",
                    data: { "regNo": regNo },
                    success: function (response) {
                        console.log("Server response:", response);
                        try {
                            const data = typeof response === "string" ? JSON.parse(response) : response; // Parse if necessary

                            if (!data.error) {
                                $('#fname').val(data.firstName);
                                $('#mname').val(data.middleName);
                                $('#lname').val(data.lastName);
                                $('#gender').val(data.gender.toLowerCase());
                                $('#contact').val(data.contactNo);
                                $('#email').val(data.email);
                            } else {
                                alert(data.error);
                            }
                        } catch (e) {
                            console.error("Error parsing response:", e);
                            alert("An error occurred while retrieving data.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX error:", status, error);
                        alert("Failed to communicate with the server.");
                    }
                });
            } else {
                $('#fname, #mname, #lname, #gender, #contact, #email').val('');
            }
        }

    </script>

</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">

                        <h2 class="page-title" style="margin-top: 0;">Registration </h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Fill all Info</div>
                                    <div class="panel-body">
                                        <form method="post" action="" class="form-horizontal">


                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">
                                                    <h4 style="color: green" align="left">Room Related info </h4>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Room no. </label>
                                                <div class="col-sm-8">
                                                    <select name="room" id="room" class="form-control"
                                                        onChange="getSeater(this.value);" onBlur="checkAvailability()"
                                                        required>
                                                        <option value="">Select Room</option>
                                                        <?php $query = "SELECT * FROM rooms";
                                                        $stmt2 = $mysqli->prepare($query);
                                                        $stmt2->execute();
                                                        $res = $stmt2->get_result();
                                                        while ($row = $res->fetch_object()) {
                                                            ?>
                                                            <option value="<?php echo $row->room_no; ?>">
                                                                <?php echo $row->room_no; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span id="room-availability-status" style="font-size:12px;"></span>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Seater</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="seater" id="seater" class="form-control"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Fees Per Month</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="fpm" id="fpm" class="form-control"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Food Status</label>
                                                <div class="col-sm-8">
                                                    <input type="radio" value="0" name="foodstatus" checked="checked">
                                                    Without Food
                                                    <input type="radio" value="1" name="foodstatus"> With Food(Rs
                                                    2000.00 Per Month Extra)
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Stay From</label>
                                                <div class="col-sm-8">
                                                    <input type="date" name="stayf" id="stayf" class="form-control"
                                                        required>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Duration</label>
                                                <div class="col-sm-8">
                                                    <select name="duration" id="duration" class="form-control">
                                                        <option value="">Select Duration in Month</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Total Amount</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="ta" id="ta" class="result form-control"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">
                                                    <h4 style="color: green" align="left">Personal info </h4>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Course </label>
                                                <div class="col-sm-8">
                                                    <select name="course" id="course" class="form-control" required>
                                                        <option value="">Select Course</option>
                                                        <?php $query = "SELECT * FROM courses";
                                                        $stmt2 = $mysqli->prepare($query);
                                                        $stmt2->execute();
                                                        $res = $stmt2->get_result();
                                                        while ($row = $res->fetch_object()) {
                                                            ?>
                                                            <option value="<?php echo $row->course_fn; ?>">
                                                                <?php echo $row->course_fn; ?>&nbsp;&nbsp;(<?php echo $row->course_sn; ?>)
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Registration No :</label>
                                                <div class="col-sm-8">
                                                    <select name="regno" id="regno" class="form-control" required
                                                        onchange="fetchUserDetails(this.value)">
                                                        <option value="">Select Registration No</option>
                                                        <?php
                                                        $query = "SELECT regNo FROM userregistration";
                                                        $stmt = $mysqli->prepare($query);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        while ($row = $result->fetch_object()) {
                                                            ?>
                                                            <option value="<?php echo $row->regNo; ?>">
                                                                <?php echo $row->regNo; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">First Name :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="fname" id="fname" class="form-control"
                                                        required="required" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Middle Name :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="mname" id="mname" class="form-control"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Last Name :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="lname" id="lname" class="form-control"
                                                        required="required" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Gender :</label>
                                                <div class="col-sm-8">
                                                    <select name="gender" id="gender" class="form-control"
                                                        required="required" readonly>
                                                        <option value="">Select Gender</option>
                                                        <option value="male">Male</option>
                                                        <option value="female">Female</option>
                                                        <option value="others">Others</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Contact No : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="contact" id="contact" class="form-control"
                                                        required="required" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Email id :</label>
                                                <div class="col-sm-8">
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        required="required" readonly>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Emergency Contact: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="econtact" id="econtact"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^0-9+ ]/g, '')"
                                                        maxlength="15" step="1">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Guardian Name : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="gname" id="gname" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Guardian Relation : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="grelation" id="grelation"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Guardian Contact no : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="gcontact" id="gcontact"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^0-9+ ]/g, '')"
                                                        maxlength="15" step="1">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Guardian Email : </label>
                                                <div class="col-sm-8">
                                                    <input type="email" name="gemail" id="gemail" class="form-control"
                                                        required="required">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">
                                                    <h4 style="color: green" align="left">Correspondence Address </h4>
                                                </label>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Address : </label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" name="address" id="address" class="form-control"
                                                        required="required"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Country: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="ccountry" id="ccountry"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">State: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="cstate" id="cstate" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">City : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="city" id="city" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Pincode : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pincode" id="pincode" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">
                                                    <h4 style="color: green" align="left">Permanent Address </h4>
                                                </label>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Permanent Address same as
                                                    Correspondence Address:</label>
                                                <div class="col-sm-4" style="margin-top: 15px; margin-left: -25px;">
                                                    <input type="checkbox" name="adcheck" value="1" />
                                                </div>
                                            </div>



                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Address : </label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" name="paddress" id="paddress"
                                                        class="form-control" required="required"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Country: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pcountry" id="pcountry"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '');">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">State: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pstate" id="pstate" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">City : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pcity" id="pcity" class="form-control"
                                                        required="required"
                                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Pincode : </label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="ppincode" id="ppincode"
                                                        class="form-control" required="required"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                </div>
                                            </div>


                                            <div class="col-sm-6 col-sm-offset-4">
                                                <input type="submit" name="submit" Value="Register"
                                                    class="btn btn-primary">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>

<script type="text/javascript">
    $(document).ready(function () {
        $('input[type="checkbox"]').click(function () {
            if ($(this).prop("checked") == true) {
                $('#paddress').val($('#address').val());
                $('#pcountry').val($('#ccountry').val());
                $('#pcity').val($('#city').val());
                $('#pstate').val($('#cstate').val());
                $('#ppincode').val($('#pincode').val());
            }
        });
    });
</script>

<script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('stayf').setAttribute('min', today);
</script>

<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'roomno=' + $("#room").val(),
            type: "POST",
            success: function (data) {
                $("#room-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function () { }
        });
    }
</script>

</html>