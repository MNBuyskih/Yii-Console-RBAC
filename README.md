#Yii Console RBAC

Это простое консольное приложение, написаное для Yii, упрощающее работу с RBAC в этом фреймворке.

###Инсталяция

Скопируйте файл в дирректорию `/path/to/your/app/protected/commands`

###Help

Чтобы получить полное описание класса, запустите из консоли:

    $ /path/to/your/app/protected/yiic rbac

##Простой пример использования

Есть три роли:

1.  Гость
1.  Пользователь
1.  Администратор

Предположим, что таблица пользователей (пусть будет `user`) имеет поле `is_admin`. Если значение поля равно 1 - авторизованый пользователь является администратором.

Кроме этого предположим, что вы уже дополнили ваше приложение собственной реализацией класса `CWebUser` и добавили в него метод `getModel`, возвращающий модель текущего авторизованного пользователя.

В соответсвии с этими допущениями составим бизнесправило для администратора (не забывайте, что это должно быть полноценное php-выражение: `return` - в начале, `;` - в конце):

    return !Yii::app()->user->isGuest && Yii::app()->user->model->is_admin == 1;

Безнес правило для пользователя

    return !Yii::app()->user->isGuest;

Безнес правило для гостя

    return Yii::app()->user->isGuest;

Теперь можно добавить все роли в систему:

    $ cd /path/to/your/app/protected
    $ yiic rbac role --name='admin' --description='This is Administrator' --bizRule='return !Yii::app()->user->isGuest && Yii::app()->user->model->is_admin == 1;'
    $ yiic rbac role --name='user' --description='This is simple user' --bizRule='return !Yii::app()->user->isGuest;'
    $ yiic rbac role --name='guest' --description='This is guest' --bizRule='return Yii::app()->user->isGuest;'
