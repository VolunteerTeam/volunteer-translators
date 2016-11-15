<div class="container main" role="main">
	<div class="row">
		<?php echo $this->session->flashdata('verify_msg'); ?>
	</div>
	<div class="row user_form">
		<h1>Профиль</h1>
		<?php
			echo $this->session->flashdata('msg');
			$attributes = array("name" => "profile_form", "enctype" => "multipart/form-data");
			echo form_open("user/profile/save", $attributes);
		?>
			<div class="row bestmedia-input">
				<div class="col-md-4">
					<label class="control-label">Фамилия <span class="required">*</span></label>
					<input type="text" name="last_name" value="<?=$user['last_name'];?>" class="form-control <?php if(!empty(form_error('last_name'))){echo "error";} ?>">
					<span class="text-danger"><?php echo form_error('last_name'); ?></span>
				</div>
				<div class="col-md-4">
					<label class="control-label">Имя <span class="required">*</span></label>
					<input type="text" name="first_name" value="<?=$user['first_name'];?>" class="form-control <?php if(!empty(form_error('first_name'))){echo "error";} ?>">
					<span class="text-danger"><?php echo form_error('first_name'); ?></span>
				</div>
				<div class="col-md-4">
					<label class="control-label">Отчество</label>
					<input type="text" name="patro_name" value="<?=$user['patro_name'];?>" class="form-control">
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Представление (вкратце о себе) <span class="required">*</span></label>
					<input type="text" name="about" value="<?=$user['about'];?>" class="form-control <?php if(!empty(form_error('about'))){echo "error";} ?>">
					<span class="text-danger"><?php echo form_error('about'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">Должность (кем работаете) <span class="required">*</span></label>
					<input type="text" name="job_post" value="<?=$user['job_post'];?>" class="form-control <?php if(!empty(form_error('job_post'))){echo "error";} ?>">
					<span class="text-danger"><?php echo form_error('job_post'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-4">
					<label class="control-label">Дата рождения <span class="required">*</span></label>
					<div class='input-group date' id='datepicker1'>
						<input type='text' class="form-control <?php if(!empty(form_error('dob'))){echo "error";} ?>" name="dob" value="<?=$user['dob'];?>"/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
					<span class="text-danger"><?php echo form_error('dob'); ?></span>
				</div>
				<div class="col-md-3">
					<label class="control-label">Пол <span class="required">*</span></label>
					<div class="btn-group <?php if(!empty(form_error('sex_type'))){echo "error";} ?>" data-toggle="buttons" style="display:block;">
						<label class="btn btn-default <?php if($user['sex_type'] == '1') { echo "active"; } ?>" style="width:50%">
							<input type="radio" name="sex" value="1"  <?php if($user['sex'] == '1') { echo "checked"; } ?>/> мужской
						</label>
						<label class="btn btn-default <?php if($user['sex_type'] == '2') { echo "active"; } ?>" style="width:50%">
							<input type="radio" name="sex" value="2"  <?php if($user['sex'] == '2') { echo "checked"; } ?>/> женский
						</label>
					</div>
					<span class="text-danger"><?php echo form_error('sex_type'); ?></span>
				</div>
				<div class="col-md-5">
					<label class="control-label">Город <span class="required">*</span></label>
					<input type="text" name="city" value="<?=$user['city']['location'];?>" class="form-control <?php if(!empty(form_error('city'))){echo "error";} ?>">
					<input id="country" name="country" type="text" value="<?=$user['city']['country'];?>" hidden/>
					<input id="country_short" name="country_short" type="text" value="<?=$user['city']['country_short'];?>" hidden/>
					<input id="latlng" name="latlng" type="text" value="<?=$user['city']['latlng'];?>" hidden/>
					<input id="place_id" name="place_id" type="text" value="<?=$user['city']['place_id'];?>" hidden/>
					<span class="text-danger" id="city-error"><?php echo form_error('city'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input" id="map_container">
				<div id="map_canvas"></div><br/>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-4">
					<label class="control-label">Сотовый телефон <span class="required">*</span></label>
					<input type="text" name="phone" value="<?=$user['phone'];?>" placeholder="+7-ххх-ххх-хх-хх" class="form-control <?php if(!empty(form_error('phone'))){echo "error";} ?>" max="16">
					<span class="text-danger"><?php echo form_error('phone'); ?></span>
				</div>
				<div class="col-md-4">
					<label class="control-label">Электронная почта <span class="required">*</span></label>
					<?php if($user['email'] == 'social_profile') { ?>
							<input type="text" name="email" value="" class="form-control">
					<?php } else { ?>
							<input type="text" value="<?= $user['email'] ?>" class="form-control" disabled>
					<?php }?>
				</div>
				<div class="col-md-4">
					<label class="control-label">Скайп</label>
					<input type="text" name="skype" value="<?=$user['skype'];?>" class="form-control" >
				</div>
			</div>
			<div style="height: 50px;"></div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<input type="text" name="soc_profiles" value="" hidden>
					<span class="text-danger"><?php echo form_error('soc_profiles'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Facebook</label>
					<input type="text" name="fb_profile" value="<?=$user['fb_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('fb_profile'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">Вконтакте</label>
					<input type="text" name="vk_profile" value="<?=$user['vk_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('vk_profile'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Одноклассники</label>
					<input type="text" name="od_profile" value="<?=$user['od_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('od_profile'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">Google+</label>
					<input type="text" name="gp_profile" value="<?=$user['gp_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('gp_profile'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Twitter</label>
					<input type="text" name="tw_profile" value="<?=$user['tw_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('tw_profile'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">Instagram</label>
					<input type="text" name="in_profile" value="<?=$user['in_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('in_profile'); ?></span>
				</div>
			</div>
			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Livejournal</label>
					<input type="text" name="lj_profile" value="<?=$user['lj_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('lj_profile'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">LinkenId</label>
					<input type="text" name="li_profile" value="<?=$user['li_profile'];?>" class="form-control" >
					<span class="text-danger"><?php echo form_error('li_profile'); ?></span>
				</div>
			</div>
			<div style="height: 20px;"></div>
			<div class="row bestmedia-input">
				<div class="col-md-12">
					<?php if(empty($user['groups'])){ ?>
						<p><b>Ваша роль <span class="required">*</span>:</b></p>
						<div class="btn-group <?php if(!empty(form_error('group'))){echo "error";} ?>" data-toggle="buttons">
							<label class="btn btn-default <?php if(@$_POST['group'] == '7') { echo "active"; } ?>">
								<input type="radio" data-href="/ustav#client" name="group" value="7"  <?php if(@$_POST['group'] == '7') { echo "checked"; } ?>/> Заказчик
							</label>
							<label class="btn btn-default <?php if(@$_POST['group'] == '4') { echo "active"; } ?>">
								<input type="radio" data-href="/ustav#translator" name="group" value="4"  <?php if(@$_POST['group'] == '4') { echo "checked"; } ?>/> Волонтёр
							</label>
							<label class="btn btn-default <?php if(@$_POST['group'] == '3') { echo "active"; } ?>">
								<input type="radio" data-href="/ustav#manager" name="group" value="3"  <?php if(@$_POST['group'] == '3') { echo "checked"; } ?>/> Менеджер
							</label>
						</div>
						<span class="text-danger"><?php echo form_error('group'); ?></span>
					<?php } else { ?>
						<p><b>Ваша роль: </b>
							<?php
							foreach($user['groups'] as $key => $group){
								echo $group['description'];
								if($key != (count($user['groups']) - 1)) echo ", ";
							}
							?>
						</p>
					<?php }?>

				</div>
			</div>

			<h3>Изменение пароля</h3>

			<div class="row bestmedia-input">
				<div class="col-md-6">
					<label class="control-label">Введите новый пароль</label>
					<div id="mypass-wrap" class="control-group">
						<input type="password" name="password" value="" class="form-control <?php if(!empty(form_error('password'))){echo "error";} ?>">
					</div>
					<span class="text-danger"><?php echo form_error('password'); ?></span>
				</div>
				<div class="col-md-6">
					<label class="control-label">Повторите новый пароль</label>
					<div id="mypass-wrap" class="control-group">
						<input type="password" name="cpassword" value="" class="form-control <?php if(!empty(form_error('cpassword'))){echo "error";} ?>">
					</div>
					<span class="text-danger"><?php echo form_error('cpassword'); ?></span>
				</div>
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
				<span style="color:red;font-weight:bold;">Извините, сохранение профиля пока не работает</span>
				<div>
					<div class="text-center">
						<input type="submit" name="do" onclick="return based64();" value="Сохранить" class="btn btn-success" disabled>
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
		<?php echo form_close(); ?>
	</div>
</div>

<?php require_once APPPATH."views/front/users/user_form_js.php"; ?>