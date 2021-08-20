<?php
include __DIR__ . '/partials/init.php';
$title = '庫存資料';
$activeLi = 'li';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;


$sqlImg = "SELECT * FROM `images`";
$rowsImg = $pdo->query($sqlImg)->fetchAll();

$sqlCate = "SELECT * FROM `categories`";
$rowsCate = $pdo->query($sqlCate)->fetchAll();

$sqlStock = "SELECT * FROM `stock` WHERE `products_id`= $sid";
$rowsStock = $pdo->query($sqlStock)->fetchAll();


$sql ="SELECT * FROM (`products` LEFT JOIN `stock` ON `stock`.`products_id` = `sid`) WHERE `sid` = $sid";
$rows = $pdo->query($sql)->fetchAll();

?>

<?php include __DIR__ . '/partials/html-head.php';?>
<style>
    .addData{
       /* width: 300px; */
       /* height: 100px; */
       color: black;
       border: 1px solid #ccc;
       color: #ccc;
       margin-bottom: 30px;
       justify-content: center;
    }

    .addData a{
        width: 100%;
        height: 100%;
        color: #333;
        justify-content: center !important;
        align-items: center;
    }
</style>
<?php include __DIR__ . '/partials/navbar.php';?>

<div class="container mt-3">
  <div class="row">
        <div class="col-3">
            <div class="addData d-flex" >
                <a class="nav-link d-flex justify-content-end" href="009-li-stock-insert.php?sid=<?= $sid ?>">＋新增鞋碼資料</a>
            </div>
        </div>
        <div class="col-3">
            <div class="addData d-flex" >
                <a class="nav-link d-flex justify-content-end" href="009-li.php">返回商品頁</a>
            </div>
        </div>
  </div>

  <div class="row">
    <div class="col">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col"><i class="fas fa-trash-alt"></i></th>
            <th scope="col">id</th>
            <th scope="col">商品圖片</th>
            <th scope="col">商品名稱</th>
            <th scope="col">鞋碼</th>
            <th scope="col">庫存</th>
            <th scope="col">
              <!-- <i class="fas fa-edit"></i> -->
              編輯
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r) : ?>
            <tr data-sid="<?= $r['id'] ?>">
              <td>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-warning del1btn" data-toggle="modal" data-target="#exampleModal">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
              <td><?= $r['id'] ?></td>
              <td>
              <?php $flag = 0 ?>
              <?php foreach($rowsImg as $ri): ?>
                <?php if ( $r['sid'] == $ri['products_sid'] AND $flag == 0): ?>
                <img src="./imgs/<?= htmlentities($ri['fileName'])?>" class="w-100" style="max-width: 150px;" alt="">
                <?php $flag = 1 ?>
                  <?php endif; ?>
                <?php endforeach; ?>
             </td>
              <td><?= htmlentities($r['name']) ?></td>
              <td><?php if(empty($r['size'])){
                echo '無資料';
              }else{
                echo $r['size'];
              } ?>
              </td>
              <td>
              <?php if(empty($r['stock'])){
                echo '無資料';
              }else{
                echo $r['stock'];
              } ?>
              </td>

              
              <td>
                <a href="009-li-stock-edit.php?id=<?= $r['id'] ?>&sid=<?= $r['sid'] ?>">
                <!-- 忘記&的做法ㄌ... -->
                  <i class="fas fa-edit"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">刪除注意</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary modal-del-btn">Delete</button>
      </div>
    </div>
  </div>
</div>




<?php include __DIR__ . '/partials/scripts.php';?>
<script>
  const myTable = document.querySelector('table');
  const modal = $('#exampleModal');


  myTable.addEventListener('click', function(event) {
    // console.log(event.target);


    // 判斷有沒有點到橙色的垃圾筒
    if (event.target.classList.contains('ajaxDelete')) {
      // console.log(event.target.closest('tr'));
      const tr = event.target.closest('tr');
      const id = tr.getAttribute('data-sid');
      if (confirm(`是否要刪除編號為 ${id} 的資料？`)) {
        fetch('009-li-stock-delete.php?id=' + id)
          .then(r => r.json())
          .then(obj => {
            if (obj.success) {
              tr.remove();
            } else {
              alert(obj.error);
            }
          });
      }

    }
  });


  let willDeleteId = 0;
  $('.del1btn').on('click', function(event) {
    willDeleteId = event.target.closest('tr').dataset.sid;
    console.log(willDeleteId);
    modal.find('.modal-body').html(`確定要刪除編號為 ${willDeleteId} 的資料嗎？`);
  });

  // 按了確定刪除的按鈕
  modal.find('.modal-del-btn').on('click', function(event) {
    console.log(`009-li-stock-delete.php?id=${willDeleteId}`);
    location.href = `009-li-stock-delete.php?id=${willDeleteId}`;
  });

  // modal 一開始顯示時觸發
  modal.on('show.bs.modal', function(event) {
    // console.log(event.target);
  });
</script>
<?php include __DIR__ . '/partials/html-foot.php';?>