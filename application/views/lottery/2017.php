<?php echo HTML::script('media/three/CSS3DRenderer.js'); ?>
<style>
  body { 
    background-color: #050505;
    background: radial-gradient(ellipse at center,  rgba(43,45,48,1) 0%,rgba(0,0,0,1) 100%);;  }
  .userbox {
    width: 300px;
    height: 300px;
    background: url() #fff center center no-repeat;
    background-size: contain;
    box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
    border-radius: 10px;
    border: 1px solid rgba(127,255,255,0.25);
    cursor: default;
  }

  .userbox:hover, .userbox_selected {
    box-shadow: 0px 0px 50px rgba(255,255,255,0.55);
    border: 2px solid rgba(255,255,255,0.75);
  }

  .userbox .username {
    position: absolute;
    top: 270px;
    width: 100%;
    font-size: 14px;
    text-align: right;
    right: 30px;
    color: rgba(255,255,255,0.75);
  }
  #progressbar { 
    position: fixed; width: 1000px;  border: 1px solid #fff; border-radius: 5px; height: 30px; line-height: 30px;
    color: #fff;
    left: 50%;
    margin-left: -500px;
    bottom: 50px;
    z-index: 999;
  }
  #bar { width: 0px; background: #fff; height: 30px;  }
  
</style>
<div id="progressbar"><div id="bar"><?php echo URL::site('media/background.jpg', null, false); ?></div></div>
<script>
if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

    var render, scene, camera, controls, light;
    var mouseX = 0, mouseY = 0
    var font;
    var angleOfview = 75;
    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2;
    var ratio=window.innerWidth/window.innerHeight;
    
    var objects = [], indexes = [], group;
    var targets = { table: [], sphere: [], helix: [], grid: [] };
  
    var maxW = 30;
    var maxH = 10;
    var imageW = 300, imageH = 300;
    var unitW = unitH = 350;
    var cameraZ = 4000;

    var timer, tween, action_step = 0;
    var count_loaded = 0, count = 0;

    var api_base = '',
        backgroundImage_url = '<?php echo URL::site('media/background.jpg', null, false); ?>';
        api_sound_url = '<?php echo URL::site('media/sound.ogg', null, false); ?>',
        api_font_url = '<?php echo URL::site('media/font.json', null, false); ?>',
        api_user_url = '<?php echo URL::site('api/user/test?count=300'); ?>';
    
    render = new THREE.CSS3DRenderer();
    scene  = new THREE.Scene();

    init();
    /*load_font();*/
    /*load_sound();*/
    load_award();
    load_user();
    animate();

    function init() {
        render.setSize(window.innerWidth, window.innerHeight);
        render.domElement.style.position = 'absolute';
        render.domElement.style.top = 0;
        //render.domElement.style.backgroundImage = 'url('+backgroundImage_url+')';
        render.domElement.style.backgroundColor = '#050505';
        render.domElement.style.backgroundImage = '-webkit-radial-gradient(center, ellipse cover,  rgba(43,45,48,1) 0%,rgba(0,0,0,1) 100%)';
        camera=new THREE.PerspectiveCamera(angleOfview, ratio, 1, 10000);
        camera.position.x = 0;
        camera.position.y = 0;
        camera.position.z = cameraZ;
        scene.add(camera);
        group = new THREE.Group();
        scene.add(group);

        camera.lookAt(new THREE.Vector3 (0, 0, -1000000000));
        document.body.appendChild( render.domElement );
        window.addEventListener( 'resize', onWindowResize, false );
    }
    
    function load_font() {
      var loader = new THREE.FontLoader();
      loader.load( api_font_url, function ( response ) {
        font = response;
        var material = new THREE.MultiMaterial( [
            new THREE.MeshPhongMaterial( { color: 0xffff00, shading: THREE.FlatShading } ), // front
            new THREE.MeshPhongMaterial( { color: 0xffffff, shading: THREE.SmoothShading } ) // side
          ] );

        var textGeo = new THREE.TextGeometry( '舒卫能', {
          font: font,
          size: 100,
          height: 20,
          curveSegments: 4,
          bevelThickness: 2,
          bevelSize: 1.5,
          bevelEnabled: true,
          material: 0,
          extrudeMaterial: 1
        });
        var textMesh = new THREE.Mesh( textGeo, material );
        textMesh.position.x = 0;
        textMesh.position.y = 200;
        textMesh.position.z = 0;
        scene.add( textMesh );
      });
    }

    function load_sound() {
      var audioListener = new THREE.AudioListener();
      camera.add( audioListener );
      var oceanAmbientSound = new THREE.Audio( audioListener );
      scene.add( oceanAmbientSound );
      var loader = new THREE.AudioLoader();
      loader.load( api_sound_url, function( buffer ) {
        oceanAmbientSound.setBuffer( buffer );
        oceanAmbientSound.play();
      }); 
    }

    function load_award() {
    }

    function load_user() {
      ajax_request(api_user_url, function(json) {
        var users = json.info;
        count = users.length;
        var key;
        for(key in users) {
          var row = parseInt(key/maxW);
          var col = key%maxW;
          var _x = ( col - maxW/2 ) * unitW + unitW/2;
          var _y = ( maxH/2 - row ) * unitH - unitH/2;
          
          (function() {
            var i = key;
            var x = _x;
            var y = _y;
            var z = 0;
            var img = new Image();
            img.src = users[i].photo;
            img.onload = function() {
              var element = document.createElement( 'div' );
              element.className = 'userbox';
              element.style.backgroundImage = 'url('+img.src+')';
              var username = document.createElement( 'div' );
              username.className = 'username';
              username.textContent = users[i].username;
              element.appendChild( username );

              var obj = new THREE.CSS3DObject( element );
              obj.position.x = x;
              obj.position.y = y;
              obj.position.z = z;
              group.add (obj );
              objects.push( obj );
              count_loaded += 1;
              document.getElementById('bar').style.width = 1000* (count_loaded/count)+'px';
              if (count == count_loaded) {
                document.getElementById('progressbar').style.display = 'none';
                load_position();
                tween = transform( targets.sphere, 3000);
                tween.onUpdate(function() {
                  action_step = 1; 
                });
                tween.onComplete(function() {
                  update_selecting_me();
                });
                new TWEEN.Tween( camera.position )
                  .to( { x: camera.position.x, y: camera.position.y, z: camera.position.z-1500 },  3000 )
                  .easing( TWEEN.Easing.Quadratic.Out )
                  .onComplete(function(){
                    window.addEventListener( 'keyup', process_keyup, false );
                  })
                  .start();
              }
            }
          })();
        }
      });
    }

    function load_position() {
			var col = 8, row = 8, depth = 0;
      for ( var i = 0; i < objects.length; i ++ ) {
				var _col = i % col;
				var _row = parseInt( i/col) % row;
				var _depth = parseInt( i/(col*row));
        var object = new THREE.Object3D();
        object.position.x =  (( _col - col/2 ) + 1/2) *unitW * 3;
        object.position.y = (( row/2 - _row ) - 1/2) * unitH * 3;
        object.position.z = -_depth*1000 + 1000;
        targets.grid.push( object );
      }
      //sphere
      var radius = cameraZ - 2000;
      var vector = new THREE.Vector3();
      for ( var i = 0, l = objects.length; i < l; i ++ ) {
        var phi = Math.acos( -1 + ( 2 * i ) / l );
        var theta = Math.sqrt( l * Math.PI ) * phi;
        var object = new THREE.Object3D();
        object.position.x = radius * Math.cos( theta ) * Math.sin( phi );
        object.position.y = radius * Math.sin( theta ) * Math.sin( phi );
        object.position.z = radius * Math.cos( phi );
        vector.copy( object.position ).multiplyScalar( 2 );
        object.lookAt( vector );
        targets.sphere.push( object );
      }
    }


    function onWindowResize() 
    {
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        render.setSize( window.innerWidth, window.innerHeight );
    }

  function transform( targets, duration ) {
    TWEEN.removeAll();
    for ( var i = 0; i < objects.length; i ++ ) {
      var object = objects[ i ];
      var target = targets[ i ];
      new TWEEN.Tween( object.position )
        .to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
        .easing( TWEEN.Easing.Exponential.InOut )
        .start();

      new TWEEN.Tween( object.rotation )
        .to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
        .easing( TWEEN.Easing.Exponential.InOut )
        .start();
    }
    return new TWEEN.Tween( this )
      .to( {}, duration * 2 )
      .start();
  }

  function ajax_request(url, callback) {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if ( xhr.readyState == 4 ) {
            if ( xhr.status == 200 || xhr.status == 0 ) {
                try {
                    var json = JSON.parse( xhr.responseText );
                    callback(json);
                } 
                catch ( error ) {
                    console.error( error );
                    console.warn( "DEPRECATED: [" + url + "] seems to be using old model format" );
                }
            } 
            else {
                console.error( "Couldn't load [" + url + "] [" + xhr.status + "]" );
            }
        }
      };
      xhr.open( "POST", url, true );
      xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
      xhr.setRequestHeader( "Content-Type", "text/plain" );
      xhr.send( null );
  }


  function renderer()
  {
    render.render(scene, camera);
  }
  
  function animate() {
    switch(action_step) {
    case 1:
        update_show_me();
        break;
    }
    requestAnimationFrame( animate );
    TWEEN.update();
    renderer();
  }

  var rotation_step = 0.002;
  var rotation_step_small = 0.00003;
  var _rotation_step = 0;
  function update_show_me() {
    if (_rotation_step < rotation_step) {
      _rotation_step += rotation_step_small;
    }
    else {
      _rotation_step = rotation_step;
    }
    group.rotation.y -= _rotation_step*2;
    group.rotation.x = Math.sin(group.rotation.y);
  }

  var keyup_count = 0;
  var selected = null;
  function process_keyup(e) {
    if (action_step == 1) 
      action_step = 2;
    keyup_count += 1;
    var next = keyup_count % 2;
    if (next) { 
      if (keyup_count == 1) {
        tween = transform(targets.grid, 3000);
        tween.onComplete(update_selecting_me); 
        new TWEEN.Tween( group.rotation )
          .to( { x: 0, y: 0, z: 0 },  3000 )
          .easing( TWEEN.Easing.Exponential.InOut)
          .start();
      }
      else {
        update_selecting_me();
      }
    }
    else {
      /*update_award_me(); */
      objects[selected].element.className += ' userbox_selected';
    }
  }

  function update_award_me()
  {
    var _obj = objects[selected];
    for(var i=0; i<4; i++) {
      light[i].position = _obj.position;
      light[i].rotation.z = (i+1)*Math.PI/4;
      switch(i) {
        case 0:
          light[i].position.y += unitH;
          break;
        case 1:
          light[i].position.x -= unitW;
          break;
        case 2:
          light[i].position.y -= unitH;
          break;
        case 3:
          light[i].position.x += unitW;
          break;
      }
    }
  }

  function update_selecting_me()
  {
    if (keyup_count%2 == 0 ) return false;
    selected = parseInt(Math.random()*count);
    var obj = objects[selected];
    new TWEEN.Tween( camera.position )
      .to( { x: obj.position.x, y: obj.position.y, z: obj.position.z + 300 },  1000 )
      .easing( TWEEN.Easing.Exponential.InOut)
      .onComplete(function() {
        camera.lookAt(obj.position);
        update_selecting_me();
      })
      .start();
  }

</script>
