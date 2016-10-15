<div class="container main" role="main">
<style type="text/css">
        .form-control:focus { 
            border-color: #F70707;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(247, 7, 7, 1);
         }
</style>
	<center>
		<h1>Регистрация</h1>
	</center>
	<?php echo validation_errors(); ?>
	<?php echo form_open('user/reg'); ?>
	<form action="/user/profile/save" method="post" enctype="multipart/form-data">
		<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Фамилия
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="surname" value="<?php echo @$_POST['surname']; ?>" id="last_name" class="form-control" style="width:100%;" required="1">
			</div>
			<div class="col-md-4">
				<label class="control-label">
					Имя
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="name" value="<?php echo @$_POST['name']; ?>" id="first_name" class="form-control" style="width:100%;" required="1">
			</div>
			<div class="col-md-4">
				<label class="control-label">Отчество<span style="color: red;">*</span></label>
				<input type="text" name="patronymic" value="<?php echo @$_POST['patronymic']; ?>" id="patro_name" class="form-control" style="width:100%;" required="1">
			</div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Электронная почта
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="email" value="<?php echo @$_POST['email']; ?>" id="email" class="form-control" style="width:100%;">
			</div>
			<div class="col-md-4">
				<label class="control-label">Пароль<span style="color: red;">*</span></label>
				<div id="mypass-wrap" class="control-group">
					<input type="password" name="password" value="" id="mypass" placeholder="пароль" class="form-control" style="width:100%;" required></div>
				</div>
			<div class="col-md-4">
				<label class="control-label">Повторите пароль<span style="color: red;">*</span></label>
				<div id="mypass-wrap" class="control-group">
					<input type="password" name="password" value="" id="mypass" placeholder="пароль" class="form-control" style="width:100%;" required></div>
				</div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">
					Представление(вкратце о себе)
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="pret" value="<?php echo @$_POST['pret']; ?>" id="intro" class="form-control" style="width:100%;" required>
			</div>

			<div class="col-md-6">
				<label class="control-label">
					Координаты
				</label>
				<input type="text" name="coordinates" value="<?php echo @$_POST['coordinates']; ?>" class="form-control" style="width:100%;" >
			</div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-12">
				<label class="control-label">
					Должность(кем работаете)
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="dol" value="<?php echo @$_POST['dol']; ?>" id="job_post" class="form-control" style="width:100%;" required></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Дата рождения
					<span style="color: red;">*</span>
				</label>
				<input type="date" name="calendar" value="<?php echo @$_POST['calendar']; ?>" id="datepicker" class="form-control" style="width:100%;" required></div>
			<div class="col-md-4">
				<label class="control-label">Пол</label>
				<select name="sex" id="" class="form-control" style="width:100%;" size="1" required>
					<option value="male" <?php if(@$_POST['sex'] == 'male') { echo "selected"; } ?> >Мужской</option>
					<option value="famale" <?php if(@$_POST['sex'] == 'famale') {echo "selected"; } ?> >Женский</option>
				</select>
		</div>
		<div class="col-md-4">
			<label class="control-label">
				Город
				<span style="color: red;">*</span>
			</label>
			<input type="text" name="city" value="<?php echo @$_POST['city']; ?>" id="coordinates" class="form-control" style="width:100%;" required></div>
	</div>
	<div class="row bestmedia-input">
		<div class="col-md-6">
			<label class="control-label">
				Сотовый телефон
				<span style="color: red;">*</span>
			</label>
			<input type="text" name="tel" value="<?php echo @$_POST['tel']; ?>" id="phone" placeholder="+7-ххх-ххх-хх-хх" class="form-control" style="width:100%;" required></div>
		<div class="col-md-6">
			<label class="control-label">Скайп</label>
			<input type="text" name="skype" value="<?php echo @$_POST['skype']; ?>" id="skype" class="form-control" style="width:100%;"></div>
	</div>

	<div style="height: 50px;"></div>
	<div class="row bestmedia-input">
		<div class="col-md-6">
			<label class="control-label">Facebook</label>
			<input type="text" name="fb_profile" value="<?php echo @$_POST['fb_profile']; ?>" id="fb_profile" class="form-control" style="width:100%;"></div>
		<div class="col-md-6">
			<label class="control-label">Вконтакте</label>
			<input type="text" name="vk_profile" value="<?php echo @$_POST['vk_profile']; ?>" id="vk_profile" class="form-control" style="width:100%;"></div>
	</div>
	<div class="row bestmedia-input">
		<div class="col-md-6">
			<label class="control-label">Одноклассники</label>
			<input type="text" name="od_profile" value="<?php echo @$_POST['od_profile']; ?>" id="od_profile" class="form-control" style="width:100%;"></div>
		<div class="col-md-6">
			<label class="control-label">Google+</label>
			<input type="text" name="gp_profile" value="<?php echo @$_POST['gp_profile']; ?>" id="gp_profile" class="form-control" style="width:100%;"></div>
	</div>
	<div class="row bestmedia-input">
		<div class="col-md-6">
			<label class="control-label">Twitter</label>
			<input type="text" name="tw_profile" value="<?php echo @$_POST['tw_profile']; ?>" id="tw_profile" class="form-control" style="width:100%;"></div>
		<div class="col-md-6">
			<label class="control-label">Instagram</label>
			<input type="text" name="in_profile" value="<?php echo @$_POST['in_profile']; ?>" id="in_profile" class="form-control" style="width:100%;"></div>
	</div>
	<div class="row bestmedia-input">
		<div class="col-md-6">
			<label class="control-label">Livejournal</label>
			<input type="text" name="lj_profile" value="<?php echo @$_POST['lj_profile']; ?>" id="lj_profile" class="form-control" style="width:100%;"></div>
		<div class="col-md-6">
			<label class="control-label">LinkenId</label>
			<input type="text" name="li_profile" value="<?php echo @$_POST['li_profile']; ?>" class="form-control" style="width:100%;"></div>
	</div>

	<div style="height: 50px;"></div>
	<div class="row">
		<div class="col-md-12">
			<label class="control-label">Группы</label>
			<select class="form-control" style="width:100%;" name="group" size="1">
					<option <?php if(@$_POST['group'] == 'Заказчик') { echo "selected"; } ?>>Заказчик</option>
					<option <?php if(@$_POST['group'] == 'Волонтёр') { echo "selected"; } ?>>Волонтёр</option>
		</select>
	</div>
</div>

<div style="height: 30px;"></div>
<div>
	<div class="text-center">
		<input type="submit" name="do" value="Сохранить" class="btn btn-success"></div>
</div>
</form>
</div>