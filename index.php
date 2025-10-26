<?php

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];

// определяем ОС на которой запущен php
function clearScreen()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { // все заглавные, возвращает подстроку, первые три символа, если WIN то виндоуз и используется system('cls');, в противном случае system('clear');
        system('cls');
    } else {
        system('clear');
    }
}

// вывод списка покупок
function displayShoppingList($items)
{
    if (count($items)) { // если массив не пустой
        echo 'Ваш список покупок: ' . PHP_EOL; // выводим строку
        echo implode(PHP_EOL, $items) . PHP_EOL; // выводим объедененные элементы массива в строку с разделителем PHP_EOL
    } else { // если массив пустой
        echo 'Ваш список покупок пуст.' . PHP_EOL; // выводим строку
    }
    echo PHP_EOL;
}

// получаем номер операции
function getOperationNumber($operations, $items)
{
    $operationNumber = null;
    
    do { // выполняем
        clearScreen();  // очищаем консоль
        displayShoppingList($items); // выводим список покупок

        echo 'Выберите операцию для выполнения: ' . PHP_EOL; // выводим строку
        
        // Показываем только доступные операции
        $availableOperations = $operations;  // список доступных операций = все операции
        if (empty($items)) { // есои список покупок пуст
            unset($availableOperations[OPERATION_DELETE]); // удаляем операцию удаления
        }
        
        echo implode(PHP_EOL, $availableOperations) . PHP_EOL . '> '; // выводим список операций
        $input = trim(fgets(STDIN)); // ввод от пользователя
        
        if (is_numeric($input)) { // если пользователь ввел целое число
            $operationNumber = (int)$input; // присваиваем его в  $operationNumber
        }

        if (!array_key_exists($operationNumber, $operations)) { // если  $operationNumber НЕ соответствует ключу из $operations
            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
            echo 'Нажмите enter для продолжения...';
            fgets(STDIN);
        }

    } while (!array_key_exists($operationNumber, $operations)); // пока не будет введен правильный номер операции

    return $operationNumber; // возвращаем номер операции
}

// добавление товара в список
function addItem($items)
{
    echo "Введите название товара для добавления в список: " . PHP_EOL . "> ";
    $itemName = trim(fgets(STDIN)); // ввод от пользователя
    
    if (!empty($itemName)) { // если не пусто - добавляем в массив $items[]
        $items[] = $itemName;
        echo "Товар '{$itemName}' добавлен в список!" . PHP_EOL;
    } else { // в противном случае выводим:
        echo "Нельзя добавить пустой товар!" . PHP_EOL;
    }
    
    echo 'Нажмите enter для продолжения...';
    fgets(STDIN);
    
    return $items;
}

// удаление из списка покупок
function deleteItem($items)
{
    if (empty($items)) { // если массив пуст, выводим:
        echo "Список покупок пуст, нечего удалять!" . PHP_EOL;
        echo 'Нажмите enter для продолжения...';
        fgets(STDIN);
        return $items;
    }
    // если массив не пуст, выводим строку и список товаров
    echo 'Текущий список покупок:' . PHP_EOL;
    displayShoppingList($items);
    // выводим строку и ввдо от пользователя
    echo 'Введите название товара для удаления из списка:' . PHP_EOL . '> ';
    $itemName = trim(fgets(STDIN));

    $found = false;
    $newItems = [];
    foreach ($items as $item) { // проходим все элементы массива
        if ($item !== $itemName) { // если элемент не соответствует удаляемому
            $newItems[] = $item; // добавляем в новый массив
        } else { // в противном случае присваиваем $found = true;
            $found = true;
        }
    }

    if ($found) { // если $found = true;
        echo "Товар '{$itemName}' удален из списка!" . PHP_EOL; // выводим строку
        $items = $newItems; // присваиваем массиву $items значение массива $newItems
    } else {// в противном случае выводим строку
        echo "Товар '{$itemName}' не найден в списке!" . PHP_EOL;
    }

    echo 'Нажмите enter для продолжения...';
    fgets(STDIN);

    return $items; // возвращаем обновленный массив
}

// вывод списка покупок
function printItems($items)
{
    clearScreen();
    echo '=== ВАШ СПИСОК ПОКУПОК ===' . PHP_EOL; // вывод строки
    displayShoppingList($items); // вызов функции вывода списка покупок
    echo 'Всего ' . count($items) . ' позиций.' . PHP_EOL; // вывод строки и количества покупок
    echo 'Нажмите enter для продолжения...'; // вывод строки
    fgets(STDIN);
}

// Основная программа
clearScreen();
echo '=== ПРОГРАММА "СПИСОК ПОКУПОК" ===' . PHP_EOL;
echo 'Нажмите enter для начала...';
fgets(STDIN);

do {
    $operationNumber = getOperationNumber($operations, $items);

    echo 'Выбрана операция: ' . $operations[$operationNumber] . PHP_EOL . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD: // если выбрана операция добавления, вызывается функция addItem($items);
            $items = addItem($items); // возращаемое значение присваивается $items
            break;

        case OPERATION_DELETE: // если выбрана операция добавления, вызывается функция deleteItem($items);
            $items = deleteItem($items); // возращаемое значение присваивается $items
            break;

        case OPERATION_PRINT: // если выбрана операция вывода, вызывается функция printItems($items);
            printItems($items);
            break;

        case OPERATION_EXIT:
            echo 'Завершение программы...' . PHP_EOL;
            break;
    }

} while ($operationNumber !== OPERATION_EXIT); // программа выполняется пока не выбрана команда  OPERATION_EXIT

clearScreen();
echo 'Программа завершена. Спасибо за использование!' . PHP_EOL;
