<?php
  date_default_timezone_set('Asia/Ho_Chi_Minh');
  $listData = $db->listData($menuPage->id);
  if(count($listData)){
  ?>
<?php if(isset($id)){ 
  $cartData = $db->alone_data_where('data','id',$id);
  $listData = $db->listDataChild($id);
  ?>
<h3><b>Khách hàng: <?= htmlspecialchars($cartData->title) ?></b></h3>
<p><i class="fa fa-phone"></i> Số điện thoại: <?=htmlspecialchars($cartData->phone) ?></p>
<p><i class="fa fa-envelope"></i> Email: <?=htmlspecialchars($cartData->email) ?></p>
<p><i class="fa fa-map-marker"></i> Địa chỉ: <?=htmlspecialchars($cartData->address) ?></p>
<p><i class="fa fa-envelope"></i> Tin nhắn: <?=htmlspecialchars($cartData->content) ?></p>
<p><i class="fa fa-clock-o"></i> Thời gian: <?=htmlspecialchars(date("d/m/Y H:i:s", $cartData->time)) ?></p>
<table class="table">
  <thead>
    <tr>
      <th>Hình</th>
      <th>Tên sản phẩm</th>
      <th>Giá</th>
      <th>Số lượng</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($listData as $key=>$data){ $sp = $db->alone_data_where('data','id',$data->cart);
      $menuParent = $db->menuParent($sp->menu);
      ?>
    <tr align="center">
      <td>
        <a <?=linkId($sp,$menuParent->name); ?>>
        <img style="height:50px;" <?=srcImg($sp); ?>/>
        </a>
      </td>
      <td><a <?=linkId($sp,$menuParent->name); ?>><?=$sp->title ?></a></td>
      <td><?=number_format(intval($sp->price))?><sup>đ</sup></td>
      <td><?=($data->count)?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<h3><b>Tổng tiền: <?=number_format(intval($cartData->price)) ?><sup>đ</sup></b></h3>
<?php }else{ ?> 
<div class="box-header">
  <h3 class="box-title">
    <button class="btn btn-success selectAll" data-target="table > tbody > tr" type="button"><i class="fa fa-check-square-o"></i> Chọn tất cả</button>
    <button class="btn btn-danger delAll"  data-target="table >tbody > tr.selected" type="button"><i class="fa fa-trash"></i> Xóa đã chọn</button>
  </h3>
</div>
<div class="box-body">
  <table class="table">
    <thead>
      <tr>
        <th>Tên khách hàng</th>
        <th>Số điện thoại</th>
        <th>Email</th>
        <th>Tin nhắn</th>
        <th>Địa chỉ</th>
        <th>Tổng đơn hàng</th>
        <th>Thời gian</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php
        foreach($listData as $key=>$data){
        ?>
      <tr align="center" data-id="<?=$data->id?>">
        <td><?=htmlspecialchars($data->title)?></td>
        <td><?=htmlspecialchars($data->phone)?></td>
        <td><?=htmlspecialchars($data->email)?></td>
        <td><?=htmlspecialchars($data->content)?></td>
        <td><?=htmlspecialchars($data->address)?></td>
        <td><?=htmlspecialchars(number_format(intval($data->price)))?><sup>đ</sup></td>
        <td><?=htmlspecialchars(date("d/m/Y H:i:s", $data->time))?></td>
        <td class="action">
          <a <?=linkId($data, $name); ?> class="btn btn-primary"><i class="fa fa-eye"></i></a>
          <a <?=linkDelId($data->id); ?>><i class="fa fa-trash"></i></a>
        </td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php }}else{ ?>
<h3>Chưa có <?=$title?> !</h3>
<?php } ?>