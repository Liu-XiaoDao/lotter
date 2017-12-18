<?php echo HTML::script('media/three/CSS3DRenderer.js'); ?>
<?php echo HTML::script('media/three/TrackballControls.js'); ?>
<style>
  .userbox {
    width: 300px;
    height: 300px;
    background: url('/upload/58732cc4244491441067569108-thumb.jpg') #fff center center no-repeat;
    background-size: contain;
    box-shadow: 0px 0px 20px rgba(255,255,255,0.8);
    border-radius: 10px;
    border: 1px solid rgba(127,255,255,0.25);
    cursor: default;
  }

  .userbox:hover {
    box-shadow: 0px 0px 20px rgba(0,255,255,0.75);
    border: 1px solid rgba(127,255,255,0.75);
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
</style>
<script>
if ( ! Detector.webgl ) Detector.addGetWebGLMessage();

    var render, scene, camera, controls, light;
    var mouseX = 0, mouseY = 0
    var font;
    var angleOfview = 75;
    var windowHalfX = window.innerWidth / 2;
    var windowHalfY = window.innerHeight / 2;
    var ratio=window.innerWidth/window.innerHeight;
    
    var objects = [], indexes = [];
    var targets = { table: [], sphere: [], helix: [], grid: [] };
  
    var maxW = 30;
    var maxH = 10;
    var imageW = 300, imageH =300;
    var unitW = unitH = 350;
    var cameraZ = 3000;

    var timer, tween, action_1 = false, action_2 = false;
    var loaded_index = 0;

    var api_base = '',
        api_sound_url = '<?php echo URL::site('media/sound.ogg', null, false); ?>',
        api_font_url = '<?php echo URL::site('media/font.json', null, false); ?>',
        api_user_url = '<?php echo URL::site('api/user/test?count=300'); ?>';
    
    render = new THREE.CSS3DRenderer();
    scene  = new THREE.Scene();
    scene.fog = new THREE.FogExp2( 0xffffff, 0.025 );

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
        camera=new THREE.PerspectiveCamera(angleOfview, ratio, 1, 10000);
        camera.position.x = 0;
        camera.position.y = 0;
        camera.position.z = cameraZ;
        scene.add(camera);
        light = new THREE.DirectionalLight( 0xffffff, 0.125 );
        light.position.set( 0, 0, cameraZ ).normalize();
        scene.add(light);
        var geometry = new THREE.Geometry();
        for ( var i = 0; i < 10000; i ++ ) {
          var vertex = new THREE.Vector3();
          vertex.x = THREE.Math.randFloatSpread( 2000 );
          vertex.y = THREE.Math.randFloatSpread( 2000 );
          vertex.z = 0;
          geometry.vertices.push( vertex );
        }
        var particles = new THREE.Points( geometry, new THREE.PointsMaterial( { color: 0x888888 } ) );
        scene.add( particles );


        controls = new THREE.TrackballControls( camera, render.domElement );
        controls.rotateSpeed = 0.5;
        controls.addEventListener( 'change', renderer );

        camera.lookAt(scene.position);
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
        var _users = json.info;
        var _users_count = _users.length;
        var key;
        for(key in _users) {
          var row = parseInt(key/maxW);
          var col = key%maxW;
          var object = new THREE.Object3D();
          object.position.x = ( col - maxW/2 ) * unitW;
          object.position.y = ( maxH/2 - row ) * unitH;
          object.position.z = 0;
          targets.table.push( object );
          
          (function() {
            var i = key;
            var to_obj = object;
            var img = new Image();
            img.src = _users[i].photo;
            img.onload = function() {
              var element = document.createElement( 'div' );
              element.className = 'userbox';
              element.style.backgroundImage = 'url('+img.src+')';
              var username = document.createElement( 'div' );
              username.className = 'username';
              username.textContent = _users[i].username;
              element.appendChild( username );

              var obj = new THREE.CSS3DObject( element );
              obj.position.x = 0;
              obj.position.y = -cameraZ;
              obj.position.z = cameraZ;
              scene.add( obj );
              objects.push( obj );
              setTimeout(function(){
                loaded_index += 1;
                if (_users_count == loaded_index) {
                  load_position();
                  tween = transform( targets.helix, 3000);
                  tween.onComplete(function() {
                    action_1 = true;
                  });
                }
                else {
                  transformOne(obj, to_obj, 10);
                }
              }, i*10);
            }
          })();
        }
      });
    }


    function load_position() {

      var radius = cameraZ - 500;
      // helix
      var vector = new THREE.Vector3();
      for ( var i = 0, l = objects.length; i < l; i ++ ) {
        var phi = ( i/18 +1 ) *Math.PI ;
        var object = new THREE.Object3D();
        object.position.x = radius * Math.sin( phi );
        object.position.y = -i*10 + unitH*5;
        object.position.z = radius * Math.cos( phi );
        vector.copy( object.position );
        vector.x *= 2;
        vector.z *= 2;
        object.lookAt( vector );
        targets.helix.push( object );
      } 
      //sphere
      var radius = cameraZ - 700;
      ratioXYZ = cameraZ/radius;
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

    function renderer()
    {
        render.render(scene, camera);
    }
    
    var next = 0;
    function animate() {
      if (action_1) {
        next += 0.002;
        camera.position.x = cameraZ * Math.sin( next );
        camera.position.z = cameraZ * Math.cos( next );
        camera.position.y = 500 * Math.sin( next );
        camera.lookAt({x:0,y:0,z:camera.position.z});
      }
      else {}
      requestAnimationFrame( animate );
      TWEEN.update();
      controls.update();
    }

    function onWindowResize() 
    {
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        render.setSize( window.innerWidth, window.innerHeight );
    }

  function transformOne( obj, target, duration) {
    new TWEEN.Tween( obj.position )
      .to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
      .easing( TWEEN.Easing.Exponential.InOut )
      .onUpdate( renderer )
      .start();
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
      .onUpdate( renderer )
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

  var ratioXYZ;
  function show_me() {
    next = parseInt(Math.random()*objects.length);
    var target = objects[next]; 
    var object = camera;
    duration = 1000;
      new TWEEN.Tween( object.position )
        .to( { x: target.position.x*ratioXYZ, y: target.position.y*ratioXYZ, z: target.position.z*ratioXYZ },  duration )
        .easing( TWEEN.Easing.Exponential.InOut )
        .start();

    return new TWEEN.Tween( this )
      .to( {}, duration * 2 )
      .onUpdate( renderer )
      .start();
  }

  window.onkeyup = function(e) {
    var key = e.keyCode ? e.keyCode : e.which;
    if (key == 13) {
      action_1 = false;
      tween = transform(targets.sphere, 3000); 
      tween.onComplete(function(){
        timer = setInterval('show_me()', 1200);
      });
    }
  }
</script>
