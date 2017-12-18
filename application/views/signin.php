<div class="register-box">
  <h1 class="title"><?php echo $year, $core->name; ?></h1>
  <h2 class="title">激活我的帐号</h1>
  <?php echo Form::open('signin/post', ['class'=>'uk-form uk-form-stacked']); ?>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">手机号码</label>
      <div class="uk-form-controls">
      <input type="number" id="phone" name="post[phone]" class="uk-width-1-1" placeholder="请输入你的手机号码">
      </div>
    </div>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">
      <button class="uk-button" id="btn_submit" type="submit">提交，激活</button>
      </label>
    </div>
  </form>
</div>

