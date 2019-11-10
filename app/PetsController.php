<?php
namespace App;


class PetsController extends BaseController {

    public function searchItems() {
        $this->smarty->assign('TITLE', 'Търсене на домашни любимци');

        $this->smarty->assign('TYPES', $this->petsTypes());

        return $this->smarty->fetch('search.html');
    }

    public function listItems() {
        $this->smarty->assign('TITLE', 'Списък на домашни любимци');

        $result = [];
        $params = [];
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $type = isset($_POST['type']) ? (int) $_POST['type'] : 0;
        $where = '';

        if (mb_strlen($name)) {
            $where = "WHERE
                a.`name` LIKE :name
            ";
            $params[':name'] = '%' . $name .'%';
        }

        if ($type > 0) {
            $where .= mb_strlen($where) ? ' AND  a.`type_id` = :type' : 'WHERE  a.`type_id` = :type';
            $params[':type'] = $type;
        }

        $sql = "SELECT
                    a.`id`,
                    a.`name` AS 'pet_name',
                    a.`description`,
                    a.`created_at`,
                    t.`name` AS 'pet_type'
                FROM `".TABLE_ANIMALS."` a
                LEFT JOIN `".TABLE_ANIMAL_TYPES."` t ON
                    a.`type_id` = t.`id`
                ".$where."
                ORDER BY a.`created_at` DESC
        ";

        $query = $this->db->prepare($sql);

        if (!$query->execute(count($params) ? $params : null)) {
            $this->smarty->assign('RESULT', $result);
            return $this->smarty->fetch('list.html');
        }

        while ($row = $query->fetch()) {
            $result[] = [
                'id' => $row['id'],
                'pet_name' => $row['pet_name'],
                'description' => $row['description'],
                'type' => $row['pet_type'],
                'date' => date('d-m-Y H:i:s', strtotime($row['created_at'])),
            ];
        }
        $this->smarty->assign('SEARCH_WORD', $name);
        $this->smarty->assign('RESULT', $result);
        return $this->smarty->fetch('list.html');

    }

    public function addItems() {
        $this->smarty->assign('TITLE', 'Добавяне - Домашни любимци');

        $this->smarty->assign('TYPES', $this->petsTypes());

        if (!isset($_POST['submit'])) {
            return $this->smarty->fetch('add.items.html');
        }

        $errors = [];

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $type = isset($_POST['type']) ? (int) $_POST['type'] : 0;

        if (!mb_strlen($name)) {
            $errors[] = 'Моля, напишете името на домашния любимец';
        } else if (mb_strlen($name) > 64) {
            $errors[] = 'Името на домашния любимец, трябва да е до 64 символа';
        }

        if (!mb_strlen($description)) {
            $errors[] = 'Моля, напишете описание на домашния любимец';
        } else if (mb_strlen($description) > 255) {
            $errors[] = 'Описанието трябва да е до 255 символа';
        }

        if ($type < 1) {
            $errors[] = 'Невалиден тип любимец';
        }

        if (count($errors)) {
            $this->smarty->assign('ERRORS', $errors);
            $this->smarty->assign('RESULT', [
                'name' => $name,
                'description' => $description,
                'type' => $type
            ]);
            return $this->smarty->fetch("add.items.html");
        }


        $sql = " INSERT INTO `".TABLE_ANIMALS."`
                (
                    `type_id`,
                    `name`,
                    `description`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    :type,
                    :name,
                    :description,
                    NOW(),
                    NOW()
                )
        ";

        $query = $this->db->prepare($sql);

        $params = [
            ':type' => $type,
            ':name' => $name,
            ':description' => $description
        ];

        if ($query->execute($params)) {
            $this->smarty->assign('SUCCESS', 'Успешно добавихте нов домашен любимец');

        } else {
            $this->smarty->assign('FAILED', 'Получи се грешка при добавяне на домашния любимец, моля свържете се с нас');
        }

		return $this->smarty->fetch("add.items.html");
    }

    protected function petsTypes() {

        $result[] = [
            'id' => 0,
            'name' => 'Моля, изберете тип',
        ];
        $sql = "SELECT
                    `id`,
                    `name`
                FROM `".TABLE_ANIMAL_TYPES."`
        ";

        $query = $this->db->prepare($sql);

        if (!$query->execute()) {
            return $result;
        }

        while($row = $query->fetch()) {
			$result[] = [
				'id' => $row['id'],
				'name' => $row['name'],
			];
		}

		return $result;
    }
}