<?php
require_once("database.php");
require_once("header.php");
?>
    <!-- Begin page content -->
    <main role="main" class="container" style="padding-top:80px;">
         <div class="col-md-12">
            <h1>Students who borrowed the most</h1>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Student</th>
                  <th scope="col">This Week</th>
                  <th scope="col">This Month</th>
                  <th scope="col">Last 3 Months</th>
                  <th scope="col">All</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = mysqli_query($con, "SELECT
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 7) THEN 1 END) as lastweek,
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 30) THEN 1 END) as lastmonth,
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 90) THEN 1 END) as last3months,
COUNT(*) as alltime, memberid, m.fullname, m.schoolnumber
 FROM ( SELECT hh.id, hh.memberid, hh.borrowed_date FROM borrow_history hh LEFT JOIN borrow_current cc ON hh.memberid = cc.memberid
        UNION
        SELECT cc.id, cc.memberid, cc.borrowed_date FROM borrow_history hh RIGHT JOIN borrow_current cc ON hh.memberid = cc.memberid) b INNER JOIN members m ON b.memberid = m.id GROUP BY b.memberid");
                while($data = mysqli_fetch_array($sql)){
                    ?>
                    <tr>
                      <td><?=$data['fullname']?> (<?=$data['schoolnumber']?>)</td>
                      <td><?=$data['lastweek']?></td>
                      <td><?=$data['lastmonth']?></td>
                      <td><?=$data['last3months']?></td>
                      <td><?=$data['alltime']?></td>
                    </tr>
                    <?php
                }
                ?>
              </tbody>
            </table>
        </div>
        <hr>
         <div class="col-md-12">
            <h1>Most Borrowed Books</h1>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">Book</th>
                  <th scope="col">This Week</th>
                  <th scope="col">This Month</th>
                  <th scope="col">Last 3 Months</th>
                  <th scope="col">All</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = mysqli_query($con, "SELECT
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 7) THEN 1 END) as lastweek,
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 30) THEN 1 END) as lastmonth,
COUNT(CASE WHEN borrowed_date > UNIX_TIMESTAMP() - (86400 * 90) THEN 1 END) as last3months,
COUNT(h.id) as alltime, b.title
 FROM ( SELECT hh.id, hh.bookid, hh.borrowed_date FROM borrow_history hh LEFT JOIN borrow_current cc ON hh.bookid = cc.bookid
        UNION
        SELECT cc.id, cc.bookid, cc.borrowed_date FROM borrow_history hh RIGHT JOIN borrow_current cc ON hh.bookid = cc.bookid) h
RIGHT JOIN books b ON h.bookid = b.id GROUP BY b.title");
                while($data = mysqli_fetch_array($sql)){
                    ?>
                    <tr>
                      <td><?=$data['title']?></td>
                      <td><?=$data['lastweek']?></td>
                      <td><?=$data['lastmonth']?></td>
                      <td><?=$data['last3months']?></td>
                      <td><?=$data['alltime']?></td>
                    </tr>
                    <?php
                }
                ?>
              </tbody>
            </table>
        </div>
    </main>
<?php
$script = '$("table").DataTable({
    order: [[4, "desc"]],
    iDisplayLength: 10,
    });';
require_once("footer.php");
