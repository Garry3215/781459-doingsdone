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
    return($diff);
    //-17 с лишним тысяч - временная метка для текста
}
?>
