<?php if ($res): ?>
<table class="uk-table uk-table-hover">
<thead>
  <tr>
      <th>头像</th>
      <th>姓名</th>
      <th>电话</th>
      <th>工牌</th>
      <th>激活</th>
      <th>删除</th>
  </tr>
</thead>
  <?php foreach($res as $one): ?>
  <tr>
  <td><?php echo HTML::image(HTML::entities($one->photo), ['style'=>'height: 50px;']); ?></td>
  <td><?php echo HTML::entities($one->username); ?></td>
  <td><?php echo HTML::entities($one->phone); ?></td>
  <td><?php echo HTML::entities($one->pre.$one->id); ?></td>
  <td><?php echo Arr::get($one, 'active')?'已激活':HTML::anchor('user/active'.URL::query(['_id'=>(string)$one->_id]),'激活'); ?></td>
  <td><?php echo HTML::anchor('user/delete/'.URL::query(['_id'=>(string)$one->_id]), '删除'); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php echo Pagination::factory(['total_items'=>$count])->render(); ?>
<?php endif; ?>
