<html>
<body>
<h1>Активация аккаунта для <?php echo $identity;?></h1>
<p>Пожалуйста перейдите по ссылке  <?php echo anchor('register/activate/'. $id .'/'. $activation, 'для активации учетной записи');?>.</p>
</body>
</html>