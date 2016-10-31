<div class="container main" role="main">
<center>
	<h1>Профиль</h1>
</center>
<?php echo validation_errors(); ?>

<form action="/user/profile/save" method="post" enctype="multipart/form-data">
<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Фамилия
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="surname" value="<?=$user['last_name'];?>" id="last_name" class="form-control" style="width:100%;" required="1"></div>
			<div class="col-md-4">
				<label class="control-label">
					Имя
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="name" value="<?=$user['first_name'];?>" id="first_name" class="form-control" style="width:100%;" required="1"></div>
			<div class="col-md-4">
				<label class="control-label">Отчество</label>
				<input type="text" name="patronymic" value="<?=$user['patro_name'];?>" id="patro_name" class="form-control" style="width:100%;" required="1"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">
					Представление(вкратце о себе)
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="pret" value="<?=$user['about'];?>" class="form-control" style="width:100%;"></div>
				

			<div class="col-md-6">
				<label class="control-label">
					Координаты
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="coordinates" value="<?=$user['coordinates'];?>" id="intro" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-12">
				<label class="control-label">
					Должность(кем работаете)
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="dol" value="<?=$user['job_post'];?>" id="job_post" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Дата рождения
					<span style="color: red;">*</span>
				</label>
				<input type="date" name="calendar" value="<?=$user['dob'];?>" id="datepicker" class="form-control" style="width:100%;"></div>
			<div class="col-md-4">
				<label class="control-label">Пол</label>
				<select name="sex" id="" class="form-control" style="width:100%;" size="1">
					<option <?php if($user['sex'] == "male") { echo "selected"; } ?> value="male">Мужской</option>
					<option <?php if($user['sex'] == "famale") { echo "selected"; } ?> value="famale">Женский</option>
				</select>
			</div>
			<div class="col-md-4">
				<label class="control-label">
					Город
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="city" value="<?=$user['intro'];?>" id="coordinates" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-4">
				<label class="control-label">
					Сотовый телефон
					<span style="color: red;">*</span>
				</label>
				<input type="text" name="tel" value="<?=$user['phone'];?>" id="phone" class="form-control" placeholder="+7-ххх-ххх-хх-хх" style="width:100%;"></div>
			<div class="col-md-4">
				<label class="control-label">
					Электронная почта
					<span style="color: red;">*</span>
				</label>
				<input type="text" value="<?=$user['email'];?>" id="email" class="form-control" style="width:100%;" disabled>
				<input type="hidden" name="email" value="<?=$user['email'];?>" id="email" class="form-control" style="width:100%;">
			</div>
			<div class="col-md-4">
				<label class="control-label">Скайп</label>
				<input type="text" name="skype" value="<?=$user['skype'];?>" id="skype" class="form-control" style="width:100%;"></div>
		</div>

		<div style="height: 50px;"></div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Facebook</label>
				<input type="text" name="sc[fb_profile]" value="<?=$user['fb_profile'];?>" id="fb_profile" class="form-control" style="width:100%;"></div>
			<div class="col-md-6">
				<label class="control-label">Вконтакте</label>
				<input type="text" name="sc[vk_profile]" value="<?=$user['vk_profile'];?>" id="vk_profile" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Одноклассники</label>
				<input type="text" name="sc[od_profile]" value="<?=$user['od_profile'];?>" id="od_profile" class="form-control" style="width:100%;"></div>
			<div class="col-md-6">
				<label class="control-label">Google+</label>
				<input type="text" name="sc[gp_profile]" value="<?=$user['gp_profile'];?>" id="gp_profile" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Twitter</label>
				<input type="text" name="sc[tw_profile]" value="<?=$user['tw_profile'];?>" id="tw_profile" class="form-control" style="width:100%;"></div>
			<div class="col-md-6">
				<label class="control-label">Instagram</label>
				<input type="text" name="sc[in_profile]" value="<?=$user['in_profile'];?>" id="in_profile" class="form-control" style="width:100%;"></div>
		</div>
		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Livejournal</label>
				<input type="text" name="sc[lj_profile]" value="<?=$user['lj_profile'];?>" id="lj_profile" class="form-control" style="width:100%;"></div>
			<div class="col-md-6">
				<label class="control-label">LinkenId</label>
				<input type="text" name="sc[li_profile]" value="<?=$user['li_profile'];?>" class="form-control" style="width:100%;"></div>
		</div>

		<div style="height: 50px;"></div>
		<div class="row">
			<div class="col-md-12">
				<label class="control-label">Группы</label>
				<select class="form-control" style="width:100%;" name="grou" size="1" disabled="">
					<? if($item['group']=='Заказчик') {
							echo '<option value="z">Заказчик</option>
				'; 
						}
						?>
				<option value="Волонтер">Волонтёр</option>
				</select>
			</div>
		</div>

		<center>
			<h3>Изменение пароля</h3>
		</center>

		<div class="row bestmedia-input">
			<div class="col-md-6">
				<label class="control-label">Введите новый пароль</label>
				<input type="text" name="newpassword" class="form-control" style="width:100%;"></div>
			<div class="col-md-6">
				<label class="control-label">Повторите новый пароль</label>
				<input type="text" name="renewpassword" class="form-control" style="width:100%;"></div>
		</div>

		<div style="height: 20px;"></div>
		<div class="row">
				<div class="panel dop_panel" style="margin-left: 15px; margin-right: 15px">
					<div style="color: #333333;background-color: #f5f5f5;border-color: #dddddd; padding: 10px 15px;">Аватар</div>
					<div class="panel-body">
						<div class="form-group">
							<label style="font-weight: bold; display: inline-block;margin-bottom: 5px;font-size: 15px;" for="index_file">Выберите одно изображение для загрузки</label>
							<label style="font-weight: bold; display: inline-block;margin-bottom: 5px;font-size: 15px; float: right;" for="index_file">Текущее фото</label><br >

							<div style="float:right;" class="cropit-image-preview">
								<img src="<?php echo $user['avatar']; ?>">
							</div>

							<div id="image-cropper" style="width: 119px;">
							  <div class="cropit-image-preview"></div>
							  <br>
							  <input type="range" class="cropit-image-zoom-input" />
							  <br>
							  <!-- The actual file input will be hidden -->
							  <input type="file" class="cropit-image-input"/>
							  <input type="hidden" name="uploadfile" id="uploadfile">
							</div>

						<div class="botstrapPoln-stroka">
							Выберите файлы .JPG или .PNG не более 5 мегабайт
							<br>
						</div>
						

					</div>
				</div>
		</div>
		<div>
			<div class="text-center">
				<input type="submit" name="do" onclick="return based64();" value="Сохранить" class="btn btn-success">
			</div>
		</div>

		<script>
			var h = $('#image-cropper').cropit();
			$('.select-image-btn').click(function() {
				$('.cropit-image-input').click();
			});

			function based64() {
				var imageData = h.cropit('export');
				$('#uploadfile').val(imageData);
			};
		</script>
	</form>

</div>