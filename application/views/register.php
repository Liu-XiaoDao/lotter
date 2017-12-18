<div class="register-box">
  <h1 class="title">请填写以下信息</h1>
  <?php echo Form::open('register/post', ['class'=>'uk-form uk-form-stacked']); ?>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">工牌号</label>
      <div class="uk-form-controls">
      <span class="id-pre">
      <select id="pre" name="post[pre]">
      <option value="SWN-">XXX-</option>
      <option value="XXX-">SWN-</option>
      </select></span><input type="number" id="id" value="20170020" name="post[id]" class="uk-width-1-1" placeholder="请输入五位数字工牌号码">
      </div>
    </div>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">姓名</label>
      <div class="uk-form-controls">
      <input type="text" id="username" value="dxx" name="post[username]" class="uk-width-1-1" placeholder="请输入你的真实姓名">
      </div>
    </div>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">手机号码</label>
      <div class="uk-form-controls">
      <input type="number" id="phone" value="18106322292" name="post[phone]" class="uk-width-1-1" placeholder="请输入你的手机号码">
      </div>
    </div>

    <div class="uk-form-row">
      <div class="uk-form-file">
          <button class="uk-button">选择照片(请注意框选显示部分)</button>
          <input type="file" name="photo" id="upload-select" accept="image/*;" capture="camera">
      </div>
      <div id="progressbar" class="uk-progress uk-hidden">
          <div class="uk-progress-bar" style="width: 0%;">...</div>
      </div>
      <div id="photo-editor" class="uk-hidden" style="margin: 10px 0;">
        <img src="" id="photo-preview" style="width: 500px;" />
        <input type="hidden" name="post[photo]" id="post-photo" value="" />
        <input type="hidden" name="post[cropx]" id="cropx" value="0" />
        <input type="hidden" name="post[cropy]" id="cropy" value="0" />
        <input type="hidden" name="post[cropw]" id="cropw" value="500" />
        <input type="hidden" name="post[croph]" id="croph" value="500" />
      </div>
    </div>
    <div class="uk-form-row">
      <label class="uk-form-label" for="">
      <button class="uk-button" id="btn_submit" disabled="disabled" type="submit">提交，注册参加年会</button>
      </label>
    </div>
  </form>
</div>
<?php echo HTML::script('media/Jcrop.min.js'); ?>
<?php echo HTML::script('media/js/components/upload.min.js'); ?>
<?php echo HTML::script('media/js/components/notify.min.js'); ?>
<?php echo HTML::style('media/Jcrop.min.css'); ?>
<?php echo HTML::style('media/css/components/form-file.min.css'); ?>
<?php echo HTML::style('media/css/components/progress.min.css'); ?>
<?php echo HTML::style('media/css/components/notify.min.css'); ?>
<script>
$(function(){

  var progressbar = $("#progressbar"),
      editor      = $('#photo-editor'),
      preview     = $('#photo-preview'),
      photo       = $('#post-photo'),
      submit      = $('#btn_submit'),
      bar         = progressbar.find('.uk-progress-bar'),
      settings    = {
      action: '<?php echo URL::site('api/upload'); ?>', // upload url
      allow : '*.(jpg|jpeg|png)', // allow only images
      param: 'photo',
      notallowed: function() {
        UIkit.notify('请选择允许的图片格式（jpg|jpeg|png）', {status:'warning'});
      },
      loadstart: function() {
          bar.css("width", "0%").text("0%");
          progressbar.removeClass("uk-hidden");
      },
      progress: function(percent) {
          percent = Math.ceil(percent);
          bar.css("width", percent+"%").text(percent+"%");
      },
      allcomplete: function(response) {
          res = $.parseJSON(response);
          if (res.code ==0 ) {
            if (editor.hasClass('uk-hidden')) {
              editor.removeClass("uk-hidden");
            }
            submit.prop('disabled', false);
            photo.val(res.thumb);
            preview.attr('src', res.thumb);
            preview.Jcrop({
              setSelect: [ 0,0,200,200 ],
              aspectRatio: 1,
              minSize: 100,
              bgColor: 'black'
            });
            editor.on('cropmove cropend',function(e,s,c){
              $('#cropx').val(c.x);
              $('#cropy').val(c.y);
              $('#cropw').val(c.w);
              $('#croph').val(c.h);
            });
          }
          else {
            UIkit.notify(res.info, {status:'warning'});
          }
          bar.css("width", "100%").text("100%");
          setTimeout(function(){
              progressbar.addClass("uk-hidden");
          }, 250);
      },
      error: function(e) {
        UIkit.notify(e);
      }
  };

  var select = UIkit.uploadSelect($("#upload-select"), settings),
      drop   = UIkit.uploadDrop($("#upload-drop"), settings);
  var fn_btn_submit = function() {
    var username = $('#username').val();
    var phone    = $('#phone').val();
    var pre      = $('#pre').val();
    if (pre == '') {
        UIkit.notify('工牌号码为空', {status:'warning'});
        return false;
    }
    if (username == '') {
        UIkit.notify('姓名为空', {status:'warning'});
        return false;
    }
    if (phone == '') {
        UIkit.notify('电话号码为空', {status:'warning'});
        return false;
    }
    return true;
  }
  $('#btn_submit').click(fn_btn_submit);
});
</script>
