<style type="text/css">
    .form-control:focus {
        border-color: #F70707;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(247, 7, 7, 1);
    }
    h1 {
        text-align: center;
    }
    .required {
        color: red;
    }
    .form-control {
        width: 100%;
    }
    input.error {
        border: 1px solid red;
    }
    .text-danger p {
        margin: 0;
        font-size: 12px;
        font-weight: bold;
    }
</style>

<div class="container main" role="main">
    <div class="row">
        <?php echo $this->session->flashdata('verify_msg'); ?>
    </div>
    <div class="row">
        <h1>Регистрация</h1>
        <?php
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
                <label class="control-label">Представление(вкратце о себе) <span class="required">*</span></label>
                <input type="text" name="about" value="<?php echo @$_POST['about']; ?>" class="form-control <?php if(!empty(form_error('about'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('about'); ?></span>
            </div>
            <div class="col-md-6">
                <label class="control-label">Координаты </label>
                <input type="text" name="coordinates" value="<?php echo @$_POST['coordinates']; ?>" class="form-control">
            </div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
                <label class="control-label">Должность(кем работаете) <span class="required">*</span></label>
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
            <div class="col-md-4">
                <label class="control-label">Пол</label>
                <select name="sex" class="form-control" size="1" required="required">
                    <option value="male" <?php if(@$_POST['sex'] == 'male') { echo "selected"; } ?> >Мужской</option>
                    <option value="famale" <?php if(@$_POST['sex'] == 'famale') {echo "selected"; } ?> >Женский</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="control-label">Город <span class="required">*</span></label>
                <input type="text" name="city" value="<?php echo @$_POST['city']; ?>" class="form-control <?php if(!empty(form_error('city'))){echo "error";} ?>">
                <span class="text-danger"><?php echo form_error('city'); ?></span>
            </div>
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
                <label class="control-label">Facebook</label>
                <input type="text" name="fb_profile" value="<?php echo @$_POST['fb_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Вконтакте</label>
                <input type="text" name="vk_profile" value="<?php echo @$_POST['vk_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Одноклассники</label>
                <input type="text" name="od_profile" value="<?php echo @$_POST['od_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Google+</label>
                <input type="text" name="gp_profile" value="<?php echo @$_POST['gp_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Twitter</label>
                <input type="text" name="tw_profile" value="<?php echo @$_POST['tw_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">Instagram</label>
                <input type="text" name="in_profile" value="<?php echo @$_POST['in_profile']; ?>" class="form-control" ></div>
        </div>
        <div class="row bestmedia-input">
            <div class="col-md-6">
                <label class="control-label">Livejournal</label>
                <input type="text" name="lj_profile" value="<?php echo @$_POST['lj_profile']; ?>" class="form-control" ></div>
            <div class="col-md-6">
                <label class="control-label">LinkenId</label>
                <input type="text" name="li_profile" value="<?php echo @$_POST['li_profile']; ?>" class="form-control" ></div>
        </div>
        <div style="height: 50px;"></div>
        <div class="row bestmedia-input">
            <div class="col-md-12">
                <label class="control-label">Группы</label>
                <select class="form-control" name="group" size="1">
                    <option <?php if(@$_POST['group'] == 'Заказчик') { echo "selected"; } ?>>Заказчик</option>
                    <option <?php if(@$_POST['group'] == 'Волонтёр') { echo "selected"; } ?>>Волонтёр</option>
                </select>
            </div>
        </div>
        <div style="height: 30px;"></div>
        <div>
            <div class="text-center">
                <input type="submit" name="submit" value="Сохранить" class="btn btn-success">
            </div>
        </div>
        <?php echo form_close(); ?>
        <?php echo $this->session->flashdata('msg'); ?>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Подключение Datepicker для выбора даты рождения
        var datepicker1 = $("#datepicker1");
        datepicker1.datetimepicker({
            format: 'DD.MM.YYYY',
            minDate: moment().add(-100, 'years'),
            maxDate: moment().add(-10, 'years'),
            viewMode: 'years',
        });
        datepicker1.on('dp.hide',function(){
            setTimeout(function(){
                $('#datepicker1').data('DateTimePicker').viewMode('years');
            },1);
        });

        var dob = '<?php echo @$_POST['dob']; ?>';
        if(!dob){
            datepicker1.data('DateTimePicker').date(null);
        } else {
            datepicker1.data('DateTimePicker').date(dob);
        }

        // Создание маски ввода номера телефона
        var phone_field = $("input[name='phone']");
        phone_field.on('focus',function(){
            if(!$(this).val()){
                $(this).val("+7-")
            }
        });
        phone_field.keypress(function() {
            if (this.value.length >= 16) return false;
            var hyphens = [6,10,13]; // длина ввода, после которой нужно вставить дефис
            var value = $(this).val(); // текущее значение поля Телефон
            if($.inArray(value.length, hyphens) != -1) {
                $(this).val(value+"-");
            }
        });
    })

</script>
