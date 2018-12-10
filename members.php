<?php
require_once("database.php");
require_once("header.php");
if(strlen($_POST['fullname']) > 2 && strlen($_POST['schoolnumber']) > 1){
    $fullname = mysqli_real_escape_string($con, $_POST['fullname']);
    $schoolnumber = mysqli_real_escape_string($con, $_POST['schoolnumber']);
    $sql = mysqli_query($con, "SELECT id, fullname FROM members WHERE schoolnumber='$schoolnumber'");
    if(mysqli_num_rows($sql)){
        $data = mysqli_fetch_array($sql);
        $alert = '<div class="alert alert-danger">This school number already exists (#'.$data[0].', '.$data[1].')</div>';
    } else {
        if(mysqli_query($con, "INSERT INTO members VALUES (NULL,'$fullname', '$schoolnumber')"))
          $alert = '<div class="alert alert-success">Student succesfully added</div>';
        else
          $alert = '<div class="alert alert-danger">Database error</div>';
    }
}
if(isset($_GET['del'])){
    $del = mysqli_real_escape_string($con, $_GET['del']);
    if(mysqli_query($con, "DELETE FROM members WHERE id='$del'")){
        if(mysqli_affected_rows($con))
            $alert = '<div class="alert alert-success">Student deleted</div>';
        else
            $alert = '<div class="alert alert-danger">Student not found</div>';
    } else {
        $alert = '<div class="alert alert-danger">Database error</div>';
    }
}
?>
    <!-- Begin page content -->
    <main role="main" class="container" style="padding-top:80px;">
        <?=$alert?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Full Name</th>
              <th scope="col">School Number</th>
              <th scope="col">Total Borrowed</th>
              <th scope="col">Total Late Returns</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = mysqli_query($con, "SELECT * FROM members");
            while($data = mysqli_fetch_array($sql)){
                $late = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(id) FROM borrow_history WHERE memberid='$data[id]' AND return_date > borrowed_date + due_time"))[0];
                $historytotal = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(id) FROM borrow_history WHERE memberid='$data[id]'"))[0];
                $currenttotal = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(id) FROM borrow_current WHERE memberid='$data[id]'"))[0];
                ?>
                <tr>
                  <th scope="row"><?=$data['id']?></th>
                  <td><?=$data['fullname']?></td>
                  <td><?=$data['schoolnumber']?></td>
                  <td><?=$currenttotal + $historytotal?></td>
                  <td><?=$late?></td>
                  <td><a href="?del=<?=$data['id']?>" class="btn btn-danger">Delete</a></td>
                </tr>
                <?php
            }
            ?>
          </tbody>
        </table>
        <div class="col-md-6 offset-md-3">
              <form method="post" action="?">
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" required class="form-control" id="fullname" name="fullname" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label for="schoolnumber">School Number</label>
                    <input type="text" required class="form-control" id="schoolnumber" name="schoolnumber" placeholder="School Number">
                </div>
              <button type="submit" class="btn btn-primary">Add</button>
             </form>
         </div>
    </main>
<?php
$script = 'var table = $("table").DataTable({
    order: [[0, "desc"]],
    iDisplayLength: 50,
    });
';
require_once("footer.php");

