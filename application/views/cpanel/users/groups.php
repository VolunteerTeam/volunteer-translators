<div id="wrapper">

    <?php $this->load->view('cpanel/sidebar'); ?>

    <!-- Page content -->
    <div id="page-content-wrapper">
        <?php $this->load->view('cpanel/breadcrumbs'); ?>
        <div class="page-content inset">
            <div class="bestmedia-h5"></div>
            <div class="row">
                <div class="col-md-5">
                    <div class="input-group">
                        <?php echo form_open(); ?>
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Поиск</button>
                        </span>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bestmedia-h20"></div>
            <div class="row bestmedia-add-and-pagination">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <a href="<?=base_url('cpanel/users/add_group');?>" type="button" class="btn btn-primary">Добавить группу</a>
                </div>

            </div>
            <div class="bestmedia-h20"></div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped bestmedia-table">
                        <thead>
                        <th>Название</th>
                        <th>Действия</th>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($groups as $group) {
                            ?>
                            <tr>
                                <td><?=$group->description;?></td>
                                <td class="col-xs-1">
                                    <div class="dropdown">
                                        <a  class="glyphicons edit pull-right" href="<?=base_url('cpanel/users/edit_group/'.$group->id);?>"></a>
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