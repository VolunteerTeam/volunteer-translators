<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>РЕН-ТВ - бегущая строка</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
</head>
<body>
    <?
        if(isset($data))
        {
            $this->load->view('mail_templates/'.$tpl,$data);
        } else {
            $this->load->view('mail_templates/'.$tpl);
        }
    ?>
</body>
</html>