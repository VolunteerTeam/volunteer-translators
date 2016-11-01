<style>
	.form-signin {
		max-width: 380px;
		padding: 15px 35px 45px;
		margin: 0 auto;
	}
	.form-control {
		width:100%;
	}
	label.checkbox {
		margin-left:20px;
	}
	.form-signin h2 {
		text-align:center;
	}
</style>

<div class="container main" role="main">
	<div class="row">
		<?php echo $this->session->flashdata('msg'); ?>
	</div>
	<div class="wrapper">
		<?php
			if(!isset($entry)) {
				$attributes = array("class" => "form-signin");
				echo form_open("/user/forgot/send", $attributes);
		?>
				<h2 class="form-signin-heading">Восстановление пароля</h2>
				<?php
					if(isset($msg) && $msg) echo "<div class='alert alert-success text-center'>".$msg."</div>";
					if(isset($error) && $error) echo "<div class='alert alert-danger text-center'>".$error."</div>";
				?>
				<input type="text" class="form-control" name="email" placeholder="Введите Email" size="50" autofocus="" />
				<button class="btn btn-success btn-block" type="submit">Восстановить</button>
		<?php   echo form_close();
			} else {
				$attributes = array("class" => "form-signin");
				echo form_open("", $attributes);
				?>
				<h2 class="form-signin-heading">Изменение пароля</h2>
				<?php
				    echo $this->session->flashdata('verify_msg');
					if(isset($msg) && $msg) echo "<div class='alert alert-success text-center'>".$msg."</div>";
					if(isset($error) && $error) echo "<div class='alert alert-danger text-center'>".$error."</div>";
				?>
				<input type="password" class="form-control" name="newpassword" placeholder="Введите новый пароль" required/>
				<input type="password" class="form-control" name="renewpassword" placeholder="Повторите новый пароль" required/>
				<button class="btn btn-success btn-block" type="submit">Отправить</button>
				<?php   echo form_close();
			} ?>
	</div>

</div>

