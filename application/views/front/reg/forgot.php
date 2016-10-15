<div class="container main" role="main">

<?php if(@$error != '') { ?>
	<h3 style="text-align: center;"><?php echo $error; ?></h3>
<?php } ?>

<?php if(@!$success) { ?>
	<h3>Восстановление пароля</h3> 
	<form method="POST" action="/user/forgot/send">
		<input style="width:250px;" class="form-control" type="text" name="email" value="" placeholder="Введите Email" size="50" /><br>
		<input type="submit" class="art-button"  value="Восстановить"/>
	</form>
<?php } ?>

<?php if(@$entry) { ?>
	<h3 style="text-align: center;">Изменение пароля</h3> <br>
	<form method="POST">
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Введите новый пароль</label>
				<input type="text" name="newpassword" class="form-control" style="width:100%;">
			</div>
			<div class="col-md-6">
					<label class="control-label">Повторите новый пароль</label>
					<input type="text" name="renewpassword" class="form-control" style="width:100%;">
			</div>
		</div>
		<br />
		<center>
			<input type="submit" name="submit" value="Отправить">
		</center>
	</form>

<?php } ?>

</div>
