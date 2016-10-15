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
                        <input name="q" type="text" value="<?=$query;?>" class="form-control">
                                <span class="input-group-btn">
                                    <input type="submit"  name="search" value="Поиск" class="btn btn-default"/>
                                </span>
                    </div>
                    </form>
                </div>
            </div>


            <div class="clearfix"></div>

            <div class="bestmedia-h20"></div>
            <?php echo $message; ?>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped bestmedia-table">
                        <thead>
                        <th>Название</th>
                        <th>Группа и права доступа</th>
                        <th>Дата добавления</th>
                        <th>Телефон</th>
                        <th>Социальные сети</th>
                        <th>Действия</th>
                        </thead>
                        <tbody>
                        <?php

                        foreach ($users as $user) {

                            ?>
                            <tr>
                                <td><?=$user->username;?></td>
                                <td><?php
                                    if(!empty($user->users_group))
                                    {
                                        echo implode(", ",array_map(function($val){ return $val->description;},$user->users_group));
                                    }
                                    else
                                    {
                                        echo 'Группа не назначена';
                                    }
                                    ?></td>
                                <td><?=date('d.m.Y',$user->created_on);?></td>
                                <td><?=$user->phone;?></td>
                                <td>
                                    <?php if(!empty($user->vk_profile)){?>
                                        <a href="<?=$user->vk_profile;?>">ВК</a>
                                    <?php } ?>
                                    <?php if(!empty($user->fb_profile)){?>
                                        <a href="<?=$user->fb_profile;?>">ФБ</a>
                                    <?php } ?>
                                    <?php if(!empty($user->od_profile)){?>
                                        <a href="<?=$user->od_profile;?>">ОК</a>
                                    <?php } ?>
                                    <?php if(!empty($user->tw_profile)){?>
                                        <a href="<?=$user->tw_profile;?>">Тв</a>
                                    <?php } ?>
                                    <?php if(!empty($user->li_profile)){?>
                                        <a href="<?=$user->li_profile;?>">Li</a>
                                    <?php } ?>
                                    <?php if(!empty($user->gp_profile)){?>
                                        <a href="<?=$user->gp_profile;?>">Г+</a>
                                    <?php } ?>
                                    <?php if(!empty($user->in_profile)){?>
                                        <a href="<?=$user->in_profile;?>">Inst</a>
                                    <?php } ?>
                                    <?php if(!empty($user->lj_profile)){?>
                                        <a href="<?=$user->lj_profile;?>">ЖЖ</a>
                                    <?php } ?>
                                </td>
                                <td class="col-xs-1">
                                    <div class="dropdown">
                                        <i class="status glyphicons <?php echo $user->active ==1?'':'eye_close'; ?> pull-right"></i>
                                        <a class="glyphicons edit pull-right" href="<?=base_url('cpanel/users/edit/'.$user->user_id);?>"></a>
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