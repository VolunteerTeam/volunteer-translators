<html>
<body>
<h1>Сброс пароля для <?php echo $identity;?></h1>
<p>Пожалуйста перейдите по ссылке для  <?php echo anchor('login/reset_password/'. $forgotten_password_code, 'сброса пароля');?>.</p>
</body>
</html>