<div id="wrapper">

    <?php $this->load->view('cpanel/sidebar'); ?>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <div class="bestmedia-h5"></div>
            <div class="form-inline" >

                <div class="form-group col-md-5">
                    <?php echo form_open('cpanel/users/search'); ?>
                    <div class="input-group">
                        <input name="q" type="text" class="form-control">
                                <span class="input-group-btn">
                                    <input type="submit"  name="search" value="Поиск" class="btn btn-default"/>
                                </span>
                    </div>
                    </form>
                </div>

                <div class="form-group col-md-5">
                    <?php echo form_open('cpanel/users/sorting',array("id"=>"do_filter")); ?>
                    <select id="statusFilter" name="sorting" class="form_filter form-control">
                        <optgroup label="Сортировать">
                        <option value="last_name__ASC">по фамилии &uarr;</option>
                        <option value="last_name__DESC">по фамилии &darr;</option>
                        </optgroup>
                    </select>
                    <input type="submit" name="do_reset" value="Сбросить"  class="btn btn-danger"/>
                    </form>
                </div>

            </div>


            <div class="clearfix"></div>
            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-add-and-pagination">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <a href="<?=base_url('cpanel/users/add');?>" type="button" class="btn btn-primary">Добавить пользователя</a>
                </div>

            </div>

            <div class="bestmedia-h20"></div>
            <?php echo $message; ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                        <th>Имя</th>
                        <th>Группы</th>
                        <th>Телефон</th>
                        <th>Сети</th>
                        <th>Действия</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($users as $user) {
                            ?>
                            <tr>
                                <td><?=$user->last_name;?> <?=$user->first_name;?></td>
                                <td><?php
                                    if(!empty($user->users_group))
                                    {
                                        echo implode(", ",array_map(function($val){ return $val->description;},$user->users_group));
                                    }
                                    else
                                    {
                                        echo '---';
                                    }
                                    ?></td>
                                <td><?=$user->phone;?></td>
                                <td>
                                    <?php if(!empty($user->vk_profile)){?><a href="<?=$user->vk_profile;?>">ВК</a><?php } ?> 
                                    <?php if(!empty($user->fb_profile)){?><a href="<?=$user->fb_profile;?>">ФБ</a><?php } ?> 
                                    <?php if(!empty($user->od_profile)){?><a href="<?=$user->od_profile;?>">ОК</a><?php } ?> 
                                    <?php if(!empty($user->tw_profile)){?><a href="<?=$user->tw_profile;?>">ТВ</a><?php } ?> 
                                    <?php if(!empty($user->li_profile)){?><a href="<?=$user->li_profile;?>">ЛИ</a><?php } ?> 
                                    <?php if(!empty($user->gp_profile)){?><a href="<?=$user->gp_profile;?>">Г+</a><?php } ?> 
                                    <?php if(!empty($user->in_profile)){?><a href="<?=$user->in_profile;?>">ИГ</a><?php } ?> 
                                    <?php if(!empty($user->lj_profile)){?><a href="<?=$user->lj_profile;?>">ЖЖ</a><?php } ?> 
                                </td>
                                <td class="col-xs-1">
                                    <div class="dropdown">
                                        <i class="status glyphicons <?php echo $user->active ==1?'':'eye_close'; ?> pull-right"></i>
                                        <a class="glyphicons edit pull-right" href="<?=base_url('cpanel/users/edit/'.$user->id);?>"></a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>