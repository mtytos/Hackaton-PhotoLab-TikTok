<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Reset password</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="header">
        <p class="gradient">CAMAGRU</p>
    </div>
    <div class="content">
        <div class="main">
            <button id="jopa">ok</button>
        </div>
    </div>
    <div class="footer">
        <p class="gradient">&copy; Copyright<br>created by student School 21 - Mtytos</p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
    var testData = {
                "image_url[1]":"http://temp-images.ws.pho.to/d98d8299df448e1db590186e028c4819258ad54b.jpeg",
                "template_name":"1003506",
                "animated":"1" 
            };
    $(function(){
    $('#jopa').on('click', function(e){
        e.preventDefault();
        $.ajax({
            url: 'http://api-soft.photolab.me/template_process.php',
            data: testData,
            method: 'POST',
            dataType: 'json',
            success: function(data){
                console.log(data);
            }
        });
    });
    });
</script>
</body>
</html>