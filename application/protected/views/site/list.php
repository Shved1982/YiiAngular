<style>
.actions
{
	width: 25%;
}
.actions a
{
	margin: 0 15px 0 15px;
	text-align: center;
}
.actions a:hover
{
	opacity: 0.5;
}
</style>
<div ng-controller="ListController">
	<h3><?=Yii::t('app','Список клиентов')?></h3>
	<div>
		<a href="#/add" title="<?=Yii::t('app', 'Создать клиента')?>" style="font-size: 14px;"><?=Yii::t('app', 'Создать клиента')?></a>
	</div><bR>
	<table class="table table-hover">
		<thead>
			<tr class="text-uppercase">
				<th>ID</th>
				<th><?=Yii::t('app','ФИО')?></th>
				<th><?=Yii::t('app','Телефон')?></th>
				<th><?=Yii::t('app','Адрес')?></th>
				<th><?=Yii::t('app','Действия')?></th>
			</tr>
			<tr class="text-uppercase">
				<th><?=Yii::t('app','фильтр')?></th>
				<th><input class="form-control" ng-model="search.name"/></th>
				<th><input class="form-control" ng-model="search.phone"/></th>
				<th><input class="form-control" ng-model="search.address"/></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="customer in customers  | pagination:watchPage:maxSizes | filter:search:strict">
				<td>{{customer.id}}</td>
				<td>{{customer.name}}</td>
				<td>{{customer.phone}}</td>
				<td>{{customer.address}}</td>
				<td class="actions">
					<a href="#/view/id/{{customer.id}}" title="<?=Yii::t('app', 'Просмотр')?>"><i class="fa fa-eye fa-2x"></i></a>
					<a href="#/update/id/{{customer.id}}" title="<?=Yii::t('app', 'Редактировать')?>"><i class="fa fa-pencil fa-2x"></i></a>
					<a href="#/view" ng-click="del(customer)" title="<?=Yii::t('app', 'Удалить')?>"><i class="fa fa-trash fa-2x"></i></a>
				</td>
			</tr>
		</tbody>
		
	</table>
	<pagination boundary-links="true" total-items="totalItems" items-per-page="maxSize" ng-model="currentPage" class="pagination-sm" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;"></pagination>
</div>