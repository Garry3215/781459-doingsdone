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

function count_tasks($project_name, $tasks) {

  $num = 0;
  foreach ($tasks as $key => $value) {
    if ((string)$value['category'] === (string)$project_name) {
      $num = $num + 1;
    }
  }
  return $num;
}
?>
