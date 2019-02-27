<?php
function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

//считает количество задач у каждого проекта в левой части страницы
function count_tasks($project_name, $tasks) {

  $num = 0;
  foreach ($tasks as $key => $value) {
    if ($value['project_id'] === $project_name) {
      $num = $num + 1;
    }
  }
  return $num;
}

//проверка даты задачи для пометки горящих
function date_check($project_date) {
    $cur_date = strtotime("now");
    $pr_date = strtotime($project_date);
    $diff = $pr_date - $cur_date;
    $diff = $diff / 86400;

    if ($diff > 1) {
        $diff = false;
    }
    elseif ($diff < -17000) {
        $diff = false;
    }
    else {
        $diff = true;
    }
    return $diff;
    //-17 с лишним тысяч - временная метка для текста
}

//получение списка проектов у текущего пользователя
function user_projects($user_id, $con) {
    if ($user_id == 0) {
        $user_projects = [];
    } else {
        $sql = "select * from project where user_id = ";
        $sql = $sql . $user_id;
        $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                print("Ошибка MySQL: " . $error);
            }
            $user_projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
    return $user_projects;
}

//получение списка из всех задач у текущего пользователя
function user_tasks($user_id, $project_id, $con) {
    if ($user_id == 0) {
        $user_projects = [];
    } else {
      if ($project_id === 0) {
          $sql = "select * from task where user_id = '$user_id' ORDER BY date_add DESC";

      }
      else {
          $sql = "select * from task where user_id = ";
          $sql = $sql . $user_id;
          $sql = $sql . " and project_id = " . $project_id;
          $sql = $sql . " ORDER BY date_add DESC";
      }
      $result = mysqli_query($con, $sql);
      if (!$result) {
          $error = mysqli_error($con);
          print("Ошибка MySQL: " . $error);
      }
      $user_projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

    }

    return $user_projects;
}

//получение списка задач по клику на название проекта
function user_projects_cur($user_id, $project_id, $con) {
    $project_id = mysqli_real_escape_string($con, $project_id);
    $sql = "SELECT * FROM task WHERE user_id = '$user_id' AND project_id = '$project_id'";
    $sql = $sql . " ORDER BY date_add DESC";
    $result = mysqli_query($con, $sql);
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    if (!empty($tasks)) {
        return $tasks;
    }
    return [];
}

//проверка текста и защита от инъекций
function text_clean($text) {
    $text = trim($text);
    $text = strip_tags($text);
    $text = htmlspecialchars($text);
    $text = stripslashes($text);
    return $text;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = null;

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
}

function db_fetch_data($link, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $result;
}

function db_insert_data($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $result = mysqli_insert_id($link);
    }
    return $result;
}

function Date_DB_to_Man ($date) {
    if ($date !== null) {
        $result = strtotime($date);
        $result = date ("d-m-Y", $result);
    } else {
        $result = null;
    }
    return $result;
}


// 

?>
