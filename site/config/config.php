<?php

return [
    'reacien.version' => 'v1.0.0',
    'reacien.commit'  => 'a1b4f9e',

    'panel' => [
        'install' => false,
        'vue' => [
            'compiler' => false
        ]
    ],

    'auth' => [
        'methods' => ['password'],
    ],

    'routes' => [
        [
            'pattern' => 'sitemap.xml',
            'action'  => function () {
                $kirby = kirby();
                $site  = $kirby->site();

                $urls = [];
                foreach ($site->index()->listed() as $p) {
                    $urls[] = [
                        'loc'        => $p->url(),
                        'lastmod'    => $p->modified('Y-m-d'),
                        'changefreq' => $p->isHomePage() ? 'weekly' : 'monthly',
                        'priority'   => $p->isHomePage() ? '1.0' : ($p->depth() === 1 ? '0.8' : '0.6'),
                    ];
                }

                $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
                foreach ($urls as $u) {
                    $xml .= "  <url>\n";
                    $xml .= "    <loc>"        . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
                    $xml .= "    <lastmod>"    . $u['lastmod'] . "</lastmod>\n";
                    $xml .= "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
                    $xml .= "    <priority>"   . $u['priority'] . "</priority>\n";
                    $xml .= "  </url>\n";
                }
                $xml .= '</urlset>';

                return new \Kirby\Http\Response($xml, 'application/xml');
            },
        ],
        [
            'pattern' => 'robots.txt',
            'action'  => function () {
                $sitemap = kirby()->site()->url() . '/sitemap.xml';
                $body  = "User-agent: *\n";
                $body .= "Allow: /\n";
                $body .= "Disallow: /panel/\n";
                $body .= "Disallow: /content/\n";
                $body .= "Disallow: /site/\n";
                $body .= "Disallow: /kirby/\n";
                $body .= "\n";
                $body .= "Sitemap: {$sitemap}\n";
                return new \Kirby\Http\Response($body, 'text/plain');
            },
        ],
    ],
];
