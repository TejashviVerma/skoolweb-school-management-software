<?php include_once("header.php");?>
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
<div class="container-fluid">
<div class="row bg-title">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
<h4 class="page-title">GRADE</h4> </div>
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
<img src="../../logo/<?php echo $logo;?>" alt="user-img" width="36" class="img-circle"> <?php echo $school_name;?>
</a>
<ol class="breadcrumb">
<li><a href="#">SkoolWeb</a></li>
<li class="active">GRADE</li>
</ol>
</div>
<!-- /.col-lg-12 -->
</div>
<!--MODAL BEGINS-->
<!--MODAL ENDS-->
<!-- /row -->
<div class="col-lg-12 col-sm-12 col-xs-12">
<div id="status" align="center">
</div>
</div>
<div class="row">
<div class="col-sm-12">
<div class="white-box">
<ul id="myTab1" class="nav nav-tabs">
<li class="active"><a href="#home1" data-toggle="tab">Manage</a></li>
<li><a href="#profile1" data-toggle="tab">Add Grade</a></li>
</ul>
<div id="myTabContent1" class="tab-content">
<div class="tab-pane fade in active" id="home1">
<div class="table-responsive">
<table class="table">
<thead>
<tr>
<th>#</th>
<th>Grade</th>
<th>Alphabet</th>
<th>Range</th>
<th>Class</th>
<th>Options</th>                                            
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM grade_result WHERE schoolidentity = '$school_name' ORDER BY id ASC";	
$query = mysqli_query($conn, $sql);
$text = "";
if(mysqli_num_rows($query) > 0){
$text = "";
$count = 1;
while($row = mysqli_fetch_array($query)){
?>
<tr>
<td><?php echo $count++ ;?></td>
<td><?php echo $row['grade_name'];?></td>
<td><?php echo $row['grade_alphabet'];?></td>
<td><?php echo $row['range_min'];?> - <?php echo $row['range_max'];?></td>
<td><?php echo $row['class'];?></td>
<script type="text/javascript">
function remove(id)
{
if(confirm('are you sure you want to delete record ?'))
{
window.location='gradedelete.php?remove_id='+id;
}
}
</script>	
<td><a href="gradeedit.php?id=<?php echo urlencode($row['id']);?>" class="btn btn-warning">Edit</a>
&nbsp;<a href="javascript:remove(<?php echo $row['id'];?>)" class="btn btn-danger">Delete</a></td>
</tr>
<?php
}
} else {
$text = "no record available,please go to add grade to add grades.";
echo '<td style="color:red;"><img src="../../img/images2.jpg" />'.$text.'</td>';
}
?>
</tbody>
</table>
</div>
</div>
<div class="tab-pane fade" id="profile1">
<form method="post" action="">
<?php
// to input the grades into the database;
$message = "";
if(isset($_POST['add'])){

$grade_name = trim(mysqli_real_escape_string($conn,$_POST['grade_name']));
$grade_alphabet = trim(mysqli_real_escape_string($conn,$_POST['grade_alphabet']));
$range_min = preg_replace('#[^0-9]#','',$_POST['range_min']);
$range_max = preg_replace('#[^0-9]#','',$_POST['range_max']);
$class = trim(mysqli_real_escape_string($conn,$_POST['classes']));
/*-----------------------------------------------------------------------------------
----------------------validations-----------------------------------------------------*/
$sql = "SELECT * FROM grade_result WHERE schoolidentity = '$school_name' AND class = '$class'";
$query = mysqli_query($conn,$sql);
$count = mysqli_fetch_row($query);

$sql = "SELECT * FROM grade_result WHERE schoolidentity = '$school_name' AND grade_alphabet = '$grade_alphabet'";
$query = mysqli_query($conn,$sql);
$count2 = mysqli_fetch_row($query);

if($count!=0 && $count2!=0){
$message = "oops! grade record already exists!!";
echo '<p style="color:red;">'.$message.'</p>';
} else {

$sql = "INSERT INTO grade_result(grade_name,grade_alphabet,range_min,range_max,class,datemade,schoolidentity)
VALUES('$grade_name','$grade_alphabet','$range_min','$range_max','$class',now(),'$school_name')";
$query = mysqli_query($conn,$sql);
$message = "grade added successfully";
echo '<p style="color:red;">'.$message.'</p>';
?>
<script>
alert(`<?=$message?>`);
</script>
<?php
header("location:grade.php");
}		 
} else {
$message = "please fill in the grade details below.......";
echo '<p style="color:red;">'.$message.'</p>';
}
?>
<input type="text" name="grade_name" placeholder="Grade name e.g Distinction" class="form-control">
<input type="text" name="grade_alphabet" placeholder="Grade alphabet e.g A,B2" class="form-control">
<label>Range: </label>        
<input type="number" name="range_min" placeholder="Minimum Range">
<input type="number" name="range_max" placeholder="Maximum Range">
<select class="form-control" name="classes"> <!-- HERE WE GET THE CLASSES FROM THE DATABASE ENTERRED BY THE ADMIN -->
<option disabled selected>Choose the class</option>
<?php
// getting the classes from database
$sql = "SELECT class FROM class WHERE schoolidentity = '$school_name' ORDER BY ID DESC";
$query = mysqli_query($conn,$sql);
$info = "";
if(mysqli_num_rows($query) > 0){
$info = "";
while($row = mysqli_fetch_array($query)){
?>
<option value="<?php echo $row['class'];?>"><?php echo $row['class'];?></option>
<?php		
}	
} else {
$info = "oops!! no record yet added from the class section,go there to add one";
?>
<option style='color:red;' disabled><?php echo $info;?></option>
<?php
}
?>
</select>  
<button class="btn btn-default" name="add">Add<i class="fa fa-plus"></i></button>
</form>		
</div>
</div>
</div>
</div>
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->

<?php include_once("footer.php"); 
ob_end_flush();
?>