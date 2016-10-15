<div class="container main" role="main">

    <div class="row" style="margin: 50px 0; border: 1px solid #ddd; border-radius: 10px; ">
        <div class="col-md-3">
            <?php if(!empty($item->avatar)){echo '<img class="img-thumbnail" style="margin-top: 30px" width="300" src="/content/'.$item->avatar->filename.'" />';}?>
            <p>
                <?php if(!empty($item->skype))     { echo '<a href="'.$item->skype.'?chat"><i class="si si-sk"></i></a> ';}  ?>
                <?php if(!empty($item->vk_profile)){ echo '<a href="'.$item->vk_profile.'" target="_blank"><i class="si si-vk"></i></a> ';}?>
                <?php if(!empty($item->fb_profile)){ echo '<a href="'.$item->fb_profile.'" target="_blank"><i class="si si-fb"></i></a> ';}?>
                <?php if(!empty($item->gp_profile)){ echo '<a href="'.$item->gp_profile.'" target="_blank"><i class="si si-gp"></i></a> ';}?>
                <?php if(!empty($item->od_profile)){ echo '<a href="'.$item->od_profile.'" target="_blank"><i class="si si-ok"></i></a> ';}?>
                <?php if(!empty($item->in_profile)){ echo '<a href="'.$item->in_profile.'" target="_blank"><i class="si si-ig"></i></a> ';}?>
                <?php if(!empty($item->tw_profile)){ echo '<a href="'.$item->tw_profile.'" target="_blank"><i class="si si-tw"></i></a> ';}?>
                <?php if(!empty($item->li_profile)){ echo '<a href="'.$item->li_profile.'" target="_blank"><i class="si si-li"></i></a> ';}?>
            </p>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-9">
            <p style="font-size:200%; margin: 20px 0"><?=$item->first_name;?> <?=$item->last_name;?></p>
            <p style="font-size:150%; margin: 20px 0"><?=$item->job_post;?></p>
            <?php if(!empty($item->intro)){ echo '<p style="font-size:100%; margin: 20px 0">'.$item->intro.'</p>';}  ?>
            <?php if(!empty($item->email)){ echo '<p style="font-size:100%; margin: 20px 0"> <a href="mailto:'.$item->email.'">'.$item->email.'</a></p>';}  ?>
            <?php if(!empty($item->phone)){ echo '<p style="font-size:100%; margin: 20px 0">'.$item->phone.'</p>';}  ?>
        </div>
    </div>
        
</div>
<div class="clearfix"></div>