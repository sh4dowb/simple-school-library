<?php
require_once("database.php");
require_once("header.php");
?>
    <!-- Begin page content -->
    <main role="main" class="container" style="padding-top:80px;">
        <?=$alert?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Barcode</th>
              <th scope="col">Borrower</th>
              <th scope="col">Borrow date</th>
              <th scope="col">Return date</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = mysqli_query($con, "SELECT first.*, fullname, schoolnumber, borrowid, borrowed_date, due_time, return_date FROM (SELECT borrow.old_id as borrowid, book.id as bookid, title, barcode, author, publisher, year, borrowed_date, memberid, due_time, return_date FROM books book INNER JOIN borrow_history borrow ON borrow.bookid = book.id ORDER BY book.id) first LEFT JOIN members second ON first.memberid = second.id");
            while($data = mysqli_fetch_array($sql)){
                $secondsleft = ($data['borrowed_date'] + $data['due_time']) - $data['return_date'];
                ?>
                <tr>
                  <th scope="row"><?=$data['borrowid']?></th>
                  <td><?=$data['title']?></td>
                  <td><?=$data['barcode']?></td>
                  <td><?=$data['fullname'].' ('.$data['schoolnumber'].')'?></td>
                  <td><?=date("d-m-Y", $data['borrowed_date'])?></td>
                  <td><?=str_replace(array("left","late"),array("before","later"),timeleft($secondsleft))?></td>
                </tr>
                <?php
            }
            ?>
          </tbody>
        </table>
    </main>
<?php
$script = '$("table").DataTable({
    order: [[0, "desc"]],
    iDisplayLength: 50,
    });';
require_once("footer.php");
