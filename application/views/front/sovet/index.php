<div class="container main" role="main">

    <h1>Совет сообщества</h1>
    
    <p>Совет сообщества принимает ключевые решения, в основном, входящие в <a href="/ustav">устав</a>.</p>
    
    <p>&nbsp;</p>

        <?php if(!empty($items)) { 
            foreach ($items as $item) { ?>
                <a href="/<?=$item->username?>" class="vcard">
                    <table width="100%" border="0">
                        <tr>
                            <td width="100" align="center" valign="top">
                                <?php if(!empty($item->avatar)){echo '<img style="margin:10px" width="80" src="/content/'.$item->avatar->filename.'" />';}?>
                            </td>
                            <td width="180" valign="top">
                                <p class="vcard-name"><?=$item->last_name;?> <?=$item->first_name;?></p>
                                <p class="vcard-title"><?=$item->job_post;?></p>
                                <p class="vcard-phone"><?=$item->phone;?></p>
                                <p class="vcard-email"><?=$item->email;?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </a>

            <?php }
        } ?>

</div>