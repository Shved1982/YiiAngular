<style>
.content_content
{
	height: 1000px;
}
.box
{
	display: inline-block;
	border: 1px solid;
	border-color: grey;
	margin: 0 50px 0 50px;
	padding: 75px;
	text-align: center;
	font-size: 16px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 2px;
}
.box:hover
{
	box-shadow:0 0 15px #000000;
	border:1px solid #000000;
	cursor: pointer;
}
.box:active
{
	box-shadow:0 0 5px #000000;
	border:1px solid #000000;
	cursor: pointer;
}
.main
{
	margin: 50px;
}


</style>
<div class="main">
	<a href="#/add" ><div class="box"><i class="fa fa-plus fa-4x"></i><br><br><?=Yii::t('app','Добавить клииента')?></div></a>
	<a href="#/view"><div class="box"><i class="fa fa-list fa-4x"></i><br><br><?=Yii::t('app','Список клиентов')?></div></a>
	
	<br><br>
	
	
</div>

