<?php
// buildMenu.php — рендер многоуровневого меню (совместимо с PHP 5.x)

if (!defined('PAGES_URL')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Оборачивает [[...]] в <span class="font-alt">...</span>.
 */
function wrapFontAlt($text) {
    return preg_replace_callback('/\[\[(.+?)\]\]/u', function ($m) {
        $inner = htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8');
        return '<span class="font-alt">'.$inner.'</span>';
    }, $text);
}

/**
 * Собирает href/атрибуты для <a>.
 * Возврат: array($href, $target, $rel, $dataUrl)
 */
function makeLinkAttrs($item) {
    $type       = isset($item['type']) ? $item['type'] : 'static';
    $slug       = trim((string)(isset($item['url_slug']) ? $item['url_slug'] : ''));
    $targetPage = trim((string)(isset($item['target_page']) ? $item['target_page'] : ''));
    $newTab     = (int)(isset($item['new_tab']) ? $item['new_tab'] : 0) === 1;

    $href   = '#';
    $target = '';
    $rel    = '';
    $dataUrl = null;

    if ($type === 'external') {
        if (preg_match('~^https?://~i', $targetPage)) {
            $href = $targetPage;
        } else {
            $href = '#';
        }
        if ($newTab) {
            $target = ' target="_blank"';
            $rel    = ' rel="noopener noreferrer"';
        }
        // для внешних ссылок data-url НЕ ставим
    } elseif ($type === 'dynamic') {
        $href    = '#';
        $dataUrl = $slug !== '' ? $slug : null;
        if ($newTab) {
            $target = ' target="_blank"';
            $rel    = ' rel="noopener noreferrer"';
        }
    } else { // static (или по умолчанию)
        if ($slug !== '') {
            $href = PAGES_URL . rawurlencode($slug) . '/';
        } else {
            $href = '#';
        }
        $dataUrl = $slug !== '' ? $slug : null;

        if ($newTab) {
            $target = ' target="_blank"';
            $rel    = ' rel="noopener noreferrer"';
        }
    }

    return array($href, $target, $rel, $dataUrl);
}

/**
 * Есть ли у пункта дети.
 */
function itemHasChildren($items, $parentId) {
    foreach ($items as $it) {
        if ((int)$it['is_visible'] === 1 && $it['parent_id'] === $parentId) {
            return true;
        }
    }
    return false;
}

/**
 * Основной рендер.
 */
function buildMenu($items, $startLevel = 1, $depth = 2, $parentId = null, $currentLevel = 1) {
    if ($currentLevel > $startLevel + $depth - 1) {
        return '';
    }

    // язык
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';
    $titleField = ($lang === 'en') ? 'title_en' : 'title_geo';

    // элементы текущего уровня
    $levelItems = array();
    foreach ($items as $item) {
        if ((int)$item['is_visible'] !== 1) continue;
        if ($item['parent_id'] === $parentId) {
            $levelItems[] = $item;
        }
    }

    if (empty($levelItems)) {
        return '';
    }

    // сортировка по order_index (без оператора <=>)
    usort($levelItems, function ($a, $b) {
        $ai = (int)$a['order_index'];
        $bi = (int)$b['order_index'];
        if ($ai === $bi) return 0;
        return ($ai < $bi) ? -1 : 1;
    });

    $html = '';

    // если мы ниже стартового уровня — не выводим ul/li здесь, а уходим глубже
    if ($currentLevel < $startLevel) {
        foreach ($levelItems as $item) {
            $html .= buildMenu($items, $startLevel, $depth, $item['id'], $currentLevel + 1);
        }
        return $html;
    }

    // рендер нужного уровня
    $html .= '<ul class="menu-level-' . (int)$currentLevel . '">' . PHP_EOL;

    foreach ($levelItems as $item) {
        $hasChildren = itemHasChildren($items, $item['id']);

        $classes = array('menu-item');
        if ($hasChildren) $classes[] = 'has-children';

        list($href, $target, $rel, $dataUrl) = makeLinkAttrs($item);

        // заголовок + подсветка [[...]]
        $rawTitle = isset($item[$titleField]) ? (string)$item[$titleField] : '';
        $safeTitle = htmlspecialchars($rawTitle, ENT_QUOTES, 'UTF-8');
        $titleHtml = wrapFontAlt($safeTitle);

        $html .= '  <li class="' . implode(' ', $classes) . '" data-id="' . (int)$item['id'] . '" data-level="' . (int)$currentLevel . '">' . PHP_EOL;
        $html .= '    <a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"'
              . ($dataUrl ? ' data-url="' . htmlspecialchars($dataUrl, ENT_QUOTES, 'UTF-8') . '"' : '')
              . $target . $rel . '>' . $titleHtml . '</a>' . PHP_EOL;

        if ($hasChildren) {
            $html .= buildMenu($items, $startLevel, $depth, $item['id'], $currentLevel + 1);
        }

        $html .= '  </li>' . PHP_EOL;
    }

    $html .= '</ul>' . PHP_EOL;

    return $html;
}
