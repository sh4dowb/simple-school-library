<?php
require_once("database.php");
require_once("header.php");
if(strlen($_POST['title']) > 2 && strlen($_POST['author']) > 2 && strlen($_POST['barcode']) > 0){
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $barcode = mysqli_real_escape_string($con, $_POST['barcode']);
    $author = mysqli_real_escape_string($con, $_POST['author']);
    $publisher = mysqli_real_escape_string($con, $_POST['publisher']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']);
    $sql = mysqli_query($con, "SELECT id, title FROM books WHERE barcode='$barcode'");
    if(mysqli_num_rows($sql)){
        $data = mysqli_fetch_array($sql);
        $alert = '<div class="alert alert-danger">This barcode already exists ('.$data[1].')</div>';
    } else {
        if(mysqli_query($con, "INSERT INTO books VALUES (NULL,'$title', '$author', '$publisher', '$year', '$barcode', '$comments')"))
          $alert = '<div class="alert alert-success">This book already exists</div>';
        else
          $alert = '<div class="alert alert-danger">Database error</div>';
    }
}
if(isset($_GET['del'])){
    $del = mysqli_real_escape_string($con, $_GET['del']);
    if(mysqli_query($con, "DELETE FROM books WHERE id='$del'")){
        if(mysqli_affected_rows($con))
            $alert = '<div class="alert alert-success">Book successfully deleted</div>';
        else
            $alert = '<div class="alert alert-danger">Book not found</div>';
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
              <th scope="col">Barcode</th>
              <th scope="col">Title</th>
              <th scope="col">Author</th>
              <th scope="col">Publisher</th>
              <th scope="col">Year</th>
              <th scope="col">Comments</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = mysqli_query($con, "SELECT first.*, fullname, schoolnumber, borrowid FROM (SELECT borrow.id as borrowid, book.id as bookid, title, barcode, author, publisher, year, borrowed_date, memberid, due_time FROM books book LEFT JOIN borrow_current borrow ON borrow.bookid = book.id ORDER BY book.id) first LEFT JOIN members second ON first.memberid = second.id ORDER BY barcode DESC");
            while($data = mysqli_fetch_array($sql)){
                ?>
                <tr>
                  <th scope="row"><?=$data['barcode']?></th>
                  <td><?=$data['title']?></td>
                  <td><?=$data['author']?></td>
                  <td><?=$data['publisher']?></td>
                  <td><?=$data['year']?></td>
                  <td><?=(strlen($data['comments']) > 5 ? '<a href="#" onclick="event.preventDefault();alert("'.$data['comments'].'");">show</a>' : $data['comments'])?></td>
                  <td><?=($data['borrowed_date'] === NULL ? '<span class="text-success">available</span>' : '<a class="text-danger" href="#" onclick="event.preventDefault();alert(\'Borrow ID: #'.$data['borrowid'].'\nBorrower: '.$data['fullname'].' ('.$data['schoolnumber'].')\n\nBorrow date: '.date("d-m-Y H:i:s", $data['borrowed_date']).'\nTime left: '.timeleft(($data['borrowed_date'] + $data['due_time']) - time()).'\n\')">taken (..)</a>')?></td>
                  <td><a href="?del=<?=$data['id']?>" class="btn btn-danger">Delete</a></td>
                </tr>
                <?php
            }
            $lastbarcodesql = mysqli_query($con, "select barcode from books order by barcode - 0 desc limit 1;");
            $lastbarcode = mysqli_fetch_array($lastbarcodesql)[0] + 1;
            ?>
          </tbody>
        </table>
        <div class="col-md-6 offset-md-3">
              <form method="post" action="?">
                <div class="form-group">
                    <label for="title">Title<span class="text-danger">*</span></label>
                    <input type="text" required class="form-control" id="title" name="title" placeholder="Title">
                </div>
                <div class="form-group">
                    <label for="barcode">Barcode<span class="text-danger">*</span></label>
                    <input type="text" required class="form-control" id="barcode" name="barcode" placeholder="Barcode" value="<?=$lastbarcode?>">
                </div>
                <div class="form-group">
                    <label for="author">Author<span class="text-danger">*</span></label>
                    <input type="text" required class="form-control" id="author" name="author" placeholder="Author">
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" class="form-control" id="publisher" name="publisher" placeholder="Publisher">
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="text" class="form-control" id="year" name="year" placeholder="Year">
                </div>
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea type="text" class="form-control" id="comments" name="comments" placeholder="Comments"></textarea>
                </div>
              <button type="submit" class="btn btn-primary">Add</button>
             </form>
         </div>
    </main>
<?php
$script = '$("table").DataTable({
    order: [[0, "desc"]],
    iDisplayLength: 50,
    });';
require_once("footer.php");
