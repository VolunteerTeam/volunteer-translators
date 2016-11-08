<div class="container main" role="main">
    <div class="row">
        <?php echo $this->session->flashdata('verify_msg'); ?>
    </div>
    <div class="row user_form">
        <h1>Регистрация</h1>
        <?php
            if(isset($email_msg)){
                echo $email_msg;
            }
            echo $this->session->flashdata('msg');
            $attributes = array("name" => "reg_form");
            echo form_open("user/register", $attributes);
        ?>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Фамилия <span class="required">*</span></label>
                <input type="text" name="last_name" value="<?php echo @$_POST['last_name']; ?>" class="form-control <?php if(!empty(form_error('last_name'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('last_name'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Имя <span class="required">*</span></label>
                <input type="text" name="first_name" value="<?php echo @$_POST['first_name']; ?>" class="form-control <?php if(!empty(form_error('first_name'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('first_name'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Отчество</label>
                <input type="text" name="patro_name" value="<?php echo @$_POST['patro_name']; ?>" class="form-control">
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Электронная почта <span class="required">*</span></label>
                <input type="text" name="email" value="<?php echo @$_POST['email']; ?>" class="form-control <?php if(!empty(form_error('email'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('email'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Пароль <span class="required">*</span></label>
                <div id="mypass-wrap" class="control-group">
                    <input type="password" name="password" value="" placeholder="пароль" class="form-control <?php if(!empty(form_error('password'))){echo "error";} ?>">
                </div>
                <span class="text-danger"><?php echo form_error('password'); ?></span>
            </div>
            <div class="col-md-4">
                <label class="control-label">Повторите пароль <span class="required">*</span></label>
                <div id="mypass-wrap" class="control-group">
                    <input type="password" name="cpassword" value="" placeholder="пароль" class="form-control <?php if(!empty(form_error('cpassword'))){echo "error";} ?>">
                </div>
                <span class="text-danger"><?php echo form_error('cpassword'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Представление (вкратце о себе) <span class="required">*</span></label>
                <input type="text" name="about" value="<?php echo @$_POST['about']; ?>" class="form-control <?php if(!empty(form_error('about'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('about'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Должность (кем работаете) <span class="required">*</span></label>
                <input type="text" name="job_post" value="<?php echo @$_POST['job_post']; ?>" class="form-control <?php if(!empty(form_error('job_post'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('job_post'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-4">
                <label class="control-label">Дата рождения <span class="required">*</span></label>
                <div class='input-group date' id='datepicker1'>
                    <input type='text' class="form-control <?php if(!empty(form_error('dob'))){echo "error";} ?>" name="dob" value="<?php echo @$_POST['dob']; ?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <span class="text-danger"><?php echo form_error('dob'); ?></span>
            </div>
            <div class="col-md-3">
                <label class="control-label">Пол <span class="required">*</span></label>
                <div class="btn-group <?php if(!empty(form_error('sex'))){echo "error";} ?>" data-toggle="buttons" style="display:block;">
                    <label class="btn btn-default <?php if(@$_POST['sex'] == '1') { echo "active"; } ?>" style="width:50%">
                        <input type="radio" name="sex" value="1"  <?php if(@$_POST['sex'] == '1') { echo "checked"; } ?>/> мужской
                    </label>
                    <label class="btn btn-default <?php if(@$_POST['sex'] == '2') { echo "active"; } ?>" style="width:50%">
                        <input type="radio" name="sex" value="2"  <?php if(@$_POST['sex'] == '2') { echo "checked"; } ?>/> женский
                    </label>
                </div>
                <span class="text-danger"><?php echo form_error('sex'); ?></span>
                <?php
                /*<select name="sex" class="form-control <?php if(!empty(form_error('sex'))){echo "error";} ?>" size="1">
                    <option value="">------------</option>
                    <option value="1" <?php if(@$_POST['sex'] == '1') { echo "selected"; } ?> >Мужской</option>
                <option value="2" <?php if(@$_POST['sex'] == '2') {echo "selected"; } ?> >Женский</option>
                </select>
                <span class="text-danger"><?php echo form_error('sex'); ?></span>*/
                ?>
            </div>
            <div class="col-md-5">
                <label class="control-label">Город <span class="required">*</span></label>
                <input type="text" name="city" value="<?php echo @$_POST['city']; ?>" class="form-control <?php if(!empty(form_error('city'))){echo "error";} ?>">
                <input id="country" name="country" type="text" value="<?php echo @$_POST['country']; ?>" hidden/>
                <input id="country_short" name="country_short" type="text" value="<?php echo @$_POST['country_short']; ?>" hidden/>
                <input id="latlng" name="latlng" type="text" value="<?php echo @$_POST['latlng']; ?>" hidden/>
                <input id="place_id" name="place_id" type="text" value="<?php echo @$_POST['place_id']; ?>" hidden/>
                <span class="text-danger" id="city-error"><?php echo form_error('city'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input" id="map_container">
            <div id="map_canvas"></div><br/>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Сотовый телефон <span class="required">*</span></label>
                <input type="text" name="phone" value="<?php echo @$_POST['phone']; ?>" placeholder="+7-ххх-ххх-хх-хх" class="form-control <?php if(!empty(form_error('phone'))){echo "error";} ?>" max="16">
                <span class="text-danger"><?php echo form_error('phone'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Скайп</label>
                <input type="text" name="skype" value="<?php echo @$_POST['skype']; ?>" class="form-control" >
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
                <input type="text" name="fb_profile" value="<?php echo @$_POST['fb_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('fb_profile'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Вконтакте</label>
                <input type="text" name="vk_profile" value="<?php echo @$_POST['vk_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('vk_profile'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Одноклассники</label>
                <input type="text" name="od_profile" value="<?php echo @$_POST['od_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('od_profile'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Google+</label>
                <input type="text" name="gp_profile" value="<?php echo @$_POST['gp_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('gp_profile'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Twitter</label>
                <input type="text" name="tw_profile" value="<?php echo @$_POST['tw_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('tw_profile'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Instagram</label>
                <input type="text" name="in_profile" value="<?php echo @$_POST['in_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('in_profile'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Livejournal</label>
                <input type="text" name="lj_profile" value="<?php echo @$_POST['lj_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('lj_profile'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">LinkenId</label>
                <input type="text" name="li_profile" value="<?php echo @$_POST['li_profile']; ?>" class="form-control" >
                <span class="text-danger"><?php echo form_error('li_profile'); ?></span>
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
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
                <?php
                /*<select class="form-control" name="group" size="1">
                    <option value="7" <?php //if(@$_POST['group'] == 'Заказчик') { echo "selected"; } ?>>Заказчик</option>
                    <option value="4" <?php //if(@$_POST['group'] == 'Волонтёр') { echo "selected"; } ?>>Волонтёр</option>
                </select>*/
                ?>

            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
                <div class="checkbox">
                    <label><input type="checkbox" name="agreement" value="agreement" checked>Нажимая кнопку «Зарегистрироваться», я принимаю условия <a id="agreement_link" href="/ustav" target="_blank">Пользовательского соглашения</a> и даю своё согласие на обработку моих персональных данных, в соответствии с Федеральным законом от 27.07.2006 года №152-ФЗ «О персональных данных».</label>
                </div>
                <span class="text-danger"><?php echo form_error('agreement'); ?></span>
            </div>
        </div>
        <div style="height: 30px;"></div>
        <div>
            <div class="text-center">
                <input type="submit" name="submit" value="Зарегистрироваться" class="btn btn-success">
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php require_once "user_form_js.php"; ?>
