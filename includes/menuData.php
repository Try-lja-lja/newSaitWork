<?php
require_once __DIR__ . '/db.php'; // подключение к БД и функция fetchData()

// Получаем все пункты меню, которые нужно показывать
$query = "
    SELECT 
        id,
        parent_id,
        title_geo,
        title_en,
        url_slug,
        is_published,
        type,
        target_page,
        new_tab,
        order_index,
        is_visible
    FROM menuMain
    WHERE is_visible = 1
    ORDER BY parent_id IS NULL DESC, parent_id, order_index
";

$menuItems = fetchData($query);
