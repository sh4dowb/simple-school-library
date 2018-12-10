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
              <th scope="col">Time left</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = mysqli_query($con, "SELECT first.*, fullname, schoolnumber, borrowid, borrowed_date, due_time FROM (SELECT borrow.id as borrowid, book.id as bookid, title, barcode, author, publisher, year, borrowed_date, memberid, due_time FROM books book INNER JOIN borrow_current borrow ON borrow.bookid = book.id ORDER BY book.id) first LEFT JOIN members second ON first.memberid = second.id");
            while($data = mysqli_fetch_array($sql)){
                $secondsleft = ($data['borrowed_date'] + $data['due_time']) - time();
                ?>
                <tr>
                  <th scope="row"><?=$data['borrowid']?></th>
                  <td><?=$data['title']?></td>
                  <td><?=$data['barcode']?></td>
                  <td><?=$data['fullname'].' ('.$data['schoolnumber'].')'?></td>
                  <td><?=date("d-m-Y", $data['borrowed_date'])?></td>
                  <td <?=($secondsleft < 0 ? "style=\"background-color:red;color:white;\" " : "")?>data-order="<?=$secondsleft?>"><?=timeleft($secondsleft)?></td>
                </tr>
                <?php
            }
            ?>
          </tbody>
        </table>
    </main>
<?php
$script = '$("table").DataTable({
    order: [[5, "asc"]],
    iDisplayLength: 50,
    });';
require_once("footer.php");
