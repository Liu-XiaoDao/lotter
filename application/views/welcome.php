
<div class="welcome-box">
  <h2 class="title"><?php echo $year, $core->name; ?></h2>
  <?php if (isset($res) && $res): ?>
  <p style="text-align: center;"><?php echo HTML::image($res->photo, ['style'=>'width: 100px; border: 1px #eee solid; border-radius: 5px;']); ?></p>
  <h3>
    系统欢迎 "<?php echo HTML::chars(Arr::get($res, 'username')); ?>" 的加入，
    <?php if (Arr::get($_GET, 'active')):?>
      你的信息已被激活生效
    <?php else: ?>
      请在会场签到激活你的信息
    <?php endif; ?>
  </h3>
  <?php endif; ?>
  <p>当前 激活 / 参加：<?php echo $count_active,' / ',$count; ?>人</p>
  <p>
    <?php echo HTML::anchor('register', '报名入口', ['class'=>'']); ?>
    <?php // echo HTML::anchor('lottery', '抽奖入口', ['class'=>'']); ?>
  </p>
  <p>
    活动日期：<?php echo Arr::path($core, sprintf('%s.time', $year)); ?> <br />
    活动地点：<?php echo Arr::path($core, sprintf('%s.address', $year)); ?>
  </p>
</div>

