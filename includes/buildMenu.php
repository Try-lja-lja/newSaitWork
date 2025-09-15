<?php
function buildMenu($items, $startLevel = 1, $depth = 2, $parentId = null, $currentLevel = 1) {
    if ($currentLevel > $startLevel + $depth - 1) {
        return '';
    }

    // Текущий язык (по умолчанию грузинский)
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';
    $titleField = ($lang === 'en') ? 'title_en' : 'title_geo';

    // Получаем пункты текущего уровня
    $levelItems = array();
    foreach ($items as $item) {
        if ($item['parent_id'] === $parentId && (int)$item['is_visible'] === 1) {
            $levelItems[] = $item;
        }
    }

    if (empty($levelItems)) {
        return '';
    }

    // Сортировка по порядку отображения
    usort($levelItems, function ($a, $b) {
        return $a['order_index'] - $b['order_index'];
    });

    $html = '<ul class="menu-level-' . $currentLevel . '">' . PHP_EOL;

    foreach ($levelItems as $item) {
        // Проверяем наличие подменю
        $hasChildren = false;
        foreach ($items as $child) {
            if ($child['parent_id'] === $item['id'] && (int)$child['is_visible'] === 1) {
                $hasChildren = true;
                break;
            }
        }

        $classes = array('menu-item');
        if ($hasChildren) {
            $classes[] = 'has-children';
        }

        $target = ((int)$item['new_tab'] === 1) ? ' target="_blank"' : '';
        $dataUrl = htmlspecialchars($item['url_slug']);
        $title = $item[$titleField];

        $html .= '<li class="' . implode(' ', $classes) . '" data-id="' . $item['id'] . '" data-level="' . $currentLevel . '">' . PHP_EOL;
        $html .= '  <a href="#" data-url="' . $dataUrl . '"' . $target . '>' . $title . '</a>' . PHP_EOL;

        if ($hasChildren) {
            $html .= buildMenu($items, $startLevel, $depth, $item['id'], $currentLevel + 1);
        }

        $html .= '</li>' . PHP_EOL;
    }

    $html .= '</ul>' . PHP_EOL;

    return $html;
}
