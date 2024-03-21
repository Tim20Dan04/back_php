<?php

// Получаем выражение из POST-запроса
$equal = $_POST['equal'];

// Функция для вычисления выражения
function calculateExpression($equal) {
    // Удаляем пробелы из выражения
    $equal = str_replace(' ', '', $equal);

    // Проверяем, есть ли скобки в выражении
    while (strpos($equal, '(') !== false) {
        // Находим самую внутреннюю пару скобок
        $start = strrpos($equal, '(');
        $end = strpos($equal, ')', $start);
        $innerExpression = substr($equal, $start + 1, $end - $start - 1);

        // Рекурсивно вычисляем результат внутреннего выражения
        $innerResult = calculateExpression($innerExpression);

        // Заменяем внутреннее выражение на его результат
        $equal = substr_replace($equal, $innerResult, $start, $end - $start + 1);
    }

    // Вычисляем выражение без скобок
    $result = 0;
    preg_match_all('/(-?\d+|\+|\-|\*|\/|\.)/', $equal, $matches);
    $parts = $matches[0];
    
    foreach ($parts as $key => $part) {
        if ($part === '*' || $part === '/') {
            $left = $parts[$key - 1];
            $right = $parts[$key + 1];
            switch ($part) {
                case '*':
                    $result = calculateExpression($left) * calculateExpression($right);
                    break;
                case '/':
                    $result = calculateExpression($left) / calculateExpression($right);
                    break;
            }
            array_splice($parts, $key - 1, 3, $result);
            return calculateExpression(implode('', $parts));
        }
    }

    // Выполняем операции сложения и вычитания
    // (оставшееся выражение должно содержать только сложение и вычитание)
    foreach ($parts as $key => $part) {
        if ($part === '+' || $part === '-' ) {
            $left = $parts[$key - 1];
            $right = $parts[$key + 1];
            switch ($part) {
                case '+':
                    $result = calculateExpression($left) + calculateExpression($right);
                    break;
                case '-':
                    $result = calculateExpression($left)-calculateExpression($right);
                    break;
            }
            array_splice($parts, $key - 1, 3, $result);
            return calculateExpression(implode('', $parts));
        }
    }
    return (float)$equal;
}

$result = calculateExpression($equal);
echo json_encode($result);
?>


