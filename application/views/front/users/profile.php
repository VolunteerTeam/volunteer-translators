<div class="container main" role="main">
    <? if(isset($user)) {?>
        <div style="float:left;padding-top:10px;margin-right:10px;">
            <img alt="" src="<?= $user->avatar ?>">
        </div>
        <div class="profile_info" style="padding-top:10px;float:left;">
            <span style="font-weight:bold;font-size:18px;"><?= $user->last_name ?> <?= $user->first_name ?> <?= $user->patro_name ?></span><br/>
            <?php
                if(!empty($user->groups)){
                    for($i = 0; $i < count($user->groups); $i++) {
                        echo $user->groups[$i]->description;
                        if($i < count($user->groups)-1) {
                            echo ",";
                        }
                        echo " ";
                    }
                }
            ?>
            <p>
                <span>E-mail:</span> <a href="mailto:<?= $user->email ?>"><?= $user->email ?></a><br/>
                <span>Телефон:</span> <?= $user->phone ?><br/>
                <span>Город:</span> <?= $user->city["location"] ?></span><br/>
            </p>
        </div>
        <div class="profile_info" style="clear:both;">
            <span>День рождения:</span> <?php if($user->dob) echo date("d.m.Y",strtotime($user->dob)); ?></span><br/>
            <span>О себе:</span> <?= $user->about ?></span><br/>
            <span>Должность:</span> <?= $user->job_post ?><br/>
        </div>
        <div class="profile_info" style="margin-top:10px;">
            <span>Профиль пользователя в социальных сетях:</span><br/>
            Skype: <?= $user->skype ?><br/>
            Вконтакте: <a href="<?= $user->vk_profile ?>"><?= $user->vk_profile ?></a><br/>
            Facebook: <a href="<?= $user->fb_profile ?>"><?= $user->fb_profile ?></a><br/>
            Одноклассники: <a href="<?= $user->od_profile ?>"><?= $user->od_profile ?></a><br/>
            Twitter: <a href="<?= $user->tw_profile ?>"><?= $user->tw_profile ?></a><br/>
            LinkedIn: <a href="<?= $user->li_profile ?>"><?= $user->li_profile ?></a><br/>
            Google+: <a href="<?= $user->gp_profile ?>"><?= $user->gp_profile ?></a><br/>
            Instagram: <a href="<?= $user->in_profile ?>"><?= $user->in_profile ?></a><br/>
            LiveJournal: <a href="<?= $user->lj_profile ?>"><?= $user->lj_profile ?></a><br/>
        </div>


    <? } ?>
</div>