<div class="container main" role="main">
<form method="POST" id="auth" >
<h3>Mail(Почта)</h3> 
<input style="width:250px;" class="form-control" type="text" id="username" name="mail" value="" size="50" /> 
<h3>Пароль</h3>
 <input style="width:250px;" class="form-control" type="password" id="password" name="password" value="" size="50" /> 
<p style="padding-top: 10px;">
<input name="submit" type="submit" class="art-button"  value="Войти"/><br>
<a href="/user/forgot">Забыли пароль?</a>
</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.js"></script>
</div>

<script>
// this is the id of the form
$("#auth").submit(function() {

    var url = "/user/auth/ajax"; // the script where you handle the form input.

    $.ajax({
           type: "POST",
           url: url,
           data: $("#auth").serialize(), // serializes the form's elements.
           dataType: 'json'
         })
    .done(function(data) {
        if(data.success) {
        	window.location = "/user/profile";
        } else {
        	alert("Неверный логин или пароль!");
        }
    });
    event.preventDefault();
});

</script>
