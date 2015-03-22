<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public $layout = '//layouts/main';
		
	public function actionIndex()
	{
		
		/* Наполнение БД
		for($i = 0; $i < 100; $i++)
		{
			$customer = new Customer();
			$customer->name = 'Test Name '.$i;
			$customer->phone = rand(10000000000,99999999999);
			$customer->address = 'Test Address '.$i;
			if($customer->validate())
			{
				$customer->save();
				
				$order = new Order();
				$order->customer_id = $customer->id;
				$order->posted_at = date("Y-m-d H:i:s", strtotime(rand(2014,2015).'-'.rand(1,12).'-'.rand(1,28).' '.rand(1,24).':'.rand(1,60).':'.rand(1,60)));
				$order->amount = rand(25,10000);
				if($i%2 == 0)
				{
					$order->paid_at = $order->posted_at;
				}
				if($order->validate())
				{
					$order->save();
				}
				
				
			}
		}*/
		$this->render('index');
	}
	
	public function actionrenderIndex()
	{
	
		$result = $this->renderPartial('default',NULL, TRUE);
		
		 echo $result;
    }  
	
	public function actionForm()
	{
	
		$result = $this->renderPartial('form',NULL, TRUE);
		
		 echo $result;
    } 

	public function actionView()
	{
	
		$result = $this->renderPartial('view',NULL, TRUE);
		
		 echo $result;
    } 	
	
	public function actionList()
	{
		
		$result = $this->renderPartial('list',NULL, TRUE);
		
		 echo $result;
    }  
	
	public function actiongetList()
	{
		$customers = Customer::model()->with('order')->findAll();
		
		$result = array();
		foreach($customers as $customer)
		{
			$date_paid = '';
			if(!empty($customer->order->paid_at))
			{
				$date_paid = date("d.m.Y H:i:s", strtotime($customer->order->paid_at));
			}
			$result[] = array(
				'id' => $customer->id,
				'name' => $customer->name,
				'phone' => $customer->phone,
				'address' => $customer->address,
				'order_id' => $customer->order->id,
				'order_amount' => $customer->order->amount,
				'order_posted_at' =>  date("d.m.Y H:i:s", strtotime($customer->order->posted_at)),
				'order_paid_at' =>  $date_paid
			);
		}
		
		 echo  CJSON::encode($result);
    }  
	
	public function actiongetCustomer()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		$customerId = $params['data'];
		
		$customer = Customer::model()->findByPk($customerId);
		
		$result[] = array(
			'id' => $customer->id,
			'name' => $customer->name,
			'phone' => $customer->phone,
			'address' => $customer->address,
			);
		
		 echo  CJSON::encode($result);
    }  
	
	public function actiongetOrders()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		$customerId = $params['data']['id'];
		
		$criteria = new CDbCriteria();
		$criteria->condition = 'customer_id = :customer_id';
		$criteria->params = array('customer_id' => $customerId);
		
		$orders = Order::model()->findAll($criteria);
		
		$result = array();
		
		foreach($orders as $order)
		{	
			$date_paid = '';
			if($order->paid_at == NULL)
			{
				$date_paid = date("d.m.Y H:i:s", strtotime($order->paid_at));
			}
			$result[] = array(
				'id' => $order->id,
				'amount' => $order->amount,
				'posted_at' => date("d.m.Y H:i:s", strtotime($order->posted_at)),
				'paid_at' =>  $date_paid
				);
		}
		
		 echo  CJSON::encode($result);
    }  
	
	
	public function actionaddCustomer()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$error = TRUE;
		$errors = array();
		
		$customer = new Customer();
		$customer->name = $params['data']['name'];
		$customer->phone = $params['data']['phone'];
		$customer->address = $params['data']['address'];
		if($customer->validate())
		{
			$customer->save();
		}
		
		$errors['customer'] = $customer->getErrors();
		$errors['id'] = $customer->id;
		
		 echo CJSON::encode($errors);
    }
	
	public function actionupdateCustomer()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$error = TRUE;
		$errors = array();
		
		$customer = Customer::model()->findByPk($params['data']['id']);
		$customer->name = $params['data']['name'];
		$customer->phone = $params['data']['phone'];
		$customer->address = $params['data']['address'];
		if($customer->validate())
		{
			if(!$customer->save())
			{
				throw new CHttpException('403', 'Ошибочный запрос, не удалось обновить клиента.');
			}
		}
		else
		{
			throw new CHttpException('403', 'Ошибочный запрос, не удалось обновить клиента.');
		}
		
		$errors['customer'] = $customer->getErrors();
		
		 echo CJSON::encode($errors);
    }
	
	public function actiondeleteCustomer()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$errors = array();
		$customerId = $params['data'];

		$criteria = new CDbCriteria();
		$criteria->condition = 'customer_id = :customer_id';
		$criteria->params = array('customer_id' => $customerId);
		
		Order::model()->deleteAll($criteria);
		Customer::model()->deleteByPk($customerId);
			
		 echo CJSON::encode($errors);
    }
	
	public function actioncreateOrder()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$error = TRUE;
		$errors = array();
		
		$order = new Order();
		$order->customer_id = $params['data']['customer_id'];
		$order->amount = $params['data']['amount'];
		$order->posted_at = date("Y-m-d H:i:s", strtotime($params['data']['posted_at']));
		$order->paid_at = date("Y-m-d H:i:s", strtotime($params['data']['paid_at']));
		if($order->validate())
		{
			if(!$order->save())
			{
				throw new CHttpException('403', 'Ошибочный запрос, не удалось создать заказ.');
			}
		}
		
		$date_paid = '';
		if(array_key_exists('paid_at', $params['data']))
		{
			$date_paid = date("d.m.Y H:i:s", strtotime($order->paid_at));
		}
		$result = array(
			'id' => $order->id,
			'amount' => $order->amount,
			'posted_at' => date("d.m.Y H:i:s", strtotime($order->posted_at)),
			'paid_at' =>  $date_paid
		);
		$errors['order'] = $order->getErrors();
		$errors['model'] = $result;
		
		 echo CJSON::encode($errors);
    }
	
	public function actionupdateOrder()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$error = TRUE;
		$errors = array();
		
		$order = Order::model()->findByPk($params['data']['id']);
		$order->customer_id = $params['data']['customer_id'];
		$order->amount = $params['data']['amount'];
		$order->posted_at = date("Y-m-d H:i:s", strtotime($params['data']['posted_at']));
		$order->paid_at = date("Y-m-d H:i:s", strtotime($params['data']['paid_at']));
		if($order->validate())
		{
			if(!$order->save())
			{
				throw new CHttpException('403', 'Ошибочный запрос, не удалось обновить заказ.');
			}
		}
		
		$errors['order'] = $order->getErrors();
		
		 echo CJSON::encode($errors);
    }
	
	public function actiondeleteOrder()
	{
		if(empty(Yii::app()->request->csrfToken))
		{
			throw new CHttpException('403', 'Ошибочный запрос, отказано в доступе.');
		}
		$params = CJSON::decode(file_get_contents('php://input'), true);
		
		$errors = array();
		$id = $params['data'];
		
		Order::model()->deleteByPk($id);
					
		 echo CJSON::encode($errors);
    }
	
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	
}