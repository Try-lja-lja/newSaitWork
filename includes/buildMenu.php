<?php
// includes/buildMenu.php
if (!defined('PAGES_URL')) {
    require_once __DIR__ . '/config.php';
}

function wrapFontAlt(string $text): string
{
    return preg_replace_callback('/\[\[(.+?)\]\]/u', function ($m) {
        $inner = htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8');
        return '<span class="font-alt">' . $inner . '</span>';
    }, $text);
}

function makeLinkAttrs(array $item): array
{
    $type       = isset($item['type']) ? (string)$item['type'] : 'static';
    $slug       = trim((string)($item['url_slug']     ?? ''));
    $targetPage = trim((string)($item['target_page']  ?? ''));
    $newTab     = ((int)($item['new_tab']      ?? 0) === 1);
    $isPub      = ((int)($item['is_published'] ?? 0) === 1);

    $href    = '#';
    $target  = '';
    $rel     = '';
    $dataUrl = null;

    if ($type === 'external') {
        if ($targetPage !== '') {
            $href = $targetPage;
            $rel  = ' rel="noopener noreferrer"';
        }
    } elseif ($type === 'dynamic') {
        if ($targetPage !== '') {
            $href = '/?page=' . rawurlencode($targetPage);
        }
    } else { // static
        if ($slug !== '') {
            if ($isPub) {
                $href = rtrim(PAGES_URL, '/') . '/' . rawurlencode($slug) . '/';
            } else {
                $href = rtrim(PAGES_URL, '/') . '/coming-soon/?slug=' . rawurlencode($slug);
            }
        }
        $dataUrl = ($slug !== '') ? $slug : null;
    }

    if ($newTab) {
        $target = ' target="_blank"';
        if (strpos($rel, 'noopener') === false) {
            $rel = trim($rel . ' rel="noopener noreferrer"');
            if ($rel !== '' && $rel[0] !== ' ') {
                $rel = ' ' . $rel;
            }
        }
    }

    return array($href, $target, $rel, $dataUrl);
}

function itemHasChildren(array $items, $parentId): bool
{
    $pid = (string)($parentId ?? '');
    foreach ($items as $it) {
        if ((int)($it['is_visible'] ?? 0) !== 1) continue;
        if ((string)($it['parent_id'] ?? '') === $pid) {
            return true;
        }
    }
    return false;
}

function renderLevelByParent(array $items, int $depth, $parentId, int $currentLevel, string $titleField): string
{
    if ($depth < 1) return '';

    $pid = (string)($parentId ?? '');
    $levelItems = array();
    foreach ($items as $item) {
        if ((int)($item['is_visible'] ?? 0) !== 1) continue;
        if ((string)($item['parent_id'] ?? '') === $pid) {
            $levelItems[] = $item;
        }
    }
    if (empty($levelItems)) return '';

    usort($levelItems, function ($a, $b) {
        $ai = (int)($a['order_index'] ?? 0);
        $bi = (int)($b['order_index'] ?? 0);
        if ($ai === $bi) return 0;
        return ($ai < $bi) ? -1 : 1;
    });

    $html = '<ul class="menu-level-' . (int)$currentLevel . '">' . PHP_EOL;

    foreach ($levelItems as $item) {
        $hasChildren = itemHasChildren($items, $item['id'] ?? null);
        $classes = array('menu-item');
        if ($hasChildren) $classes[] = 'has-children';

        $li = '  <li class="' . implode(' ', $classes) . '" data-id="' . (int)($item['id'] ?? 0) . '">' . PHP_EOL;

        list($href, $targetAttr, $relAttr, $dataUrl) = makeLinkAttrs($item);

        $langTitle = isset($item[$titleField]) ? (string)$item[$titleField] : '';
        $safeTitle = htmlspecialchars($langTitle, ENT_QUOTES, 'UTF-8');
        $titleHtml = wrapFontAlt($safeTitle);

        $aAttrs  = ' href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"';
        $aAttrs .= $targetAttr;
        $aAttrs .= $relAttr;
        if ($dataUrl !== null) {
            $aAttrs .= ' data-url="' . htmlspecialchars($dataUrl, ENT_QUOTES, 'UTF-8') . '"';
        }

        $li .= '    <a' . $aAttrs . '>' . $titleHtml . '</a>' . PHP_EOL;

        if ($hasChildren) {
            $li .= renderLevelByParent($items, $depth - 1, $item['id'] ?? null, $currentLevel + 1, $titleField);
        }

        $li .= '  </li>' . PHP_EOL;
        $html .= $li;
    }

    $html .= '</ul>' . PHP_EOL;
    return $html;
}

function buildMenu(array $items, int $depth = 2, ?int $parentId = null, int $currentLevel = 1): string
{
    $lang       = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ka';
    $titleField = ($lang === 'en') ? 'title_en' : 'title_geo';
    return renderLevelByParent($items, $depth, $parentId, $currentLevel, $titleField);
}
