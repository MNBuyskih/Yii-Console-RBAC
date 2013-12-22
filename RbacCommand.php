<?php
/**
 * RbacCommand.php
 * Date: 27.09.13
 * Time: 13:10
 *
 * @author  M.N.B. <buyskih@gmail.com>
 * @package convergator
 */

/**
 * Class RbacCommand
 */
class RbacCommand extends CConsoleCommand {
    public function actionIndex() {
        echo "
This is console application for create and manage rbac in your application.

Examples:
    \$yiic rbac operation --name=\"\" [--description=\"\"] [--bizRule=\"\"] [--data=\"\"]
        Creates an operation.
        - name        the operation name
        - description the operation description
        - bizRule     the business rule associated with this operation
        - data        additional data to be passed when evaluating the
                      business rule

    \$yiic rbac task --name=\"\" [--description=\"\"] [--bizRule=\"\"] [--data=\"\"]
        Creates a task.
        - name        the task name
        - description the task description
        - bizRule     the business rule associated with this task
        - data        additional data to be passed when evaluating the
                      business rule

    \$yiic rbac role --name=\"\" [--description=\"\"] [--bizRule=\"\"] [--data=\"\"]
        Creates a role.
        - name        the role name
        - description the role description
        - bizRule     the business rule associated with this role
        - data        additional data to be passed when evaluating the
                      business rule

    \$yiic rbac child --parent=\"\" --child=\"\"
        Adds an item as a child of another item.
        - parent      the parent item name
        - child       the child item name

    \$yiic rbac removeChild --parent=\"\" --child=\"\"
        Removes a child from its parent.
        Note, the child item is not deleted.
        Only the parent-child relationship is removed.
        - parent      the parent item name
        - child       the child item name

    \$yiic rbac assign --itemName=\"\" --userId=\"\" [--bizRule=\"\"] [--data=\"\"]
        Assigns an authorization item to a user.
        - itemName    the item name
        - userId      the user ID
        - bizRule     the business rule to be executed when checkAccess
                      is called for this particular authorization item.
        - data        additional data associated with this assign

    \$yiic rbac update --name=\"\" [--description=\"\"] [--bizRule=\"\"] [--data=\"\"]
        Update item. Fil only values that need for update

    \$yiic rbac remove --name=\"\"
        Removes the specified authorization item.
        - the name of the item to be removed\n";
    }

    public function actionOperation($name, $description = '', $bizRule = '', $data = '') {
        if ($this->manager()->createOperation($name, $description, $bizRule, $data)) {
            echo "The operation \"{$name}\" was successfully created\n";
        }
    }

    public function actionTask($name, $description = '', $bizRule = '', $data = '') {
        if ($this->manager()->createTask($name, $description, $bizRule, $data)) {
            echo "The task \"{$name}\" was successfully created\n";
        }
    }

    public function actionRole($name, $description = '', $bizRule = '', $data = '') {
        if ($this->manager()->createRole($name, $description, $bizRule, $data)) {
            echo "The role \"{$name}\" was successfully created\n";
        }
    }

    public function actionChild($parent, $child) {
        if ($this->manager()->addItemChild($parent, $child)) {
            echo "The item \"{$child}\" was successfully added to \"{$parent}\"\n";
        }
    }

    public function actionRemoveChild($parent, $child) {
        if ($this->manager()->removeItemChild($parent, $child)) {
            echo "The item \"{$child}\" was successfully removed from \"{$parent}\"\n";
        }
    }

    public function actionAssign($name, $userId, $bizRule = '', $data = '') {
        if ($this->manager()->assign($name, $userId, $bizRule, $data)) {
            echo "User with {$userId} was successfully assigned to item \"{$name}\"\n";
        }
    }

    public function actionUpdate($name, $description = '', $bizRule = '', $data = '') {
        $item = $this->manager()->getAuthItem($name);
        if (!empty($description)) {
            $item->description = $description;
        }
        if (!empty($bizRule)) {
            $item->bizRule = $bizRule;
        }
        if (!empty($data)) {
            $item->data = $data;
        }

        $this->manager()->saveAuthItem($item);
        echo "The item \"{$name}\" was successfully saved.\n";
    }

    public function actionRemove($name) {
        if ($this->manager()->removeAuthItem($name)) {
            echo "The item \"{$name}\" was successfully removed\n";
        }
    }

    public function actionView($name) {
        $item = $this->manager()->getAuthItem($name);
        if ($item) {
            echo "View auth item $name\n
Name: {$item->name}
Type: {$item->type} - {$this->typeName($item->type)}
Description: {$item->description}
BizRule: {$item->bizRule}
Data: {$item->data}\n";
        } else {
            echo "Item $name not found";
        }
    }

    public function typeName($type) {
        $types = array(
            CAuthItem::TYPE_ROLE      => 'Role',
            CAuthItem::TYPE_OPERATION => 'Operation',
            CAuthItem::TYPE_TASK      => 'Task',
        );

        return isset($types[$type]) ? $types[$type] : 'unknown';
    }

    /**
     * @return CPhpAuthManager
     */
    private function manager() {
        return Yii::app()->authManager;
    }

    function __destruct() {
        $this->manager()->save();
    }

}