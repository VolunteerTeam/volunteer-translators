<div class="container main" role="main">
    <a href="#" id="extended_search">Расширенный поиск&nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a>
    <div class="filtering">
        <form class="form-inline">
            <table class="table">
                <tbody>
                <tr>
                    <td>Дата создания заказа с:</td>
                    <td>
                        <div class='input-group date' id='datepicker1'>
                            <input type='text' class="form-control" name="dob" value=""/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
                        </div>
                        <label class="control-label">по:</label>
                        <div class='input-group date' id='datepicker2'>
                            <input type='text' class="form-control" name="dob" value=""/>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Перевод c:</td>
                    <td>
                        <select name="language_in" class="form-control">
                            <option value="">Выберите язык...</option>
                            <?php if(isset($languages) && !empty($languages)){
                                foreach($languages as $value){
                                    echo "<option value='".$value->code."'>".$value->name_ru."</option>";
                                }
                            }?>
                        </select>
                        <label class="control-label">на:</label>
                        <select name="language_out" class="form-control">
                            <option value="">Выберите язык...</option>
                            <?php if(isset($languages) && !empty($languages)){
                                foreach($languages as $value){
                                    echo "<option value='".$value->code."'>".$value->name_ru."</option>";
                                }
                            }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Номер заказа:</td>
                    <td><input type="text" class="form-control" id="exampleInputName2" placeholder=""></td>
                </tr>
                <tr>
                    <td>Заказчик:</td>
                    <td><input type="text" class="form-control" id="exampleInputName2" placeholder=""></td>
                </tr>
                <tr>
                    <td>Менеджер:</td>
                    <td><input type="text" class="form-control" id="exampleInputName2" placeholder=""></td>
                </tr>
                <tr>
                    <td>Поиск:</td>
                    <td><input type="text" class="form-control" id="exampleInputName2" placeholder="поиск по полям Назначение и Получатель перевода"></td>
                </tr>
                </tbody>
            </table>
            <button type="submit" id="LoadRecordsButton" class="btn btn-success">Поиск</button>
        </form>
    </div>
    <div id="ordersTable"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#extended_search").click(function(){
            var _this = $(this);
            if(_this.hasClass("show")){
                _this.removeClass("show").html('Расширенный поиск&nbsp;&nbsp;<i class="fa fa-chevron-right"></i>');
                $(".filtering").slideUp();
            } else {
                _this.addClass("show").html('Расширенный поиск&nbsp;&nbsp;<i class="fa fa-chevron-down"></i>');
                $(".filtering").slideDown();
            }
        })
    })
</script>