<?php
require_once("database.php");
require_once("header.php");
if(!isset($_POST['schoolnumber']) && strlen($_POST['barcode']) > 0){
    $barcode = mysqli_real_escape_string($con, $_POST['barcode']);
    $borrowsql = mysqli_query($con, "SELECT c.*, b.title as title FROM books b INNER JOIN borrow_current c ON c.bookid=b.id WHERE b.barcode='$barcode'");
    if(!mysqli_num_rows($borrowsql)){
        $alert = '<div class="alert alert-danger">Book isn\'t borrowed or not found</div>';
    } else {
        $borrowdata = mysqli_fetch_array($borrowsql);
        if(mysqli_query($con, "INSERT INTO borrow_history VALUES (NULL, '$borrowdata[memberid]', '$borrowdata[bookid]','$borrowdata[borrowed_date]','$borrowdata[due_time]','".time()."', '$borrowdata[id]')") && mysqli_query($con, "DELETE FROM borrow_current WHERE id='$borrowdata[id]'"))
            $alert = '<div class="alert alert-success">'.$borrowdata['title'].' is returned</div>';
        else
            $alert = '<div class="alert alert-danger">Database error</div>';
    }
}
if(strlen($_POST['schoolnumber']) > 1 && strlen($_POST['barcode']) > 0){
    $barcode = mysqli_real_escape_string($con, $_POST['barcode']);
    $schoolnumber = mysqli_real_escape_string($con, $_POST['schoolnumber']);
    $studentsql = mysqli_query($con, "SELECT id FROM members WHERE schoolnumber='$schoolnumber'");
    if(!mysqli_num_rows($studentsql)){
        $alert = '<div class="alert alert-danger">Student not found</div>';
    } else {
        $studentdata = mysqli_fetch_array($studentsql);
        $sql = mysqli_query($con, "SELECT id, title FROM books WHERE barcode='$barcode'");
        if(mysqli_num_rows($sql)){
            $data = mysqli_fetch_array($sql);
            $borrowsql = mysqli_query($con, "SELECT *, b.id as borrowid FROM borrow_current b INNER JOIN members m ON m.id = b.memberid WHERE b.bookid='".$data['id']."'");
            if(mysqli_num_rows($borrowsql)){
                $borrowdata = mysqli_fetch_array($borrowsql);
                $alert = '<div class="alert alert-danger">This book is already taken (#'.$borrowdata['borrowid'].', '.$borrowdata['fullname'].')</div>';
            } else {
                $duetime = $_POST['duetime'] * 86400;
                $ok = false;
                if($_POST['force'] != "yes"){
                    $userborrowsql = mysqli_query($con, "SELECT * FROM (SELECT b.id as borrowid, b.bookid as bookid FROM borrow_current b INNER JOIN members m ON m.id = b.memberid WHERE b.memberid='".$studentdata['id']."') first INNER JOIN books b ON b.id = first.bookid");
                    if(mysqli_num_rows($userborrowsql)){
                        $userborrowdata = mysqli_fetch_array($userborrowsql);
                        $alert = '<div class="alert alert-danger">This student has already a book on him/her (#'.$userborrowdata['borrowid'].', '.$data['title'].')</div>';
                    } else
                        $ok = true;
                } else
                    $ok = true;

                if($ok){
                    var_dump($studentdata);
                    if(mysqli_query($con, "INSERT INTO borrow_current VALUES (NULL, '$studentdata[id]', '$data[id]', '".time()."', '$duetime')"))
                        $alert = '<div class="alert alert-success">Book borrowed, ID: #'.mysqli_insert_id($con).'</div>';
                    else
                        $alert = '<div class="alert alert-danger">Database error</div>';
                }
            }
        } else {
            $alert = '<div class="alert alert-danger">Book not found</div>';
        }
    }
}
?>
    <!-- Begin page content -->
    <main role="main" class="container">
        <?=$alert?>
        <div class="row">
            <div class="col-md-6">
              <h1 class="mt-5">Borrow Book</h1><hr>
              <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" required class="form-control" name="barcode" placeholder="Barcode">
                    </div>
                    <div class="col-md-4 text-center">
                        <input type="text" required class="form-control" name="schoolnumber" placeholder="Student number">
                        <small style="font-size:75%">(school number)</small>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Borrow Book</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                          <input type="text" class="form-control" name="duetime" value="15">
                          <div class="input-group-append">
                            <span class="input-group-text">days</span>
                          </div>
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-8">
                        <input type="checkbox" id="force" name="force" value="yes"> <label for="force">Borrow even if student has already a book</label>
                    </div>
                </div>
               </form>
             </div>
            <div class="col-md-6">
              <h1 class="mt-5">Return Book</h1><hr>
              <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" required class="form-control" name="barcode" placeholder="Barcode">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Return</button>
                    </div>
                </div>
               </form>
            </div>
        </div>
    </main>
<?php
require_once("footer.php");

